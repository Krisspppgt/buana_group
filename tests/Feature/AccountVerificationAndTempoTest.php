<?php

namespace Tests\Feature;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
