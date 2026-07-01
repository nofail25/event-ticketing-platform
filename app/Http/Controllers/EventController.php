<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where('organizer_id', Auth::id())->latest()->paginate(10);
        $profile = Auth::user()->organizerProfile;
        return view('organizer.events.index', compact('events', 'profile'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $profile = Auth::user()->organizerProfile;
        if (!$profile || $profile->verification_status !== 'verified') {
            return redirect()->route('profile.edit')->with('error', 'Anda harus melengkapi profil dan diverifikasi oleh admin sebelum dapat membuat event.');
        }

        return view('organizer.events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        $profile = Auth::user()->organizerProfile;
        if (!$profile || $profile->verification_status !== 'verified') {
            return redirect()->route('profile.edit')->with('error', 'Anda harus melengkapi profil dan diverifikasi oleh admin sebelum dapat membuat event.');
        }

        $validated = $request->validated();

        if ($request->hasFile('banner_image')) {
            $validated['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        $validated['organizer_id'] = Auth::id();
        $validated['status'] = 'pending';

        Event::create($validated);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dibuat dan diajukan untuk persetujuan admin.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $event->load('ticketCategories');

        return view('organizer.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        return view('organizer.events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validated();
        unset($validated['status']);

        if ($request->hasFile('banner_image')) {
            if ($event->banner_image) {
                Storage::disk('public')->delete($event->banner_image);
            }
            $validated['banner_image'] = $request->file('banner_image')->store('banners', 'public');
        }

        if (in_array($event->status, ['active', 'rejected'])) {
            $validated['status'] = 'pending'; // BUG-09 FIX: rejected events re-enter review queue when edited
        }

        $event->update($validated);

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil diperbarui. Event aktif dikembalikan untuk persetujuan admin setelah diedit.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if ($event->organizer_id !== Auth::id()) {
            abort(403);
        }

        // BUG-18 REFIX: Block deletion if there are ANY orders (including pending/failed) 
        // to prevent orphaned orders from causing SQL constraint errors on payment attempts.
        $hasOrders = \App\Models\Order::whereHas('ticketCategory', function ($q) use ($event) {
            $q->where('event_id', $event->id);
        })->exists();

        if ($hasOrders) {
            return redirect()->route('organizer.events.index')->with('danger', 'Event tidak dapat dihapus karena sudah ada pesanan atau tiket yang terjual.');
        }

        if ($event->banner_image) {
            Storage::disk('public')->delete($event->banner_image);
        }

        $event->delete();

        return redirect()->route('organizer.events.index')->with('success', 'Event berhasil dihapus.');
    }
}
