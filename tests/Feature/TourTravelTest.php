<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Models\TourBooking;
use App\Models\TourPackage;
use App\Models\User;
use Database\Seeders\TourAndTicketSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TourTravelTest extends TestCase
{
    use RefreshDatabase;

    private User $customer;

    private User $civitas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(TourAndTicketSeeder::class);

        $this->customer = User::factory()->create([
            'role' => Role::Customer,
            'phone' => '087711223344',
        ]);

        $this->civitas = User::factory()->create([
            'role' => Role::Civitas,
            'phone' => '085511223344',
            'campus_id' => 'NIM-202611001',
        ]);
    }

    public function test_user_can_view_tour_packages_catalog(): void
    {
        $response = $this->actingAs($this->customer)->get(route('tour.index'));

        $response->assertStatus(200);
        $response->assertSee('Buana Tour & Travel');
        $response->assertSee('Paket Umrah Berkah 12 Hari');
        $response->assertSee('Paket Ziarah Wali 9');
    }

    public function test_civitas_gets_special_price_for_tour_package(): void
    {
        $package = TourPackage::where('category', 'umrah')->first();
        $this->assertNotNull($package);

        $this->assertLessThan($package->price, $package->priceFor($this->civitas));
        $this->assertEquals($package->price_civitas, $package->priceFor($this->civitas));
        $this->assertEquals($package->price, $package->priceFor($this->customer));
    }

    public function test_user_can_book_tour_with_installment_scheme_and_group_manifest(): void
    {
        $package = TourPackage::where('category', 'umrah')->first();

        $response = $this->actingAs($this->customer)->post(route('tour.bookings.store'), [
            'tour_package_id' => $package->id,
            'coordinator_name' => 'H. Ahmad Sobari',
            'coordinator_phone' => '081234567890',
            'total_pax' => 2,
            'payment_scheme' => 'cicilan',
            'installment_tenor' => 6,
            'member_names' => ['H. Ahmad Sobari', 'Hj. Siti Aminah'],
            'member_ids' => ['3301010101900001', '3301010101920002'],
        ]);

        $booking = TourBooking::where('coordinator_name', 'H. Ahmad Sobari')->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2, $booking->total_pax);
        $this->assertEquals('cicilan', $booking->payment_scheme);
        $this->assertEquals(6, $booking->installment_tenor);
        $this->assertEquals($package->min_dp * 2, $booking->paid_amount);
        $this->assertCount(2, $booking->members_data);

        $response->assertRedirect(route('tour.bookings.show', $booking->code));
    }

    public function test_user_can_view_booking_detail_and_installment_progress(): void
    {
        $package = TourPackage::where('category', 'umrah')->first();

        $booking = TourBooking::create([
            'code' => TourBooking::newCode(),
            'user_id' => $this->customer->id,
            'tour_package_id' => $package->id,
            'coordinator_name' => 'H. Ahmad Sobari',
            'coordinator_phone' => '081234567890',
            'total_pax' => 1,
            'payment_scheme' => 'cicilan',
            'installment_tenor' => 6,
            'paid_amount' => 5000000,
            'total_amount' => 28500000,
            'members_data' => [['name' => 'H. Ahmad Sobari', 'id_number' => '3301010101900001']],
            'status' => 1,
        ]);

        $response = $this->actingAs($this->customer)->get(route('tour.bookings.show', $booking->code));

        $response->assertStatus(200);
        $response->assertSee('Detail Booking #'.$booking->code);
        $response->assertSee('Cicilan / Tabungan Umrah');
        $response->assertSee('H. Ahmad Sobari');
    }
}
