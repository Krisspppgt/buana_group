<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Enums\VerificationStatus;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Super Admin Buana',
                'email' => 'superadmin@buanagroup.id',
                'phone' => '081111111111',
                'password' => Hash::make('password123'),
                'role' => Role::SuperAdmin,
                'campus_id' => 'DIR-001',
            ],
            [
                'name' => 'Admin Unit Usaha',
                'email' => 'admin@buanagroup.id',
                'phone' => '082222222222',
                'password' => Hash::make('password123'),
                'role' => Role::Admin,
                'campus_id' => null,
            ],
            [
                'name' => 'Koordinator Panitia B2B',
                'email' => 'b2b@unwahas.ac.id',
                'phone' => '083333333333',
                'password' => Hash::make('password123'),
                'role' => Role::Coordinator,
                'verification_status' => VerificationStatus::Approved,
                'verified_at' => now(),
                'company_name' => 'BEM Universitas Wahid Hasyim',
                'position' => 'Koordinator Pengadaan',
                'tempo_approved' => true,
                'tempo_limit' => 10000000,
                'campus_id' => 'BEM-2026',
            ],
            [
                'name' => 'Kurir Logistik Armada',
                'email' => 'kurir@buanagroup.id',
                'phone' => '084444444444',
                'password' => Hash::make('password123'),
                'role' => Role::Courier,
                'campus_id' => null,
            ],
            [
                'name' => 'Ahmad (Mahasiswa Unwahas)',
                'email' => 'ahmad@student.unwahas.ac.id',
                'phone' => '085555555555',
                'password' => Hash::make('password123'),
                'role' => Role::Civitas,
                'verification_status' => VerificationStatus::Approved,
                'verified_at' => now(),
                'faculty' => 'Fakultas Teknik',
                'tempo_approved' => false,
                'tempo_limit' => 0,
                'campus_id' => '24101001',
            ],
            [
                'name' => 'Operator Konveksi & Gate',
                'email' => 'operator@buanagroup.id',
                'phone' => '086666666666',
                'password' => Hash::make('password123'),
                'role' => Role::Operator,
                'campus_id' => null,
            ],
            [
                'name' => 'Budi (Pelanggan Umum)',
                'email' => 'budi@gmail.com',
                'phone' => '087777777777',
                'password' => Hash::make('password123'),
                'role' => Role::Customer,
                'campus_id' => null,
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['phone' => $data['phone']],
                $data
            );
        }
    }
}
