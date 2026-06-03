<?php

namespace App\Http\Controllers;

use App\Models\TicketDetail;
use Illuminate\Http\Request;

class ScannerController extends Controller
{
    /**
     * Verify a ticket by barcode string.
     */
    public function verify(Request $request)
    {
        // Validate the barcode string input
        $validated = $request->validate([
            'barcode_string' => 'required|string|uuid',
        ], [
            'barcode_string.required' => 'Please enter a barcode.',
            'barcode_string.uuid' => 'Invalid barcode format.',
        ]);

        $barcodeString = $validated['barcode_string'];

        // Find the ticket detail by barcode string
        $ticket = TicketDetail::where('barcode_string', $barcodeString)
            ->with([
                'order.user',
                'ticketCategory.event',
            ])
            ->first();

        // Logic Check 1: Ticket not found
        if (!$ticket) {
            return redirect()->back()
                ->withErrors(['scan_result' => 'INVALID_TICKET'])
                ->with('scan_message', 'Invalid Ticket / Not Found');
        }

        // Logic Check 2: Ticket already scanned
        if ($ticket->is_scanned) {
            $scannedAt = $ticket->scanned_at->format('M d, Y \a\t h:i A');
            return redirect()->back()
                ->with('scan_warning', 'ALREADY_SCANNED')
                ->with('scan_message', "Ticket Already Scanned at {$scannedAt}");
        }

        // Logic Check 3: Check if Scanner is authorized for this event
        if (auth()->user()->organizer_id && $ticket->ticketCategory->event->organizer_id !== auth()->user()->organizer_id) {
            return redirect()->back()
                ->withErrors(['scan_result' => 'UNAUTHORIZED'])
                ->with('scan_message', 'Invalid Ticket / Not Authorized for this Event');
        }

        // Logic Check 4: Valid ticket - mark as scanned
        $eventName = $ticket->ticketCategory->event->title;
        $categoryName = $ticket->ticketCategory->name;
        $customerName = $ticket->order->user->name;

        $ticket->update([
            'is_scanned' => true,
            'scanned_at' => now(),
        ]);

        return redirect()->back()
            ->with('scan_success', 'VALID_TICKET')
            ->with('scan_message', "VALID: {$eventName} - {$categoryName}")
            ->with('scan_details', [
                'event_name' => $eventName,
                'category_name' => $categoryName,
                'customer_name' => $customerName,
            ]);
    }
}
