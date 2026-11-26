<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\Role;
use App\Enums\StockMovementType;
use App\Enums\Unit;
use App\Filament\Pages\B2bReceivablesReport;
use App\Filament\Pages\ProfitLossReport;
use App\Filament\Pages\StockMovementsReport;
use App\Models\Expense;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FinancialAndStockReportsTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin Keuangan',
            'email' => 'finance@buanagroup.id',
            'phone' => '081111111111',
            'role' => Role::SuperAdmin,
        ]);
    }

    public function test_admin_can_access_all_report_pages_and_expense_resource(): void
    {
        $endpoints = [
            '/admin/b2b-receivables-report',
            '/admin/stock-movements-report',
            '/admin/profit-loss-report',
            '/admin/expenses',
        ];

        foreach ($endpoints as $endpoint) {
            $this->actingAs($this->admin)
                ->get($endpoint)
                ->assertStatus(200);
        }
    }

    public function test_non_admin_cannot_access_report_pages(): void
    {
        $customer = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087700001111',
        ]);

        $this->actingAs($customer)
            ->get('/admin/b2b-receivables-report')
            ->assertStatus(403);
    }

    public function test_b2b_tempo_payment_creates_due_date_and_appears_in_receivables_summary(): void
    {
        $mitra = User::factory()->create([
            'role' => Role::Coordinator,
            'company_name' => 'PT Mitra Sejahtera',
            'phone' => '081234567890',
            'tempo_approved' => true,
            'tempo_limit' => 5000000,
        ]);

        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $mitra->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Pending,
            'subtotal' => 250000,
            'ship_fee' => 0,
            'total' => 250000,
            'ship_name' => 'PT Mitra Sejahtera',
            'ship_phone' => '081234567890',
            'ship_addr' => 'Semarang',
            'send_at' => now(),
        ]);

        $this->actingAs($mitra)
            ->post(route('payment.process', $order->code), [
                'method' => 'tempo_b2b',
            ])
            ->assertRedirect();

        $payment = Payment::where('order_id', $order->id)->firstOrFail();
        $this->assertSame('tempo_b2b', $payment->method);
        $this->assertSame(PaymentStatus::Pending, $payment->status);
        $this->assertNotNull($payment->due_date);

        $summary = app(B2bReceivablesReport::class)->getSummary();
        $this->assertSame(250000, $summary['active_amount']);
        $this->assertSame(1, $summary['active_count']);
        $this->assertSame(0, $summary['overdue_amount']);

        // Dashboard shows B2B receivables separately
        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertStatus(200)
            ->assertSee('Piutang B2B: Rp 250.000');
    }

    public function test_order_creation_and_cancellation_record_stock_movements_and_cost_price(): void
    {
        $customer = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '081299998888',
        ]);

        $product = Product::create([
            'unit' => Unit::Air,
            'name' => 'Galon Air Unwahas 19L',
            'price' => 18000,
            'cost_price' => 11000,
            'stock' => 50,
            'uom' => 'galon',
            'is_active' => true,
        ]);

        $this->actingAs($customer)
            ->post(route('water.store'), [
                'order_type' => 'regular',
                'products' => [$product->id => 5],
                'ship_name' => 'Budi',
                'ship_phone' => '081299998888',
                'ship_addr' => 'Kampus Unwahas',
            ])
            ->assertRedirect();

        $product->refresh();
        $this->assertSame(45, (int) $product->stock);

        $order = Order::latest('id')->firstOrFail();
        $item = $order->items()->firstOrFail();
        $this->assertSame(11000, (int) $item->cost_price);

        $outMovement = StockMovement::where('order_id', $order->id)
            ->where('type', StockMovementType::Out)
            ->first();
        $this->assertNotNull($outMovement);
        $this->assertSame(5, $outMovement->qty);
        $this->assertSame(50, $outMovement->stock_before);
        $this->assertSame(45, $outMovement->stock_after);

        // Cancel order restores stock and records StockMovement::In
        $order->update(['status' => OrderStatus::Cancelled]);
        $product->refresh();
        $this->assertSame(50, (int) $product->stock);

        $inMovement = StockMovement::where('order_id', $order->id)
            ->where('type', StockMovementType::In)
            ->where('reason', 'order_cancel')
            ->first();
        $this->assertNotNull($inMovement);
        $this->assertSame(5, $inMovement->qty);
        $this->assertSame(45, $inMovement->stock_before);
        $this->assertSame(50, $inMovement->stock_after);

        // Manual restock & opname adjustment via StockService
        $stockService = app(StockService::class);
        $stockService->stockIn($product, 20, 'restock', $this->admin->id, null, 'Restok dari pabrik');
        $this->assertSame(70, (int) $product->fresh()->stock);

        $stockService->adjust($product->fresh(), 68, 'opname', $this->admin->id, 'Selisih opname');
        $this->assertSame(68, (int) $product->fresh()->stock);
    }

    public function test_profit_loss_report_computes_revenue_cogs_expenses_and_net_profit(): void
    {
        $product = Product::create([
            'unit' => Unit::Food,
            'name' => 'Nasi Box Ayam Bakar',
            'price' => 25000,
            'cost_price' => 15000,
            'stock' => 100,
            'uom' => 'box',
            'is_active' => true,
        ]);

        $order = Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->admin->id,
            'unit' => Unit::Food,
            'status' => OrderStatus::Done,
            'subtotal' => 250000,
            'ship_fee' => 0,
            'total' => 250000,
            'ship_name' => 'Panitia',
            'ship_phone' => '081111111111',
            'ship_addr' => 'Semarang',
            'send_at' => now(),
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'qty' => 10,
            'price' => 25000,
            'cost_price' => 15000,
        ]);

        Expense::create([
            'unit' => Unit::Food,
            'category' => 'pengiriman',
            'title' => 'BBM Kurir Food',
            'amount' => 30000,
            'incurred_at' => now()->toDateString(),
            'recorded_by' => $this->admin->id,
        ]);

        $page = new ProfitLossReport;
        $page->mount();
        $data = $page->getReportData();

        $this->assertSame(250000, $data['total_revenue']);
        $this->assertSame(150000, $data['total_cogs']);
        $this->assertSame(100000, $data['gross_profit']);
        $this->assertSame(30000, $data['total_expenses']);
        $this->assertSame(70000, $data['net_profit']);
    }

    public function test_excel_exports_generate_valid_xlsx_workbooks(): void
    {
        $plPage = new ProfitLossReport;
        $plPage->mount();

        foreach ([
            app(B2bReceivablesReport::class)->exportExcel(),
            app(StockMovementsReport::class)->exportExcel(),
            $plPage->exportExcel(),
        ] as $xlsxResponse) {
            ob_start();
            $xlsxResponse->sendContent();
            $xlsxBytes = (string) ob_get_clean();
            $this->assertStringStartsWith('PK', $xlsxBytes);
        }
    }
}
