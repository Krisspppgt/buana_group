<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountAndAddressTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'phone' => '087711223344',
            'role' => Role::Customer,
            'campus_id' => null,
        ]);
    }

    public function test_user_can_view_account_page_and_update_profile(): void
    {
        $this->actingAs($this->user)
            ->get(route('account.index'))
            ->assertStatus(200)
            ->assertSee('Informasi Profil Akun')
            ->assertSee('Buku Alamat Pengiriman');

        $this->actingAs($this->user)
            ->put(route('account.profile.update'), [
                'name' => 'Budi Unwahas',
                'email' => 'budi@unwahas.ac.id',
                'phone' => '081234567890',
                'campus_id' => '24101099',
            ])
            ->assertRedirect();

        $this->user->refresh();
        $this->assertEquals('Budi Unwahas', $this->user->name);
        $this->assertEquals('24101099', $this->user->campus_id);
        $this->assertEquals(Role::Customer, $this->user->role);
        $this->assertEquals(Role::Civitas, $this->user->requested_role);
        $this->assertEquals(VerificationStatus::Pending, $this->user->verification_status);
        $this->assertFalse($this->user->isCivitas());
    }

    public function test_user_can_manage_shipping_addresses_and_set_main(): void
    {
        $this->actingAs($this->user)
            ->post(route('account.addresses.store'), [
                'label' => 'Kampus',
                'receiver' => 'Dekanat FT',
                'phone' => '081234567890',
                'detail' => 'Gedung Fakultas Teknik Lt 2 UNWAHAS',
            ])
            ->assertRedirect();

        $first = Address::where('user_id', $this->user->id)->first();
        $this->assertNotNull($first);
        $this->assertTrue($first->is_main);

        $this->actingAs($this->user)
            ->post(route('account.addresses.store'), [
                'label' => 'Rumah',
                'receiver' => 'Budi',
                'phone' => '087711223344',
                'detail' => 'Jl. Menoreh Raya No 10 Semarang',
                'is_main' => 1,
            ])
            ->assertRedirect();

        $second = Address::where('user_id', $this->user->id)->where('label', 'Rumah')->first();
        $this->assertNotNull($second);
        $this->assertTrue($second->is_main);
        $this->assertFalse($first->fresh()->is_main);

        $this->actingAs($this->user)
            ->post(route('account.addresses.main', $first))
            ->assertRedirect();

        $this->assertTrue($first->fresh()->is_main);
        $this->assertFalse($second->fresh()->is_main);

        $this->actingAs($this->user)
            ->delete(route('account.addresses.destroy', $second))
            ->assertRedirect();

        $this->assertDatabaseMissing('addresses', ['id' => $second->id]);
    }
}
