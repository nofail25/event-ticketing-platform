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

    public function test_order_reserves_quota_and_generates_tickets_after_payment(): void
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
            'qris_universal'
        );

        $ticketCategory->refresh();

        $this->assertSame(5, $ticketCategory->quota);
        $this->assertSame('pending', $order->payment_status);
        $this->assertSame(0, $order->ticketDetails()->count());
        $this->assertSame(4, $ticketCategory->availableQuota());
        Notification::assertNothingSent();

        app(TicketOrderService::class)->completeOrder($order);
        $order->refresh();

        $this->assertSame('paid', $order->payment_status);
        $this->assertSame(1, $order->ticketDetails()->count());
        $this->assertSame(1, $ticketCategory->ticketDetails()->count());
        $this->assertSame(4, $ticketCategory->availableQuota());
        Notification::assertSentTo($customer, OrderPaid::class);
    }

    public function test_cannot_order_tickets_for_inactive_event(): void
    {
        $customer = User::factory()->create();
        $organizer = User::factory()->create();
        $event = Event::create([
            'organizer_id' => $organizer->id,
            'title' => 'Draft Event',
            'description' => 'A draft event',
            'location' => 'Jakarta',
            'start_time' => now()->addDay(),
            'end_time' => now()->addDay()->addHours(2),
            'status' => 'pending',
        ]);
        $ticketCategory = TicketCategory::create([
            'event_id' => $event->id,
            'name' => 'Regular',
            'price' => 100000,
            'quota' => 5,
        ]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Event tidak tersedia untuk pembelian tiket.');

        app(TicketOrderService::class)->processOrder(
            $ticketCategory->id,
            1,
            $customer->id,
            'qris',
            'qris_universal'
        );
    }
}
