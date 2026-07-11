<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $technician = $user->technician;

        if (! $technician) {
            abort(404);
        }

        return view('technician.profile.edit', compact('user', 'technician'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $technician = $user->technician;

        if (! $technician) {
            abort(404);
        }

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'phone'         => ['required', 'string', Rule::unique('users', 'phone')->ignore($user->id)],
            'password'      => ['nullable', 'confirmed', Password::min(8)],
            'experience'    => ['nullable', 'string', 'max:100'],
            'is_available'  => ['nullable', 'boolean'],
            'avatar'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'remove_avatar' => ['nullable', 'boolean'],
        ], [
            'email.unique' => 'Email sudah digunakan akun lain.',
            'phone.unique' => 'Nomor WhatsApp sudah digunakan akun lain.',
            'avatar.image' => 'Foto profil harus berupa gambar.',
            'avatar.max'   => 'Ukuran foto profil maksimal 2MB.',
        ]);

        $userData = [
            'name'  => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];

        if ($request->boolean('remove_avatar') && $user->avatar_url) {
            Storage::disk('public')->delete($user->avatar_url);
            $userData['avatar_url'] = null;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url) {
                Storage::disk('public')->delete($user->avatar_url);
            }
            $userData['avatar_url'] = $request->file('avatar')->store('avatars', 'public');
        }

        if (filled($data['password'] ?? null)) {
            $userData['password'] = Hash::make($data['password']);
        }

        $user->update($userData);

        $technician->update([
            'experience'   => $data['experience'] ?? null,
            'is_available' => $request->boolean('is_available'),
        ]);

        return redirect()->route('technician.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
