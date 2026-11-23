<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Models\Discount;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscountFeatureTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create([
            'name' => 'Budi Santoso',
            'role' => Role::Civitas,
            'phone' => '085512340001',
            'loyalty_points' => 50000,
            'tempo_approved' => true,
            'tempo_limit' => 2000000,
        ]);

        $this->admin = User::factory()->create([
            'name' => 'Admin Buana',
            'role' => Role::SuperAdmin,
            'phone' => '081112340002',
        ]);
    }

    private function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'code' => Order::newCode(),
            'user_id' => $this->customer->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Pending,
            'subtotal' => 200000,
            'ship_fee' => 10000,
            'total' => 210000,
            'ship_name' => 'Budi Santoso',
            'ship_phone' => '085512340001',
            'ship_addr' => 'Kampus UNWAHAS',
        ], $overrides));
    }

    public function test_admin_can_access_discount_resource_page(): void
    {
        Discount::factory()->create([
            'name' => 'Promo Semester Baru',
            'code' => 'BUANA20',
            'type' => DiscountType::Percentage,
            'value' => 20,
            'max_discount' => 30000,
        ]);

        $response = $this->actingAs($this->admin)->get('/admin/discounts');

        $response->assertStatus(200);
        $response->assertSee('BUANA20');
        $response->assertSee('Promo Semester Baru');
    }

    public function test_user_can_apply_and_remove_percentage_discount_with_max_cap(): void
    {
        $discount = Discount::factory()->create([
            'code' => 'hemat20',
            'type' => DiscountType::Percentage,
            'value' => 20,
            'max_discount' => 25000,
            'min_subtotal' => 100000,
            'quota' => 5,
            'used_count' => 0,
        ]);

        $order = $this->createOrder(['subtotal' => 200000, 'ship_fee' => 10000, 'total' => 210000]);

        // 20% dari 200.000 = 40.000, tapi max_discount = 25.000
        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), [
                'discount_code' => '  hemat20 ',
            ])
            ->assertRedirect(route('payment.checkout', $order->code))
            ->assertSessionHas('success');

        $order->refresh();
        $discount->refresh();

        $this->assertEquals($discount->id, $order->discount_id);
        $this->assertEquals('HEMAT20', $order->discount_code);
        $this->assertEquals(25000, $order->discount_amount);
        $this->assertEquals(185000, $order->total); // (200.000 - 25.000) + 10.000
        $this->assertEquals(1, $discount->used_count);

        // Terlihat di halaman checkout & invoice
        $this->actingAs($this->customer)
            ->get(route('payment.checkout', $order->code))
            ->assertOk()
            ->assertSee('HEMAT20')
            ->assertSee('-Rp 25.000');

        $this->actingAs($this->customer)
            ->get(route('payment.invoice', $order->code))
            ->assertOk()
            ->assertSee('Potongan Voucher (HEMAT20)')
            ->assertSee('-Rp 25.000');

        // Hapus voucher mengembalikan total dan kuota
        $this->actingAs($this->customer)
            ->delete(route('payment.discount.remove', $order->code))
            ->assertRedirect(route('payment.checkout', $order->code));

        $order->refresh();
        $discount->refresh();

        $this->assertNull($order->discount_id);
        $this->assertNull($order->discount_code);
        $this->assertEquals(0, $order->discount_amount);
        $this->assertEquals(210000, $order->total);
        $this->assertEquals(0, $discount->used_count);
    }

    public function test_swapping_discount_restores_previous_quota(): void
    {
        $first = Discount::factory()->fixed(15000)->create([
            'code' => 'FIRST15',
            'quota' => 10,
            'used_count' => 0,
        ]);

        $second = Discount::factory()->fixed(30000)->create([
            'code' => 'SECOND30',
            'quota' => 10,
            'used_count' => 0,
        ]);

        $order = $this->createOrder(['subtotal' => 100000, 'ship_fee' => 0, 'total' => 100000]);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), ['discount_code' => 'FIRST15'])
            ->assertSessionHasNoErrors();

        $this->assertEquals(1, $first->fresh()->used_count);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), ['discount_code' => 'SECOND30'])
            ->assertSessionHasNoErrors();

        $order->refresh();
        $this->assertEquals(0, $first->fresh()->used_count);
        $this->assertEquals(1, $second->fresh()->used_count);
        $this->assertEquals('SECOND30', $order->discount_code);
        $this->assertEquals(30000, $order->discount_amount);
        $this->assertEquals(70000, $order->total);
    }

    public function test_discount_validation_blocks_ineligible_vouchers(): void
    {
        $order = $this->createOrder(['unit' => Unit::Air, 'subtotal' => 50000, 'ship_fee' => 0, 'total' => 50000]);

        Discount::factory()->inactive()->create(['code' => 'NONAKTIF']);
        Discount::factory()->create(['code' => 'EXPIRED', 'expires_at' => now()->subHour()]);
        Discount::factory()->create(['code' => 'FUTURE', 'starts_at' => now()->addDay()]);
        Discount::factory()->create(['code' => 'FOODONLY', 'unit' => Unit::Food]);
        Discount::factory()->create(['code' => 'MIN100K', 'min_subtotal' => 100000]);
        Discount::factory()->create(['code' => 'HABIS', 'quota' => 2, 'used_count' => 2]);

        foreach (['TIDAKADA', 'NONAKTIF', 'EXPIRED', 'FUTURE', 'FOODONLY', 'MIN100K', 'HABIS'] as $code) {
            $this->actingAs($this->customer)
                ->post(route('payment.discount.apply', $order->code), ['discount_code' => $code])
                ->assertSessionHasErrors('discount_code');
        }

        $order->refresh();
        $this->assertNull($order->discount_id);
        $this->assertEquals(50000, $order->total);
    }

    public function test_discount_respects_max_uses_per_user(): void
    {
        $discount = Discount::factory()->fixed(10000)->create([
            'code' => 'ONCEONLY',
            'max_uses_per_user' => 1,
        ]);

        $firstOrder = $this->createOrder(['subtotal' => 100000, 'ship_fee' => 0, 'total' => 100000]);
        $secondOrder = $this->createOrder(['subtotal' => 100000, 'ship_fee' => 0, 'total' => 100000]);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $firstOrder->code), ['discount_code' => 'ONCEONLY'])
            ->assertSessionHasNoErrors();

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $secondOrder->code), ['discount_code' => 'ONCEONLY'])
            ->assertSessionHasErrors('discount_code');

        $this->assertEquals(1, $discount->fresh()->used_count);
    }

    public function test_discount_stacks_with_loyalty_points_on_checkout(): void
    {
        Discount::factory()->fixed(40000)->create([
            'code' => 'BUANA40K',
        ]);

        // Subtotal 200.000, ongkir 10.000. Diskon 40.000 -> net subtotal 160.000.
        // Poin user 50.000 (maks 50% dari 160.000 = 80.000) -> tukar 50.000.
        // Total akhir = 200.000 - 40.000 - 50.000 + 10.000 = 120.000.
        $order = $this->createOrder(['subtotal' => 200000, 'ship_fee' => 10000, 'total' => 210000]);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), ['discount_code' => 'BUANA40K'])
            ->assertSessionHasNoErrors();

        $this->actingAs($this->customer)
            ->post(route('payment.process', $order->code), [
                'method' => 'tempo_b2b',
                'use_points' => 1,
            ])
            ->assertRedirect(route('food.show', $order->code));

        $order->refresh();
        $payment = Payment::where('order_id', $order->id)->first();

        $this->assertEquals(40000, $order->discount_amount);
        $this->assertEquals(50000, $order->points_redeemed);
        $this->assertEquals(120000, $order->total);
        $this->assertNotNull($payment);
        $this->assertEquals(120000, $payment->amount);
    }

    public function test_cancelling_order_releases_discount_quota(): void
    {
        $discount = Discount::factory()->fixed(10000)->create([
            'code' => 'CANCELTEST',
            'quota' => 5,
            'used_count' => 0,
        ]);

        $order = $this->createOrder(['subtotal' => 100000, 'ship_fee' => 0, 'total' => 100000]);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), ['discount_code' => 'CANCELTEST'])
            ->assertSessionHasNoErrors();

        $this->assertEquals(1, $discount->fresh()->used_count);

        $order->refresh();
        $order->update(['status' => OrderStatus::Cancelled]);

        $this->assertEquals(0, $discount->fresh()->used_count);
    }

    public function test_full_discount_settles_order_immediately_without_gateway(): void
    {
        Discount::factory()->create([
            'code' => 'GRATIS100',
            'type' => DiscountType::Percentage,
            'value' => 100,
            'max_discount' => null,
        ]);

        $order = $this->createOrder(['subtotal' => 50000, 'ship_fee' => 0, 'total' => 50000]);

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $order->code), ['discount_code' => 'GRATIS100'])
            ->assertSessionHasNoErrors();

        $this->assertEquals(0, $order->fresh()->total);

        $this->actingAs($this->customer)
            ->post(route('payment.process', $order->code), ['method' => 'qris'])
            ->assertRedirect(route('payment.invoice', $order->code));

        $order->refresh();
        $payment = Payment::where('order_id', $order->id)->first();

        $this->assertEquals(OrderStatus::Paid, $order->status);
        $this->assertNotNull($payment);
        $this->assertEquals(PaymentStatus::Success, $payment->status);
        $this->assertEquals(0, $payment->amount);
    }

    public function test_internal_only_discount_is_restricted_to_internal_members(): void
    {
        Discount::factory()->fixed(20000)->internalOnly()->create([
            'name' => 'Promo Khusus Civitas',
            'code' => 'INTERNAL20K',
        ]);

        // 1. Pelanggan Umum tidak melihat voucher & ditolak jika ketik manual
        $publicUser = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '085512349901',
        ]);
        $publicOrder = $this->createOrder([
            'user_id' => $publicUser->id,
            'subtotal' => 100000,
            'ship_fee' => 0,
            'total' => 100000,
        ]);

        $this->actingAs($publicUser)
            ->get(route('payment.checkout', $publicOrder->code))
            ->assertOk()
            ->assertDontSee('INTERNAL20K');

        $this->actingAs($publicUser)
            ->post(route('payment.discount.apply', $publicOrder->code), ['discount_code' => 'INTERNAL20K'])
            ->assertSessionHasErrors('discount_code');

        // 2. Koordinator / Mitra B2B juga tidak termasuk member internal
        $this->flushSession();
        $mitraUser = User::factory()->create([
            'role' => Role::Coordinator,
            'phone' => '085512349902',
        ]);
        $mitraOrder = $this->createOrder([
            'user_id' => $mitraUser->id,
            'subtotal' => 100000,
            'ship_fee' => 0,
            'total' => 100000,
        ]);

        $this->actingAs($mitraUser)
            ->get(route('payment.checkout', $mitraOrder->code))
            ->assertOk()
            ->assertDontSee('INTERNAL20K');

        $this->actingAs($mitraUser)
            ->post(route('payment.discount.apply', $mitraOrder->code), ['discount_code' => 'INTERNAL20K'])
            ->assertSessionHasErrors('discount_code');

        // 3. Civitas Akademika melihat voucher & bisa langsung menerapkannya
        $civitasOrder = $this->createOrder([
            'user_id' => $this->customer->id,
            'subtotal' => 100000,
            'ship_fee' => 0,
            'total' => 100000,
        ]);

        $this->actingAs($this->customer)
            ->get(route('payment.checkout', $civitasOrder->code))
            ->assertOk()
            ->assertSee('INTERNAL20K')
            ->assertSee('Khusus Internal');

        $this->actingAs($this->customer)
            ->post(route('payment.discount.apply', $civitasOrder->code), ['discount_code' => 'INTERNAL20K'])
            ->assertSessionHasNoErrors();

        $this->assertEquals(20000, $civitasOrder->fresh()->discount_amount);
        $this->assertEquals(80000, $civitasOrder->fresh()->total);
    }
}
