<?php

namespace App\Http\Controllers;

use App\Models\OrganizerProfile;
use Illuminate\Http\Request;

class AdminOrganizerController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        
        $profiles = OrganizerProfile::with('user')
            ->where('verification_status', $status)
            ->latest()
            ->paginate(15);
            
        return view('admin.organizers.index', compact('profiles', 'status'));
    }

    public function verify(Request $request, OrganizerProfile $profile)
    {
        $validated = $request->validate([
            'status' => 'required|in:verified,rejected',
        ]);

        $profile->verification_status = $validated['status'];
        $profile->save();

        // Notify the organizer user of the decision
        $organizer = $profile->user;
        if ($organizer) {
            $isVerified = $validated['status'] === 'verified';
            $organizer->notify(new \App\Notifications\OrganizerVerified($isVerified));
        }

        $message = $validated['status'] === 'verified' 
            ? 'Profil Organizer berhasil disetujui.' 
            : 'Profil Organizer ditolak.';

        return back()->with('success', $message);
    }
}
