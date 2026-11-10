<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        abort_unless($request->user()->role === 'user', 403);
        $user = $request->user()->load('profilUser');

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'user', 403);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:25', 'regex:/^[0-9+().\\s-]+$/'],
            'tanggal_lahir' => ['nullable', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['nullable', 'in:Laki-laki,Perempuan,Memilih Tidak Menyebutkan'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'preferensi_lokasi' => ['nullable', 'string', 'max:150'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $user->update(['name' => $validated['name']]);
        $profile = $user->profilUser()->firstOrCreate([]);
        $profileData = collect($validated)->only([
            'no_hp', 'tanggal_lahir', 'jenis_kelamin', 'alamat', 'preferensi_lokasi',
        ])->all();

        if ($request->hasFile('foto_profil')) {
            if ($profile->foto_profil) {
                Storage::disk('public')->delete($profile->foto_profil);
            }
            $profileData['foto_profil'] = $request->file('foto_profil')->store('profile-photos', 'public');
        }

        $profile->update($profileData);

        return redirect()->route('profile.edit')->with('success', 'Profil relawan berhasil diperbarui.');
    }

    public function destroyAvatar(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'user', 403);
        $profile = $request->user()->profilUser;
        if ($profile?->foto_profil) {
            Storage::disk('public')->delete($profile->foto_profil);
            $profile->update(['foto_profil' => null]);
        }

        return redirect()->route('profile.edit')->with('success', 'Foto profil berhasil dihapus.');
    }
}
