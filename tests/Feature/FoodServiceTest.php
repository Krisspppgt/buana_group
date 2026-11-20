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

class FoodServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Product $foodProduct;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087799887766',
        ]);

        $this->foodProduct = Product::create([
            'unit' => Unit::Food,
            'name' => 'Nasi Kotak Ayam Bakar Menoreh',
            'info' => 'Nasi kotak ayam bakar lezat',
            'price' => 25000,
            'price_civitas' => 22000,
            'price_bulk' => 20000,
            'bulk_min' => 30,
            'stock' => 500,
            'uom' => 'kotak',
            'is_active' => true,
        ]);
    }

    public function test_user_can_view_food_catalog(): void
    {
        $response = $this->actingAs($this->user)->get(route('food.index'));

        $response->assertStatus(200);
        $response->assertSee('Buana Food Services');
        $response->assertSee('Budget Calculator Acara');
        $response->assertSee('Nasi Kotak Ayam Bakar Menoreh');
    }

    public function test_user_can_order_catering_with_rundown(): void
    {
        $response = $this->actingAs($this->user)->post(route('food.store'), [
            'products' => [
                $this->foodProduct->id => 10,
            ],
            'event_name' => 'Seminar Nasional Hukum',
            'send_at' => now()->addDays(2)->format('Y-m-d\TH:i'),
            'diet_pref' => 'Reguler',
            'rundown_panitia' => '07:30',
            'rundown_peserta' => '09:30',
            'rundown_vip' => '12:00',
            'ship_name' => 'Panitia Konsumsi',
            'ship_phone' => '087799887766',
            'ship_addr' => 'Auditorium Unwahas Lt 2',
            'note' => 'Mohon sediakan sendok plastik',
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertEquals(250000, $order->total); // 10 * 25000
        $this->assertStringContainsString('Seminar Nasional Hukum', $order->note);

        $this->assertEquals(OrderStatus::Pending, $order->status);
        $response->assertRedirect(route('payment.checkout', $order->code));
    }
}
