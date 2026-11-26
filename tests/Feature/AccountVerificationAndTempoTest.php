<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Enums\VerificationStatus;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AccountVerificationAndTempoTest extends TestCase
{
    use RefreshDatabase;

    public function test_general_registration_logs_in_as_regular_customer_without_verification_queue(): void
    {
        $response = $this->post(route('register'), [
            'role_register' => 'umum',
            'name' => 'Rina Alumni',
            'email' => 'rina@example.com',
            'phone' => '081234567891',
            'password' => 'Buana2026',
            'password_confirmation' => 'Buana2026',
        ]);

        $response->assertRedirect('/');

        $user = User::where('email', 'rina@example.com')->firstOrFail();
        $this->assertEquals(Role::Customer, $user->role);
        $this->assertNull($user->requested_role);
        $this->assertEquals(VerificationStatus::None, $user->verification_status);
        $this->assertFalse($user->isCivitas());
        $this->assertFalse($user->isVerifiedMitra());
        $this->assertFalse($user->canUseTempo(10000));
    }

    public function test_civitas_registration_stays_customer_until_admin_verifies_and_does_not_auto_grant_tempo(): void
    {
        $response = $this->post(route('register'), [
            'role_register' => 'unwahas',
            'name' => 'Ahmad Dosen',
            'campus_id' => '198801012015',
            'faculty' => 'Fakultas Teknik',
            'email' => 'ahmad@unwahas.ac.id',
            'phone' => '081234567892',
            'password' => 'Buana2026',
            'password_confirmation' => 'Buana2026',
        ]);

        $response->assertRedirect('/');

        $user = User::where('email', 'ahmad@unwahas.ac.id')->firstOrFail();
        $this->assertEquals(Role::Customer, $user->role);
        $this->assertEquals(Role::Civitas, $user->requested_role);
        $this->assertEquals(VerificationStatus::Pending, $user->verification_status);
        $this->assertEquals('198801012015', $user->campus_id);
        $this->assertEquals('Fakultas Teknik', $user->faculty);
        $this->assertFalse($user->isCivitas());
        $this->assertFalse($user->canUseTempo(10000));

        // Admin approves civitas role without tempo credit
        $user->approveVerification(Role::Civitas, false, 0, 'NIM/NIP terverifikasi');
        $user->refresh();

        $this->assertEquals(Role::Civitas, $user->role);
        $this->assertEquals(VerificationStatus::Approved, $user->verification_status);
        $this->assertTrue($user->isCivitas());
        $this->assertFalse($user->canUseTempo(10000));
    }

    public function test_mitra_registration_requires_admin_verification_before_coordinator_and_tempo_access(): void
    {
        $response = $this->post(route('register'), [
            'role_register' => 'mitra',
            'company_name' => 'SMA NU 1 Semarang',
            'name' => 'Hendra Wijaya',
            'position' => 'Kabag Pengadaan',
            'email' => 'hendra@smanu1.sch.id',
            'phone' => '081234567893',
            'password' => 'Buana2026',
            'password_confirmation' => 'Buana2026',
        ]);

        $response->assertRedirect('/');

        $user = User::where('email', 'hendra@smanu1.sch.id')->firstOrFail();
        $this->assertEquals(Role::Customer, $user->role);
        $this->assertEquals(Role::Coordinator, $user->requested_role);
        $this->assertEquals(VerificationStatus::Pending, $user->verification_status);
        $this->assertEquals('SMA NU 1 Semarang', $user->company_name);
        $this->assertEquals('Kabag Pengadaan', $user->position);
        $this->assertFalse($user->isVerifiedMitra());
        $this->assertFalse($user->canUseTempo(500000));

        // Admin approves mitra with Tempo limit Rp 5.000.000
        $user->approveVerification(Role::Coordinator, true, 5000000, 'Surat pengadaan instansi valid');
        $user->refresh();

        $this->assertEquals(Role::Coordinator, $user->role);
        $this->assertEquals(VerificationStatus::Approved, $user->verification_status);
        $this->assertTrue($user->isVerifiedMitra());
        $this->assertTrue($user->canUseTempo(500000));
        $this->assertFalse($user->canUseTempo(6000000));
    }

    public function test_rejected_verification_keeps_user_as_customer_with_note(): void
    {
        $user = User::factory()->create([
            'role' => Role::Customer,
            'requested_role' => Role::Civitas,
            'verification_status' => VerificationStatus::Pending,
            'campus_id' => '999999',
        ]);

        $user->rejectVerification('NIM tidak terdaftar di arsip akademik.');
        $user->refresh();

        $this->assertEquals(Role::Customer, $user->role);
        $this->assertEquals(VerificationStatus::Rejected, $user->verification_status);
        $this->assertEquals('NIM tidak terdaftar di arsip akademik.', $user->verification_note);
        $this->assertFalse($user->isCivitas());
        $this->assertFalse($user->canUseTempo(10000));
    }

    public function test_mitra_can_view_tempo_usage_page_and_settle_tempo_invoice_via_midtrans_auto_lunas(): void
    {
        config()->set('services.midtrans.server_key', 'SB-Mid-server-TESTKEY');
        config()->set('services.midtrans.client_key', 'SB-Mid-client-TESTKEY');
        config()->set('services.midtrans.is_production', false);

        Http::fake([
            'https://app.sandbox.midtrans.com/snap/v1/transactions' => Http::response([
                'token' => 'snap-tempo-settle-001',
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/snap-tempo-settle-001',
            ], 201),
            'https://api.sandbox.midtrans.com/v2/*/status' => Http::response([
                'status_code' => '200',
                'transaction_status' => 'settlement',
                'fraud_status' => 'accept',
                'payment_type' => 'qris',
            ], 200),
        ]);

        $mitra = User::factory()->create([
            'name' => 'Koordinator BEM',
            'role' => Role::Coordinator,
            'verification_status' => VerificationStatus::Approved,
            'company_name' => 'BEM Universitas Wahid Hasyim',
            'tempo_approved' => true,
            'tempo_limit' => 5000000,
        ]);

        $product = Product::create([
            'unit' => Unit::Air,
            'name' => 'Air Botol 600ml (Dus)',
            'info' => '24 botol per dus',
            'price' => 40000,
            'price_civitas' => 36000,
            'price_bulk' => 35000,
            'bulk_min' => 10,
            'stock' => 200,
            'uom' => 'dus',
            'is_active' => true,
        ]);

        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $mitra->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Pending,
            'subtotal' => 700000,
            'ship_fee' => 0,
            'total' => 700000,
            'ship_name' => 'BEM UNWAHAS',
            'ship_phone' => '081234567890',
            'ship_addr' => 'Gedung Dekanat Lt. 2',
            'note' => 'Seminar Nasional Kampus',
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'qty' => 20,
            'price' => 35000,
        ]);

        // 1. Mitra memilih metode Tempo B2B saat checkout awal
        $this->actingAs($mitra)
            ->post(route('payment.process', $order->code), ['method' => 'tempo_b2b'])
            ->assertRedirect(route('water.show', $order->code));

        $tempoPayment = Payment::where('order_id', $order->id)
            ->where('method', 'tempo_b2b')
            ->firstOrFail();

        $this->assertEquals(PaymentStatus::Pending, $tempoPayment->status);
        $this->assertEquals(700000, $mitra->fresh()->usedTempoAmount());
        $this->assertEquals(4300000, $mitra->fresh()->remainingTempoLimit());
        $this->assertEquals(1, $mitra->fresh()->pendingTempoCount());

        // 2. Mitra membuka halaman Fasilitas & Tagihan Tempo (/akun/tempo) dari dropdown profil
        $this->actingAs($mitra)
            ->get(route('account.tempo'))
            ->assertOk()
            ->assertSeeText('Fasilitas & Tagihan Tempo B2B')
            ->assertSeeText('Rp 4.300.000')
            ->assertSeeText('Rp 700.000')
            ->assertSeeText($tempoPayment->ref)
            ->assertSeeText('Air Botol 600ml (Dus)')
            ->assertSeeText('Lunasi via Midtrans');

        // 3. Mitra melunasi tagihan Tempo tersebut menggunakan QRIS Midtrans
        $this->actingAs($mitra)
            ->post(route('payment.process', $order->code), ['method' => 'qris'])
            ->assertRedirect(route('payment.checkout', $order->code))
            ->assertSessionHas('snap_token', 'snap-tempo-settle-001');

        $midtransPayment = Payment::where('order_id', $order->id)
            ->where('ref', 'like', 'MID-%')
            ->firstOrFail();

        // 4. Konfirmasi Snap Midtrans selesai (settlement) -> otomatis melunasi Tempo & memulihkan limit
        $this->actingAs($mitra)
            ->postJson(route('payment.snap-confirm', $order->code), [
                'order_id' => $midtransPayment->ref,
                'transaction_status' => 'settlement',
                'payment_type' => 'qris',
                'fraud_status' => 'accept',
                'gross_amount' => 700000,
            ])
            ->assertOk()
            ->assertJson(['status' => 'paid']);

        $tempoPayment->refresh();
        $midtransPayment->refresh();
        $mitra->refresh();
        $order->refresh();

        $this->assertEquals(PaymentStatus::Success, $tempoPayment->status);
        $this->assertNotNull($tempoPayment->paid_at);
        $this->assertEquals(PaymentStatus::Success, $midtransPayment->status);
        $this->assertEquals(0, $mitra->usedTempoAmount());
        $this->assertEquals(5000000, $mitra->remainingTempoLimit());
        $this->assertEquals(0, $mitra->pendingTempoCount());
        $this->assertEquals(7000, $order->points_earned);
        $this->assertEquals(7000, $mitra->loyalty_points);
    }

    public function test_overdue_tempo_invoice_blocks_new_tempo_usage_until_settled(): void
    {
        $mitra = User::factory()->create([
            'role' => Role::Coordinator,
            'verification_status' => VerificationStatus::Approved,
            'tempo_approved' => true,
            'tempo_limit' => 5000000,
        ]);

        $firstOrder = Order::create([
            'code' => Order::newCode(),
            'user_id' => $mitra->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Processing,
            'subtotal' => 500000,
            'ship_fee' => 0,
            'total' => 500000,
            'ship_name' => 'Mitra Instansi',
            'ship_phone' => '081234567890',
            'ship_addr' => 'Semarang',
        ]);

        $overduePayment = Payment::create([
            'order_id' => $firstOrder->id,
            'ref' => 'INV-OVERDUE-'.$firstOrder->code,
            'method' => 'tempo_b2b',
            'amount' => 500000,
            'status' => PaymentStatus::Pending,
            'due_date' => now()->subDays(2),
        ]);

        $this->assertTrue($mitra->hasOverdueTempo());
        $this->assertFalse($mitra->canUseTempo(100000));

        $secondOrder = Order::create([
            'code' => Order::newCode(),
            'user_id' => $mitra->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Pending,
            'subtotal' => 200000,
            'ship_fee' => 0,
            'total' => 200000,
            'ship_name' => 'Mitra Instansi',
            'ship_phone' => '081234567890',
            'ship_addr' => 'Semarang',
        ]);

        $this->actingAs($mitra)
            ->post(route('payment.process', $secondOrder->code), ['method' => 'tempo_b2b'])
            ->assertSessionHasErrors('method');

        // Setelah tagihan yang menunggak dilunasi, fasilitas Tempo aktif kembali
        $overduePayment->update([
            'status' => PaymentStatus::Success,
            'paid_at' => now(),
        ]);

        $this->assertFalse($mitra->fresh()->hasOverdueTempo());
        $this->assertTrue($mitra->fresh()->canUseTempo(200000));
    }
}
