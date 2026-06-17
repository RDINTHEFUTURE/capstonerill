<?php

namespace App\Http\Controllers;

use App\Models\CompanyProfile;
use Illuminate\Http\Request;

class CompanyProfileController extends Controller
{
    public function edit()
    {
        $profile = CompanyProfile::getProfile();
        return view('settings.company', compact('profile'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'npwp' => ['nullable', 'string', 'max:32'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'logo' => ['nullable', 'file', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ]);

        $profile = CompanyProfile::getProfile();

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $mime = $file->getMimeType();
            $contents = file_get_contents($file->getRealPath());
            $validated['logo'] = 'data:' . $mime . ';base64,' . base64_encode($contents);
        }

        $profile->update($validated);

        return redirect()->route('settings.company')->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}
