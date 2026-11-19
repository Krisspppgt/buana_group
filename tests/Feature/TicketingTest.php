<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\TourAndTicketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketingTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private User $civitas;

    private User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TourAndTicketSeeder::class);

        $this->customer = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087711223344',
        ]);

        $this->civitas = User::factory()->create([
            'role' => Role::Civitas,
            'phone' => '085511223344',
            'campus_id' => 'NIM-202611001',
        ]);

        $this->operator = User::factory()->create([
            'role' => Role::Operator,
            'phone' => '086611223344',
        ]);
    }

    public function test_user_can_view_ticketing_index(): void
    {
        $response = $this->actingAs($this->customer)->get(route('ticketing.index'));

        $response->assertStatus(200);
        $response->assertSee('Buana Ticketing');
        $response->assertSee('Agregator Tiket Transportasi');
        $response->assertSee('KA Argo Sindoro');
        $response->assertSee('Seminar Nasional');
    }

    public function test_user_can_order_transport_ticket_and_get_boarding_pass(): void
    {
        $response = $this->actingAs($this->customer)->post(route('ticketing.order.transport'), [
            'title' => 'KA Argo Sindoro (Eksekutif)',
            'price' => 380000,
            'seat_info' => 'Eks 3 / 8A',
            'passenger_name' => 'Budi Santoso',
            'passenger_id' => '3301012345678001',
            'schedule_at' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ]);

        $ticket = Ticket::where('passenger_name', 'Budi Santoso')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals('transport', $ticket->category);
        $this->assertEquals('Eks 3 / 8A', $ticket->seat_info);
        $this->assertEquals(0, $ticket->status); // Status Aktif

        $response->assertRedirect(route('ticketing.show', $ticket->code));
    }

    public function test_user_can_order_event_ticket_with_civitas_pricing(): void
    {
        $event = Event::where('title', 'like', '%Seminar%')->first();

        $response = $this->actingAs($this->civitas)->post(route('ticketing.order.event'), [
            'event_id' => $event->id,
            'passenger_name' => 'Rina Mahasiswa',
            'passenger_id' => 'NIM-202611001',
        ]);

        $ticket = Ticket::where('passenger_name', 'Rina Mahasiswa')->first();
        $this->assertNotNull($ticket);
        $this->assertEquals('event', $ticket->category);
        $this->assertEquals($event->price_civitas, $ticket->price);

        $response->assertRedirect(route('ticketing.show', $ticket->code));
    }

    public function test_operator_can_scan_and_check_in_boarding_pass(): void
    {
        $ticket = Ticket::create([
            'code' => Ticket::newCode(),
            'user_id' => $this->customer->id,
            'category' => 'transport',
            'title' => 'KA Argo Sindoro',
            'passenger_name' => 'Budi Santoso',
            'passenger_id' => '3301012345678001',
            'schedule_at' => now()->addDays(1),
            'seat_info' => 'Eks 3 / 8A',
            'qr_payload' => 'BUANA-TRN-TEST-1234',
            'price' => 380000,
            'status' => 0,
        ]);

        $response = $this->actingAs($this->operator)->post(route('ticketing.scan', $ticket->code));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $ticket->refresh();
        $this->assertEquals(1, $ticket->status); // Status Checked-In
        $this->assertNotNull($ticket->checked_in_at);
    }

    public function test_double_scan_prevents_duplicate_check_in(): void
    {
        $ticket = Ticket::create([
            'code' => Ticket::newCode(),
            'user_id' => $this->customer->id,
            'category' => 'transport',
            'title' => 'KA Argo Sindoro',
            'passenger_name' => 'Budi Santoso',
            'passenger_id' => '3301012345678001',
            'schedule_at' => now()->addDays(1),
            'seat_info' => 'Eks 3 / 8A',
            'qr_payload' => 'BUANA-TRN-TEST-1234',
            'price' => 380000,
            'status' => 1,
            'checked_in_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($this->operator)->post(route('ticketing.scan', $ticket->code));

        $response->assertRedirect();
        $response->assertSessionHas('warning');
    }

    public function test_user_can_filter_transport_routes_by_mode_and_search(): void
    {
        $response = $this->actingAs($this->customer)->get(route('ticketing.index', [
            'mode' => 'pesawat',
            'q' => 'Garuda',
        ]));

        $response->assertStatus(200);
        $response->assertSee('Garuda Indonesia GA-234');
        $response->assertDontSee('KA Argo Sindoro');
    }
}
