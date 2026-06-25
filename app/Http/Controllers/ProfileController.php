<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile.show', compact('user'));
    }

    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['required', 'string'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => ['required', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $user = auth()->user();
        $file = $request->file('avatar');
        $mime = $file->getMimeType();
        $contents = file_get_contents($file->getRealPath());
        $dataUri = 'data:' . $mime . ';base64,' . base64_encode($contents);

        $user->update(['avatar' => $dataUri]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function removeAvatar()
    {
        $user = auth()->user();
        $user->update(['avatar' => null]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil dihapus.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.'])->withInput();
        }

        $user->update([
            'password' => bcrypt($validated['password']),
        ]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
    }
}
