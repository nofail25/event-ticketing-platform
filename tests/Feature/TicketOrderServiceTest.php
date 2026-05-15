<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\TicketCategory;
use App\Models\User;
use App\Notifications\OrderPaid;
use App\Services\TicketOrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TicketOrderServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_creates_one_ticket_without_decrementing_category_quota(): void
    {
        Notification::fake();

        $customer = User::factory()->create();
        $organizer = User::factory()->create();
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Test Event',
            'description' => 'A test event',
            'location' => 'Jakarta',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'active',
        ]);
        $ticketCategory = TicketCategory::create([
            'event_id' => $event->id,
            'name' => 'Regular',
            'price' => 100000,
            'quota' => 5,
        ]);

        $order = app(TicketOrderService::class)->processOrder(
            $ticketCategory->id,
            1,
            $customer->id,
            'qris',
            'qris_gopay'
        );

        $ticketCategory->refresh();

        $this->assertSame(5, $ticketCategory->quota);
        $this->assertSame(1, $order->ticketDetails()->count());
        $this->assertSame(1, $ticketCategory->ticketDetails()->count());
        $this->assertSame(4, $ticketCategory->quota - $ticketCategory->ticketDetails()->count());
        Notification::assertSentTo($customer, OrderPaid::class);
    }
}
