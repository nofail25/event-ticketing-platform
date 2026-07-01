<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\TicketCategory;
use Illuminate\Http\Request;

class TicketCategoryController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        if ($event->status === 'completed') {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Tidak dapat menambah tiket pada event yang sudah selesai.');
        }
        return view('organizer.ticket-categories.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event)
    {
        if ($event->organizer_id !== auth()->id()) {
            abort(403);
        }
        if ($event->status === 'completed') {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Tidak dapat menambah tiket pada event yang sudah selesai.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        $event->ticketCategories()->create($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Kategori tiket berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }
        if ($event->status === 'completed') {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Tidak dapat mengubah tiket pada event yang sudah selesai.');
        }

        return view('organizer.ticket-categories.edit', compact('event', 'ticketCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }
        if ($event->status === 'completed') {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Tidak dapat mengubah tiket pada event yang sudah selesai.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        // Prevent reducing quota below paid tickets plus active pending reservations.
        $activeSoldCount = $ticketCategory->activeSoldCount();
        if ((int) $validated['quota'] < $activeSoldCount) {
            return redirect()->back()
                ->withErrors(['quota' => "Kuota tidak dapat dikurangi di bawah jumlah tiket terjual atau sedang dipesan ({$activeSoldCount} tiket)."])
                ->withInput();
        }

        $ticketCategory->update($validated);

        return redirect()->route('organizer.events.show', $event)->with('success', 'Kategori tiket berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event, TicketCategory $ticketCategory)
    {
        if ($event->organizer_id !== auth()->id() || $ticketCategory->event_id !== $event->id) {
            abort(403);
        }
        if ($event->status === 'completed') {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Tidak dapat menghapus tiket pada event yang sudah selesai.');
        }

        $hasOrders = \App\Models\Order::where('ticket_category_id', $ticketCategory->id)->exists();

        if ($hasOrders) {
            return redirect()->route('organizer.events.show', $event)->with('danger', 'Kategori tiket tidak dapat dihapus karena sudah ada pesanan atau tiket yang terjual.');
        }

        $ticketCategory->delete();

        return redirect()->route('organizer.events.show', $event)->with('success', 'Kategori tiket berhasil dihapus.');
    }
}
