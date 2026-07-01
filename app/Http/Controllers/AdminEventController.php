<?php

namespace App\Http\Controllers;

use App\Models\Event;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('organizer')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function approve(Event $event)
    {
        // Safety check: ensure organizer is still verified before publishing
        $organizerProfile = $event->organizer?->organizerProfile;
        if (!$organizerProfile || $organizerProfile->verification_status !== 'verified') {
            return back()->with('danger', "Tidak dapat menyetujui event. Profil Organizer \"{$event->organizer?->name}\" belum terverifikasi.");
        }

        $event->update(['status' => 'active']);

        return back()->with('success', "Event \"{$event->title}\" telah disetujui dan dipublikasikan.");
    }

    public function reject(Event $event)
    {
        $event->update(['status' => 'rejected']);

        return back()->with('success', "Event \"{$event->title}\" telah ditolak.");
    }
}
