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
                ->with('scan_message', 'Tiket Tidak Valid / Tidak Ditemukan');
        }

        // Logic Check 2: Ticket already scanned
        if ($ticket->is_scanned) {
            $scannedAt = $ticket->scanned_at->format('M d, Y \a\t h:i A');
            return redirect()->back()
                ->with('scan_warning', 'ALREADY_SCANNED')
                ->with('scan_message', "Tiket Sudah Dipindai pada {$scannedAt}");
        }

        // Logic Check 3: Check if Scanner or Organizer is authorized for this event
        // Scanners have organizer_id set. Organizers use their own id.
        $authorizedOrganizerId = auth()->user()->organizer_id ?? auth()->id();
        
        if ($ticket->ticketCategory->event->organizer_id !== $authorizedOrganizerId) {
            return redirect()->back()
                ->withErrors(['scan_result' => 'UNAUTHORIZED'])
                ->with('scan_message', 'Tiket Tidak Valid / Tidak Diizinkan untuk Event ini');
        }

        // Logic Check 4: Valid ticket - mark as scanned (with lock to prevent double-scan race condition)
        $eventName    = $ticket->ticketCategory->event->title;
        $categoryName = $ticket->ticketCategory->name;
        $customerName = $ticket->order->user->name;

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($ticket) {
                $locked = \App\Models\TicketDetail::lockForUpdate()->findOrFail($ticket->id);
                if ($locked->is_scanned) {
                    // Already scanned by a concurrent request — abort
                    throw new \Exception('ALREADY_SCANNED');
                }
                $locked->update([
                    'is_scanned' => true,
                    'scanned_at' => now(),
                ]);
            });
        } catch (\Exception $e) {
            if ($e->getMessage() === 'ALREADY_SCANNED') {
                return redirect()->back()
                    ->with('scan_warning', 'ALREADY_SCANNED')
                    ->with('scan_message', "Tiket Sudah Dipindai (Double Scan Detected)");
            }
            throw $e;
        }

        return redirect()->back()
            ->with('scan_success', 'VALID_TICKET')
            ->with('scan_message', "VALID: {$eventName} - {$categoryName}")
            ->with('scan_details', [
                'event_name'    => $eventName,
                'category_name' => $categoryName,
                'customer_name' => $customerName,
            ]);
    }
}
