<?php

namespace App\Http\Controllers;

use App\Models\OrganizerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class OrganizerProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->organizerProfile ?? new OrganizerProfile();
        return view('organizer.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->organizerProfile;

        // Validasi input
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'company_description' => 'nullable|string',
            'pic_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'website_url' => 'nullable|url|max:255',
            'legal_document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:100',
        ]);

        if (!$profile) {
            $profile = new OrganizerProfile();
            $profile->user_id = $user->id;
        }

        // Jangan izinkan edit jika sudah verified, kecuali ada fitur re-verifikasi
        if ($profile->verification_status === 'verified') {
            throw ValidationException::withMessages([
                'verification' => 'Profil Anda sudah diverifikasi dan tidak dapat diubah. Hubungi admin jika perlu perubahan.',
            ]);
        }

        $profile->company_name = $validated['company_name'];
        $profile->company_description = $validated['company_description'];
        $profile->pic_name = $validated['pic_name'];
        $profile->phone_number = $validated['phone_number'];
        $profile->website_url = $validated['website_url'];
        $profile->bank_name = $validated['bank_name'];
        $profile->account_number = $validated['account_number'];
        $profile->account_holder = $validated['account_holder'];

        if ($request->hasFile('legal_document')) {
            if ($profile->legal_document_path) {
                Storage::disk('public')->delete($profile->legal_document_path);
            }
            $profile->legal_document_path = $request->file('legal_document')->store('organizer_documents', 'public');
        }

        // Set status ke pending setelah update/submit
        $profile->verification_status = 'pending';
        $profile->save();

        return redirect()->route('profile.edit')->with('status', 'organizer-profile-updated');
    }
}
