<?php

namespace Tests\Feature;

use App\Enums\ProductionStage;
use App\Enums\QuoteStatus;
use App\Enums\Role;
use App\Enums\Unit;
use App\Models\Order;
use App\Models\ProductionLog;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KonveksiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087788112233',
        ]);
    }

    public function test_user_can_view_konveksi_catalog(): void
    {
        $response = $this->actingAs($this->user)->get(route('konveksi.index'));

        $response->assertStatus(200);
        $response->assertSee('Buana Konveksi');
        $response->assertSee('American Drill 1919');
        $response->assertSee('Formulir Kuotasi');
    }

    public function test_user_can_submit_quote_and_get_estimate(): void
    {
        $response = $this->actingAs($this->user)->post(route('konveksi.quotes.store'), [
            'item' => 'Kemeja PDH Organisasi',
            'material' => 'American Drill 1919',
            'qty' => 30,
            'size_s' => 5,
            'size_m' => 10,
            'size_l' => 10,
            'size_xl' => 5,
            'notes' => 'Bordir dada kiri',
        ]);

        $quote = Quote::where('user_id', $this->user->id)->first();
        $this->assertNotNull($quote);
        $this->assertEquals(30, $quote->qty);
        $this->assertGreaterThan(0, $quote->est_price);
        $this->assertEquals(QuoteStatus::Submitted, $quote->status);

        $response->assertRedirect(route('konveksi.quotes.show', $quote->id));
    }

    public function test_user_can_view_transparent_production_tracking(): void
    {
        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->user->id,
            'unit' => Unit::Konveksi,
            'subtotal' => 2500000,
            'ship_fee' => 0,
            'total' => 2500000,
            'ship_name' => 'BEM Wahid Hasyim',
            'ship_phone' => '087788112233',
            'ship_addr' => 'Kampus Menoreh Gedung Ormawa',
        ]);

        ProductionLog::create([
            'order_id' => $order->id,
            'stage' => ProductionStage::Sewing,
            'note' => 'Sedang proses jahit kemeja angkatan 30 pcs',
        ]);

        $response = $this->actingAs($this->user)->get(route('konveksi.tracking', $order->code));

        $response->assertStatus(200);
        $response->assertSee('Progres Produksi Transparan');
        $response->assertSee('Jahit');
        $response->assertSee('Sedang proses jahit kemeja angkatan 30 pcs');
    }

    public function test_user_can_approve_quote_to_create_production_order_and_operator_can_update_stage(): void
    {
        $quote = Quote::create([
            'user_id' => $this->user->id,
            'item' => 'Jas Almamater (Japan Drill)',
            'qty' => 50,
            'sizes' => 'M:25, L:25',
            'est_price' => 6000000,
            'status' => QuoteStatus::Submitted,
        ]);

        $response = $this->actingAs($this->user)->post(route('konveksi.quotes.approve', $quote->id), [
            'ship_addr' => 'Fakultas Kedokteran UNWAHAS',
        ]);

        $quote->refresh();
        $this->assertEquals(QuoteStatus::Approved, $quote->status);
        $this->assertNotNull($quote->order_id);

        $order = Order::find($quote->order_id);
        $this->assertNotNull($order);
        $response->assertRedirect(route('konveksi.tracking', $order->code));

        $operator = User::factory()->create([
            'role' => Role::Operator,
            'phone' => '086699887766',
        ]);

        $this->actingAs($operator)->post(route('konveksi.tracking.stage', $order->code), [
            'stage' => ProductionStage::Cutting->value,
            'note' => 'Pemotongan kain 50 pcs selesai',
        ])->assertRedirect();

        $this->assertEquals(ProductionStage::Cutting, $order->productionLogs()->latest('id')->first()->stage);
    }
}
