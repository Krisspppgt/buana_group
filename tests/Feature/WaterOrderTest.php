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

class WaterOrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087712345678',
        ]);

        $this->product = Product::create([
            'unit' => Unit::Air,
            'name' => 'Galon Air Mineral Unwahas 19L',
            'info' => 'Galon higienis',
            'price' => 18000,
            'price_civitas' => 15000,
            'price_bulk' => 13500,
            'bulk_min' => 10,
            'stock' => 100,
            'uom' => 'galon',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_water_catalog(): void
    {
        $response = $this->actingAs($this->user)->get(route('water.index'));

        $response->assertStatus(200);
        $response->assertSee('Air Mineral Unwahas');
        $response->assertSee('Galon Air Mineral Unwahas 19L');
        $response->assertSee('18.000');
    }

    public function test_user_can_place_regular_water_order(): void
    {
        $response = $this->actingAs($this->user)->post(route('water.store'), [
            'order_type' => 'regular',
            'products' => [
                $this->product->id => 2,
            ],
            'ship_name' => 'Budi Santoso',
            'ship_phone' => '087712345678',
            'ship_addr' => 'Gedung Rektorat Lt 2',
            'note' => 'Titip di meja resepsionis',
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(36000, $order->total); // 2 * 18000
        $this->assertCount(1, $order->items);

        $this->assertEquals(OrderStatus::Pending, $order->status);
        $this->assertEquals(98, $this->product->fresh()->stock);
        $response->assertRedirect(route('payment.checkout', $order->code));
    }

    public function test_water_order_fails_and_rolls_back_when_stock_is_insufficient(): void
    {
        $secondProduct = Product::create([
            'unit' => Unit::Air,
            'name' => 'Air Mineral Gelas 220ml',
            'info' => 'Dus isi 48 cup',
            'price' => 25000,
            'stock' => 3,
            'uom' => 'dus',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)->post(route('water.store'), [
            'order_type' => 'regular',
            'products' => [
                $this->product->id => 5,
                $secondProduct->id => 4,
            ],
            'ship_name' => 'Budi Santoso',
            'ship_phone' => '087712345678',
            'ship_addr' => 'Gedung Rektorat Lt 2',
        ]);

        $response->assertSessionHasErrors('products');
        $this->assertSame(0, Order::count());
        $this->assertEquals(100, $this->product->fresh()->stock);
        $this->assertEquals(3, $secondProduct->fresh()->stock);
    }

    public function test_civitas_user_gets_civitas_pricing(): void
    {
        $civitasUser = User::factory()->create([
            'role' => Role::Civitas,
            'campus_id' => '24101001',
            'phone' => '085512345678',
        ]);

        $this->actingAs($civitasUser)->post(route('water.store'), [
            'order_type' => 'regular',
            'products' => [
                $this->product->id => 2,
            ],
            'ship_name' => 'Mahasiswa Unwahas',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Fakultas Teknik',
        ]);

        $order = Order::where('user_id', $civitasUser->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(30000, $order->total); // 2 * 15000 (civitas price!)
    }

    public function test_user_can_view_live_logistics_tracking(): void
    {
        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->user->id,
            'unit' => Unit::Air,
            'subtotal' => 18000,
            'ship_fee' => 0,
            'total' => 18000,
            'ship_name' => 'Budi',
            'ship_phone' => '087712345678',
            'ship_addr' => 'Gedung A',
        ]);

        $response = $this->actingAs($this->user)->get(route('water.show', $order->code));

        $response->assertStatus(200);
        $response->assertSee('Detail Pesanan Air Mineral');
        $response->assertSee('Pesanan Diajukan');
        $response->assertDontSee('LIVE GPS TRACKING ARMADA');
        $response->assertDontSee('Pak Bambang Susanto');
    }

    public function test_courier_and_customer_can_update_water_delivery_status(): void
    {
        $courier = User::factory()->create([
            'role' => Role::Courier,
            'phone' => '084412345678',
        ]);

        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->user->id,
            'unit' => Unit::Air,
            'subtotal' => 18000,
            'ship_fee' => 0,
            'total' => 18000,
            'ship_name' => 'Budi',
            'ship_phone' => '087712345678',
            'ship_addr' => 'Gedung A',
        ]);

        $this->actingAs($courier)
            ->post(route('water.status.update', $order->code), ['status' => 3])
            ->assertRedirect();

        $this->assertEquals(OrderStatus::Shipped, $order->fresh()->status);

        $this->actingAs($this->user)
            ->post(route('water.status.update', $order->code), ['status' => 4])
            ->assertRedirect();

        $this->assertEquals(OrderStatus::Done, $order->fresh()->status);
    }
}
