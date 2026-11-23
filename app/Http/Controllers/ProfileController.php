<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    // Menampilkan halaman profile
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // Menampilkan form edit profile
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Update data profile
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nim' => 'required|string|unique:users,nim,' . $user->id,
            'fakultas' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'angkatan' => 'nullable|string|max:4',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string|max:500',
        ]);

        $user->update($validated);

        return redirect()->route('profile')
            ->with('success', 'Profile berhasil diperbarui!');
    }

    // Update password
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password saat ini tidak sesuai.'
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('profile')
            ->with('success', 'Password berhasil diperbarui!');
    }

    // Upload foto profil
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Hapus foto lama jika ada
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Simpan foto baru
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'profile-' . $user->id . '-' . time() . '.' . $file->getClientOriginalExtension();

            $path = $file->storeAs('profiles', $filename, 'public');

            $user->update(['foto_profil' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui!');
    }
}
