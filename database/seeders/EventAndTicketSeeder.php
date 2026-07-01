<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventAndTicketSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch the Event Organizer user
        $organizer = User::where('email', 'organizer@ticketing.com')->firstOrFail();

        $eventsData = [
            [
                'title'       => 'Tech Summit 2026',
                'description' => 'Annual technology summit featuring the latest innovations and trends.',
                'location'    => 'Jakarta Convention Center, Jakarta',
                'start_time'  => '2026-07-10 09:00:00',
                'end_time'    => '2026-07-10 17:00:00',
                'status'      => 'active',
            ],
            [
                'title'       => 'Music Festival Night',
                'description' => 'A spectacular night of live music performances across multiple genres.',
                'location'    => 'Gelora Bung Karno, Jakarta',
                'start_time'  => '2026-08-20 18:00:00',
                'end_time'    => '2026-08-20 23:59:00',
                'status'      => 'active',
            ],
            [
                'title'       => 'Startup Expo 2026',
                'description' => 'Connect with innovative startups and investors shaping the future.',
                'location'    => 'Bali Nusa Dua Convention Center, Bali',
                'start_time'  => '2026-09-15 08:00:00',
                'end_time'    => '2026-09-16 17:00:00',
                'status'      => 'pending',
            ],
            [
                'title'       => 'Food Carnival 2026',
                'description' => 'A weekend of amazing food, drinks, and entertainment.',
                'location'    => 'Senayan Park, Jakarta',
                'start_time'  => '2026-10-05 10:00:00',
                'end_time'    => '2026-10-06 22:00:00',
                'status'      => 'active',
            ],
            [
                'title'       => 'Indonesian E-Sports Championship',
                'description' => 'The biggest e-sports tournament in Indonesia featuring top teams.',
                'location'    => 'ICE BSD City, Tangerang',
                'start_time'  => '2026-11-12 09:00:00',
                'end_time'    => '2026-11-15 21:00:00',
                'status'      => 'active',
            ],
            [
                'title'       => 'Art & Design Exhibition',
                'description' => 'Showcasing contemporary art and design from local and international artists.',
                'location'    => 'National Gallery of Indonesia, Jakarta',
                'start_time'  => '2026-12-01 10:00:00',
                'end_time'    => '2026-12-10 18:00:00',
                'status'      => 'pending',
            ],
            [
                'title'       => 'Jakarta Fashion Week 2027',
                'description' => 'The ultimate fashion event presenting collections from top local and international designers.',
                'location'    => 'Pondok Indah Mall 3, Jakarta',
                'start_time'  => '2027-01-20 10:00:00',
                'end_time'    => '2027-01-25 21:00:00',
                'status'      => 'pending',
            ],
            [
                'title'       => 'National Book Fair',
                'description' => 'A gathering for book lovers with thousands of titles, author meet-and-greets, and workshops.',
                'location'    => 'Istora Senayan, Jakarta',
                'start_time'  => '2026-08-01 09:00:00',
                'end_time'    => '2026-08-05 20:00:00',
                'status'      => 'active',
            ],
            [
                'title'       => 'FinTech Summit Indonesia',
                'description' => 'Discussing the future of finance and technology in Southeast Asia.',
                'location'    => 'Ritz-Carlton, Pacific Place, Jakarta',
                'start_time'  => '2026-11-20 09:00:00',
                'end_time'    => '2026-11-21 18:00:00',
                'status'      => 'active',
            ],
        ];

        foreach ($eventsData as $eventData) {
            $event = Event::firstOrCreate(
                ['title' => $eventData['title'], 'organizer_id' => $organizer->id],
                array_merge($eventData, ['organizer_id' => $organizer->id])
            );

            // Create VIP category
            TicketCategory::firstOrCreate(
                ['event_id' => $event->id, 'name' => 'VIP'],
                [
                    'event_id' => $event->id,
                    'name'     => 'VIP',
                    'price'    => 500000.00,
                    'quota'    => 100,
                ]
            );

            // Create Regular category
            TicketCategory::firstOrCreate(
                ['event_id' => $event->id, 'name' => 'Regular'],
                [
                    'event_id' => $event->id,
                    'name'     => 'Regular',
                    'price'    => 150000.00,
                    'quota'    => 500,
                ]
            );
        }

        $this->command->info('Events and ticket categories seeded successfully.');
        $this->command->table(
            ['Event', 'Status', 'VIP Price', 'Regular Price'],
            Event::with('ticketCategories')
                ->where('organizer_id', $organizer->id)
                ->get()
                ->map(fn ($e) => [
                    $e->title,
                    $e->status,
                    'Rp ' . number_format($e->ticketCategories->where('name', 'VIP')->first()?->price ?? 0, 0, ',', '.'),
                    'Rp ' . number_format($e->ticketCategories->where('name', 'Regular')->first()?->price ?? 0, 0, ',', '.'),
                ])
                ->toArray()
        );
    }
}
