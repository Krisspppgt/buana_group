<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkOrderConfirmationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $waterProduct;

    private Product $foodProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087755667788',
        ]);

        $this->waterProduct = Product::create([
            'unit' => Unit::Air,
            'name' => 'Galon Air Mineral Unwahas 19L',
            'info' => 'Galon higienis',
            'price' => 18000,
            'price_civitas' => 15000,
            'price_bulk' => 13500,
            'bulk_min' => 10,
            'stock' => 200,
            'uom' => 'galon',
            'is_active' => true,
        ]);

        $this->foodProduct = Product::create([
            'unit' => Unit::Food,
            'name' => 'Nasi Kotak Ayam Bakar',
            'info' => 'Paket acara',
            'price' => 25000,
            'price_civitas' => 22000,
            'price_bulk' => 20000,
            'bulk_min' => 30,
            'stock' => 500,
            'uom' => 'kotak',
            'is_active' => true,
        ]);
    }

    public function test_bulk_water_order_requires_admin_confirmation_and_blocks_payment_until_confirmed(): void
    {
        $response = $this->actingAs($this->user)->post(route('water.store'), [
            'order_type' => 'bulk',
            'products' => [
                $this->waterProduct->id => 15,
            ],
            'ship_name' => 'Panitia Seminar',
            'ship_phone' => '087755667788',
            'ship_addr' => 'Auditorium UNWAHAS',
            'event_date' => now()->addDays(3)->format('Y-m-d'),
        ]);

        $order = Order::where('user_id', $this->user->id)->firstOrFail();
        $this->assertEquals(OrderStatus::WaitingConfirmation, $order->status);
        $this->assertEquals(15 * 13500, $order->total);

        $response->assertRedirect(route('order.waiting-confirmation', $order->code));

        // Waiting confirmation page renders cleanly with locked payment state
        $waitingPage = $this->actingAs($this->user)->get(route('order.waiting-confirmation', $order->code));
        $waitingPage->assertStatus(200);
        $waitingPage->assertSee('Menunggu Pemeriksaan &amp; Konfirmasi Admin', false);
        $waitingPage->assertSee('Pembayaran Terkunci (Menunggu Konfirmasi Admin)');

        // Direct access to checkout or payment submission is blocked while WaitingConfirmation
        $this->actingAs($this->user)
            ->get(route('payment.checkout', $order->code))
            ->assertRedirect(route('order.waiting-confirmation', $order->code));

        $this->actingAs($this->user)
            ->post(route('payment.process', $order->code), ['method' => 'qris'])
            ->assertRedirect(route('order.waiting-confirmation', $order->code));

        // Admin confirms order -> status becomes Pending & payment unlocks
        $order->update([
            'status' => OrderStatus::Pending,
            'confirmed_at' => now(),
            'admin_note' => 'Stok tersedia, siap dikirim sesuai jadwal.',
        ]);

        $readyPage = $this->actingAs($this->user)->get(route('order.waiting-confirmation', $order->code));
        $readyPage->assertStatus(200);
        $readyPage->assertSee('Pesanan Dikonfirmasi Admin');
        $readyPage->assertSee('Lanjut ke Pembayaran');

        $this->actingAs($this->user)
            ->get(route('payment.checkout', $order->code))
            ->assertStatus(200);
    }

    public function test_bulk_food_order_redirects_to_waiting_confirmation_page(): void
    {
        $response = $this->actingAs($this->user)->post(route('food.store'), [
            'products' => [
                $this->foodProduct->id => 40,
            ],
            'event_name' => 'Rapat Senat Fakultas',
            'send_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'diet_pref' => 'Reguler',
            'ship_name' => 'Sekretariat Fakultas',
            'ship_phone' => '087755667788',
            'ship_addr' => 'Gedung Dekanat Lt 1',
        ]);

        $order = Order::where('user_id', $this->user->id)->firstOrFail();
        $this->assertEquals(OrderStatus::WaitingConfirmation, $order->status);
        $this->assertEquals(40 * 20000, $order->total);

        $response->assertRedirect(route('order.waiting-confirmation', $order->code));
    }

    public function test_user_can_cancel_order_while_waiting_confirmation(): void
    {
        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->user->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::WaitingConfirmation,
            'subtotal' => 270000,
            'ship_fee' => 0,
            'total' => 270000,
            'ship_name' => 'Panitia',
            'ship_phone' => '087755667788',
            'ship_addr' => 'Kampus UNWAHAS',
        ]);

        $this->actingAs($this->user)
            ->post(route('order.cancel-waiting', $order->code))
            ->assertRedirect();

        $this->assertEquals(OrderStatus::Cancelled, $order->fresh()->status);

        // Cancelled order cannot access checkout
        $this->actingAs($this->user)
            ->get(route('payment.checkout', $order->code))
            ->assertRedirect(route('order.waiting-confirmation', $order->code));
    }
}
