<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommunityProfileController extends Controller
{
    public function edit(Request $request): View
    {
        abort_unless($request->user()->role === 'community', 403);
        $user = $request->user()->load('community');

        return view('community.profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        abort_unless($request->user()->role === 'community', 403);
        $validated = $request->validate([
            'nama_community' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'kota' => ['nullable', 'string', 'max:150'],
            'kontak' => ['nullable', 'string', 'max:50', 'regex:/^[0-9+().@\\s-]+$/'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $community = $request->user()->community()->firstOrCreate([
            'nama_community' => $request->user()->name,
        ]);
        $data = collect($validated)->except('logo')->all();
        if ($request->hasFile('logo')) {
            if ($community->logo) {
                Storage::disk('public')->delete($community->logo);
            }
            $data['logo'] = $request->file('logo')->store('community-logos', 'public');
        }
        $community->update($data);
        $request->user()->update(['name' => $validated['nama_community']]);

        return redirect()->route('community.profile.edit')->with('success', 'Profil komunitas berhasil diperbarui.');
    }
}