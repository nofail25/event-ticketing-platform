<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrganizerVerified extends Notification
{
    use Queueable;

    public function __construct(public bool $isVerified) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        if ($this->isVerified) {
            return [
                'type'    => 'organizer_verified',
                'message' => '🎉 Profil Organizer Anda telah diverifikasi! Anda sekarang dapat membuat dan mempublikasikan Event.',
                'url'     => route('organizer.events.create'),
            ];
        }

        return [
            'type'    => 'organizer_rejected',
            'message' => '❌ Profil Organizer Anda ditolak oleh Admin. Silakan perbarui data Anda dan ajukan kembali.',
            'url'     => route('profile.edit'),
        ];
    }
}
