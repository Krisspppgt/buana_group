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

class FilamentAdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Super Admin Buana',
            'email' => 'superadmin@buanagroup.id',
            'phone' => '081111111111',
            'role' => Role::SuperAdmin,
        ]);
    }

    public function test_non_admin_cannot_access_filament_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087712345678',
        ]);

        $response = $this->actingAs($customer)->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_widgets(): void
    {
        Product::create([
            'unit' => Unit::Air,
            'name' => 'Galon Air Mineral Unwahas 19L',
            'info' => 'Galon higienis',
            'price' => 18000,
            'price_civitas' => 15000,
            'price_bulk' => 13500,
            'bulk_min' => 10,
            'stock' => 15,
            'uom' => 'galon',
            'is_active' => true,
        ]);

        Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->admin->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::Processing,
            'subtotal' => 180000,
            'ship_fee' => 0,
            'total' => 180000,
            'ship_name' => 'Fakultas Teknik',
            'ship_phone' => '081111111111',
            'ship_addr' => 'Kampus UNWAHAS',
            'send_at' => now(),
        ]);

        Order::create([
            'code' => Order::newCode(),
            'user_id' => $this->admin->id,
            'unit' => Unit::Air,
            'status' => OrderStatus::WaitingConfirmation,
            'subtotal' => 270000,
            'ship_fee' => 0,
            'total' => 270000,
            'ship_name' => 'Panitia Seminar',
            'ship_phone' => '081111111111',
            'ship_addr' => 'Kampus UNWAHAS',
            'send_at' => now(),
        ]);

        $response = $this->actingAs($this->admin)->get('/admin');

        $response->assertStatus(200);
        $response->assertSee('Total Omzet Masuk');
        $response->assertSee('Status Inventaris Gudang');
    }

    public function test_admin_can_access_all_management_resources(): void
    {
        $endpoints = [
            '/admin/products',
            '/admin/orders',
            '/admin/discounts',
            '/admin/quotes',
            '/admin/tour-packages',
            '/admin/tour-bookings',
            '/admin/events',
            '/admin/tickets',
            '/admin/users',
        ];

        foreach ($endpoints as $endpoint) {
            $this->actingAs($this->admin)
                ->get($endpoint)
                ->assertStatus(200);
        }
    }
}
