<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPaid extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $ticketCount = $this->order->ticketDetails->count();
        $event = $this->order->ticketDetails->first()?->ticketCategory->event;

        return (new MailMessage)
            ->subject('Tiket Anda Berhasil Dibayar - Invoice #' . $this->order->invoice_number)
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Pembayaran tiket Anda telah berhasil diproses.')
            ->line('Nomor Invoice: **' . $this->order->invoice_number . '**')
            ->line('Jumlah Tiket: ' . $ticketCount . ' tiket')
            ->line('Total Pembayaran: Rp ' . number_format($this->order->total_amount, 0, ',', '.'))
            ->when($event, function ($mail) use ($event) {
                return $mail->line('Acara: ' . $event->title);
            })
            ->line('Status Pembayaran: **Lunas**')
            ->action('Lihat E-Tiket Anda', route('customer.dashboard'))
            ->line('E-tiket Anda sudah siap untuk digunakan. Silakan kunjungi dashboard untuk melihat detail tiket dan barcode.')
            ->line('Terimakasih telah membeli tiket bersama kami!')
            ->salutation('Salam hangat,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $ticketCount = $this->order->ticketDetails->count();
        $event = $this->order->ticketDetails->first()?->ticketCategory->event;

        return [
            'order_id' => $this->order->id,
            'invoice_number' => $this->order->invoice_number,
            'ticket_count' => $ticketCount,
            'total_amount' => $this->order->total_amount,
            'event_title' => $event?->title,
            'message' => "Pembayaran tiket berhasil! {$ticketCount} tiket untuk {$event?->title} sudah siap digunakan.",
            'type' => 'order_paid',
        ];
    }
}
