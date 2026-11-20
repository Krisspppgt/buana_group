<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TourBooking;
use App\Models\TourPackage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UnifiedOrderHistoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_unified_order_history_displays_and_filters_user_orders_across_units(): void
    {
        $user = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087700112233',
        ]);

        $otherUser = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087700112244',
        ]);

        $waterOrder = Order::create([
            'code' => 'BN2611200010',
            'user_id' => $user->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::WaitingConfirmation,
            'subtotal' => 270000,
            'ship_fee' => 0,
            'total' => 270000,
            'ship_name' => 'Budi',
            'ship_phone' => '087700112233',
            'ship_addr' => 'Gedung Rektorat',
            'note' => 'Borongan Galon',
        ]);

        $foodOrder = Order::create([
            'code' => 'BN2611200011',
            'user_id' => $user->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Pending,
            'subtotal' => 250000,
            'ship_fee' => 0,
            'total' => 250000,
            'ship_name' => 'Budi',
            'ship_phone' => '087700112233',
            'ship_addr' => 'Fakultas Hukum',
            'note' => 'Snack Box Rapat',
        ]);

        $package = TourPackage::create([
            'title' => 'Paket Ziarah Wali',
            'category' => 'ziarah',
            'duration' => '2 Hari 1 Malam',
            'departure_date' => now()->addDays(30)->toDateString(),
            'price' => 1500000,
            'price_civitas' => 1350000,
            'min_dp' => 500000,
            'description' => 'Ziarah',
            'is_active' => true,
        ]);

        TourBooking::create([
            'code' => 'TR2611200001',
            'user_id' => $user->id,
            'tour_package_id' => $package->id,
            'coordinator_name' => 'Budi',
            'coordinator_phone' => '087700112233',
            'total_pax' => 2,
            'payment_scheme' => 'cicilan',
            'installment_tenor' => 3,
            'paid_amount' => 1000000,
            'total_amount' => 3000000,
            'members_data' => [['name' => 'Budi', 'id_number' => '3301']],
            'status' => 1,
        ]);

        Ticket::create([
            'code' => 'TIX2611200001',
            'user_id' => $user->id,
            'category' => 'event',
            'title' => 'Seminar Nasional UNWAHAS',
            'passenger_name' => 'Budi',
            'passenger_id' => '3301',
            'schedule_at' => now()->addDays(5),
            'seat_info' => 'Reguler',
            'qr_payload' => 'QR-TIX-1',
            'price' => 50000,
            'status' => 0,
        ]);

        Order::create([
            'code' => 'BN2611209999',
            'user_id' => $otherUser->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Pending,
            'subtotal' => 18000,
            'ship_fee' => 0,
            'total' => 18000,
            'ship_name' => 'Orang Lain',
            'ship_phone' => '087700112244',
            'ship_addr' => 'Semarang',
        ]);

        $response = $this->actingAs($user)->get(route('orders.history'));
        $response->assertStatus(200);
        $response->assertSee($waterOrder->code);
        $response->assertSee($foodOrder->code);
        $response->assertSee('TR2611200001');
        $response->assertSee('TIX2611200001');
        $response->assertSee('Cek Konfirmasi Admin');
        $response->assertSee('Bayar Sekarang');
        $response->assertDontSee('BN2611209999');

        // Filter unit=food
        $foodOnly = $this->actingAs($user)->get(route('orders.history', ['unit' => 'food']));
        $foodOnly->assertStatus(200);
        $foodOnly->assertSee($foodOrder->code);
        $foodOnly->assertDontSee($waterOrder->code);

        // Filter status=waiting
        $waitingOnly = $this->actingAs($user)->get(route('orders.history', ['status' => 'waiting']));
        $waitingOnly->assertStatus(200);
        $waitingOnly->assertSee($waterOrder->code);
        $waitingOnly->assertDontSee($foodOrder->code);
    }

    public function test_old_per_catalog_history_routes_are_removed(): void
    {
        $user = User::factory()->create(['phone' => '087700112255']);

        foreach (['/air-mineral/riwayat', '/food-services/riwayat', '/tour-travel/riwayat', '/ticketing/riwayat'] as $path) {
            $this->actingAs($user)->get($path)->assertStatus(404);
        }
    }
}
