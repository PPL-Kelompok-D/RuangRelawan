<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->merge([
            'role' => $this->requestedRole($request),
        ]);

        if (
            !$request->filled('identifier') &&
            ($request->filled('login') || $request->filled('email'))
        ) {
            $request->merge([
                'identifier' => $request->input(
                    'login',
                    $request->input('email')
                ),
            ]);
        }

        $credentials = $request->validate([
            'role' => ['required', 'in:user,community,admin'],
            'identifier' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        // Login menggunakan email
        if (filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $credentials['identifier'])->first();
        } else {
            // Login menggunakan nomor HP komunitas
            $user = User::whereHas('community', function ($query) use ($credentials) {
                $query->where('kontak', $credentials['identifier']);
            })->first();
        }

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors([
                    'login' => 'Email/nomor ponsel atau kata sandi yang Anda masukkan salah.',
                ])
                ->onlyInput('login', 'role');
        }

        if ($user->role !== $credentials['role']) {
            $label = [
                'user' => 'Relawan',
                'community' => 'Komunitas',
                'admin' => 'Admin',
            ];

            return back()
                ->withErrors([
                    'role' => 'Akun ini terdaftar sebagai ' .
                        ($label[$user->role] ?? $user->role) .
                        '. Silakan pilih peran yang sesuai.',
                ])
                ->onlyInput('login', 'role');
        }

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route(
            $user->role === 'community' ? 'community.profile.edit' : 'home'
        )->with('success', 'Selamat datang kembali di RuangRelawan!');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'role' => $this->requestedRole($request) ?? 'user',
        ]);

        if (!$request->filled('no_hp') && $request->filled('phone')) {
            $request->merge([
                'no_hp' => $request->input('phone'),
            ]);
        }

        if (!$request->filled('kota') && $request->filled('city')) {
            $request->merge([
                'kota' => $request->input('city'),
            ]);
        }

        $role = $request->input('role');

        $validated = $request->validate([
            'role' => ['required', 'in:user,community'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['required', 'string', 'max:25'],
            'kota' => [
                $role === 'community' ? 'required' : 'nullable',
                'string',
                'max:150',
            ],
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => ['accepted'],
        ], [
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan serta Kebijakan Privasi.',
            'kota.required' => 'Kota basis operasional komunitas wajib diisi.',
        ]);

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
            ]);

            // Membuat profil komunitas
            if ($user->role === 'community') {
                $user->community()->create([
                    'nama_community' => $validated['name'],
                    'kota' => $validated['kota'],
                    'kontak' => $validated['no_hp'],
                ]);
            }

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->role === 'community') {
            return redirect()
                ->route('community.profile.edit')
                ->with('success', 'Akun komunitas berhasil dibuat. Selamat bergabung!');
        }

        return redirect()
            ->route('home')
            ->with('success', 'Akun RuangRelawan berhasil dibuat!');
    }

    public function logout(Request $request): RedirectResponse
    {
        $roleSelection = match ($request->user()?->role) {
            'community' => 'komunitas',
            'admin' => 'admin',
            default => 'relawan',
        };

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->withInput(['role' => $roleSelection])
            ->with('success', 'Anda telah berhasil keluar.');
    }

    private function requestedRole(Request $request): ?string
    {
        $role = $request->input('role', $request->input('peran'));

        return match ($role) {
            'relawan' => 'user',
            'komunitas' => 'community',
            default => $role,
        };
    }
}
