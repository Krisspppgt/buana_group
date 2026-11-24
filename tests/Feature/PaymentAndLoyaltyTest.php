<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentAndLoyaltyTest extends TestCase
{
    use RefreshDatabase;

    private User $civitas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->civitas = User::factory()->create([
            'name' => 'Ahmad Mahasiswa',
            'role' => Role::Civitas,
            'campus_id' => '24101001',
            'phone' => '085512345678',
            'loyalty_points' => 0,
        ]);
    }

    public function test_user_can_view_payment_gateway_checkout_page(): void
    {
        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->civitas->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Processing,
            'subtotal' => 150000,
            'ship_fee' => 0,
            'total' => 150000,
            'ship_name' => 'Ahmad',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Fakultas Teknik UNWAHAS',
        ]);

        $response = $this->actingAs($this->civitas)->get(route('payment.checkout', $order->code));

        $response->assertStatus(200);
        $response->assertSee('Payment Gateway Terpadu');
        $response->assertSee('QRIS Terpadu Buana Group');
        $response->assertSee('Invoice Tempo Institusi / B2B (TOP 30 Hari)');
        $response->assertDontSee('Paylater Mahasiswa / Pegawai');
        $response->assertSee('Buana Loyalty Points');
    }

    public function test_paylater_is_deferred_and_tempo_requires_admin_approval_with_limit(): void
    {
        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->civitas->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Pending,
            'subtotal' => 500000,
            'ship_fee' => 0,
            'total' => 500000,
            'ship_name' => 'BEM Fakultas',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Auditorium UNWAHAS',
        ]);

        // 1. Paylater ditunda dari pilot (tahap lanjutan)
        $this->actingAs($this->civitas)
            ->post(route('payment.process', $order->code), ['method' => 'paylater'])
            ->assertSessionHasErrors('method');

        // 2. Status civitas tidak otomatis memberi fasilitas kredit / tempo
        $this->actingAs($this->civitas)
            ->post(route('payment.process', $order->code), ['method' => 'tempo_b2b'])
            ->assertSessionHasErrors('method');

        // 3. Setelah disetujui admin dengan limit Rp 1.000.000, Tempo B2B dapat digunakan
        $this->civitas->update([
            'tempo_approved' => true,
            'tempo_limit' => 1000000,
        ]);

        $response = $this->actingAs($this->civitas)->post(route('payment.process', $order->code), [
            'method' => 'tempo_b2b',
        ]);

        $response->assertRedirect(route('food.show', $order->code));

        $payment = Payment::where('order_id', $order->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals('tempo_b2b', $payment->method);
        $this->assertEquals(500000, $payment->amount);
        $this->assertEquals(PaymentStatus::Pending, $payment->status);

        $order->refresh();
        $this->assertEquals(OrderStatus::Processing, $order->status);
        $this->assertEquals(0, $order->points_earned); // Poin masuk setelah tagihan tempo dilunasi
        $this->assertEquals(500000, $this->civitas->fresh()->remainingTempoLimit());

        // 4. Pesanan yang sama tidak bisa diajukan Tempo dua kali
        $this->actingAs($this->civitas)
            ->post(route('payment.process', $order->code), ['method' => 'tempo_b2b'])
            ->assertSessionHasErrors('method');

        // 5. Pesanan melebihi sisa limit tempo diblokir
        $secondOrder = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->civitas->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Pending,
            'subtotal' => 600000,
            'ship_fee' => 0,
            'total' => 600000,
            'ship_name' => 'BEM Fakultas',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Auditorium UNWAHAS',
        ]);

        $this->actingAs($this->civitas)
            ->post(route('payment.process', $secondOrder->code), ['method' => 'tempo_b2b'])
            ->assertSessionHasErrors('method');
    }

    public function test_user_can_redeem_loyalty_points_and_complete_midtrans_qris_via_webhook(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY');
        config()->set('services.midtrans.client_key', 'SB-Mid-client-TESTKEY');
        config()->set('services.midtrans.is_production', false);

        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'snap-token-12345',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/snap-token-12345',
            ], 201),
        ]);

        $this->civitas->update(['loyalty_points' => 10000]);

        $waterOrder = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->civitas->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Processing,
            'subtotal' => 50000,
            'ship_fee' => 0,
            'total' => 50000,
            'ship_name' => 'Ahmad',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Asrama Kampus',
        ]);

        $response = $this->actingAs($this->civitas)->post(route('payment.process', $waterOrder->code), [
            'method' => 'qris',
            'use_points' => 1,
        ]);

        $response->assertRedirect(route('payment.checkout', $waterOrder->code));
        $response->assertSessionHas('snap_token', 'snap-token-12345');

        $waterOrder->refresh();
        $this->assertEquals(10000, $waterOrder->points_redeemed);
        $this->assertEquals(40000, $waterOrder->total);
        $this->assertEquals(0, $waterOrder->points_earned); // Belum masuk sebelum webhook settlement!

        $payment = Payment::where('order_id', $waterOrder->id)->first();
        $this->assertNotNull($payment);
        $this->assertEquals(PaymentStatus::Pending, $payment->status);

        // Simulasikan Webhook Notification dari Midtrans (settlement)
        $statusCode = '200';
        $grossAmount = '40000.00';
        $signature = hash('sha512', $payment->ref.$statusCode.$grossAmount.'SB-Mid-server-TESTKEY');

        $this->postJson(route('api.payment.midtrans.webhook'), [
            'order_id' => $payment->ref,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'payment_type' => 'qris',
        ])->assertStatus(200);

        $payment->refresh();
        $waterOrder->refresh();
        $this->civitas->refresh();

        $this->assertEquals(PaymentStatus::Success, $payment->status);
        $this->assertEquals(400, $waterOrder->points_earned); // 1% dari 40.000 = 400 Poin
        $this->assertEquals(400, $this->civitas->loyalty_points);

        $loyaltyPage = $this->actingAs($this->civitas)->get(route('loyalty.index'));
        $loyaltyPage->assertStatus(200);
        $loyaltyPage->assertSee('Satu Poin Terintegrasi untuk 5 Unit Usaha');
        $loyaltyPage->assertSee('400');
    }

    public function test_midtrans_webhook_rejects_invalid_signature(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY');

        $this->postJson(route('api.payment.midtrans.webhook'), [
            'order_id' => 'MID-INVALID-123',
            'status_code' => '200',
            'gross_amount' => '10000.00',
            'signature_key' => 'invalid-signature',
            'transaction_status' => 'settlement',
        ])->assertStatus(403);
    }

    public function test_snap_confirm_sandbox_fallback_marks_paid_and_cek_status_redirects_to_invoice(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY');
        config()->set('services.midtrans.client_key', 'SB-Mid-client-TESTKEY');
        config()->set('services.midtrans.is_production', false);

        Http::fake([
            'https://api.sandbox.midtrans.com/v2/*' => Http::response([
                'status_code' => '404',
                'status_message' => "Transaction doesn't exist.",
            ], 404),
        ]);

        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->civitas->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Pending,
            'subtotal' => 30000,
            'ship_fee' => 0,
            'total' => 30000,
            'ship_name' => 'Ahmad',
            'ship_phone' => '085512345678',
            'ship_addr' => 'Kampus UNWAHAS',
        ]);

        $payment = Payment::create([
            'order_id' => $order->id,
            'ref' => 'MID-TEST99-'.$order->code,
            'method' => 'qris',
            'amount' => 30000,
            'status' => PaymentStatus::Pending,
        ]);

        $this->actingAs($this->civitas)
            ->postJson(route('payment.snap-confirm', $order->code), [
                'order_id' => $payment->ref,
                'transaction_status' => 'settlement',
                'payment_type' => 'qris',
                'gross_amount' => '30000.00',
            ])
            ->assertStatus(200)
            ->assertJson(['status' => 'paid_sandbox_fallback']);

        $this->assertEquals(PaymentStatus::Success, $payment->fresh()->status);
        $this->assertEquals(OrderStatus::Processing, $order->fresh()->status);

        // Cek status pada halaman checkout otomatis mengarahkan ke halaman Invoice
        $this->actingAs($this->civitas)
            ->get(route('payment.checkout', ['code' => $order->code, 'cek_status' => 1]))
            ->assertRedirect(route('payment.invoice', $order->code));

        // Halaman Invoice dengan ?cek_status=1 juga harus merender View 200 dengan status LUNAS
        $this->actingAs($this->civitas)
            ->get(route('payment.invoice', ['code' => $order->code, 'cek_status' => 1]))
            ->assertStatus(200)
            ->assertSee('LUNAS');
    }
}
