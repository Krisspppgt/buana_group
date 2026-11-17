<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\TourPackage;
use Illuminate\Database\Seeder;

class TourAndTicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'title' => 'Paket Umrah Berkah 12 Hari (Bintang 4 Madinah & Makkah)',
                'category' => 'umrah',
                'departure_date' => now()->addMonths(2)->format('Y-m-d'),
                'duration' => '12 Hari',
                'price' => 32500000,
                'price_civitas' => 30500000,
                'allow_installment' => true,
                'min_dp' => 5000000,
                'quota' => 45,
                'itinerary' => 'Hari 1: Semarang - Jakarta - Jeddah. Hari 2-5: Madinah Ziarah Raudhah & Masjid Nabawi. Hari 6-10: Ibadah Umrah Makkah & Thawaf. Hari 11-12: Kepulangan ke Semarang.',
                'is_active' => true,
            ],
            [
                'title' => 'Paket Ziarah Wali 9 Jawa - Madura (Keluarga & Santri)',
                'category' => 'ziarah',
                'departure_date' => now()->addWeeks(3)->format('Y-m-d'),
                'duration' => '4 Hari 3 Malam',
                'price' => 1450000,
                'price_civitas' => 1250000,
                'allow_installment' => true,
                'min_dp' => 300000,
                'quota' => 50,
                'itinerary' => 'Ziarah Makam Sunan Gunung Jati, Kalijaga, Muria, Kudus, Bonang, Drajat, Giri, Maulana Malik Ibrahim, Ampel, hingga Syaikhona Kholil Bangkalan Madura.',
                'is_active' => true,
            ],
            [
                'title' => 'Study Tour Edukasi Kampus & Budaya Yogya - Solo',
                'category' => 'study_tour',
                'departure_date' => now()->addWeeks(4)->format('Y-m-d'),
                'duration' => '2 Hari 1 Malam',
                'price' => 650000,
                'price_civitas' => 550000,
                'allow_installment' => false,
                'min_dp' => 0,
                'quota' => 60,
                'itinerary' => 'Kunjungan Museum Edukasi, Keraton Yogyakarta, Candi Prambanan, Pusat Batik Solo & Workshop Kreatif Mahasiswa.',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            TourPackage::updateOrCreate(
                ['title' => $pkg['title']],
                $pkg
            );
        }

        $events = [
            [
                'title' => 'Seminar Nasional Kewirausahaan Digital & AI Unwahas',
                'organizer' => 'BEM Universitas Wahid Hasyim',
                'event_date' => now()->addDays(14)->setTime(8, 30),
                'location' => 'Auditorium Menoreh Kampus 1 Unwahas',
                'price' => 35000,
                'price_civitas' => 15000,
                'quota' => 250,
                'is_active' => true,
            ],
            [
                'title' => 'Gema Shalawat Akbar & Milad Yayasan Wahid Hasyim ke-38',
                'organizer' => 'Yayasan Wahid Hasyim Semarang',
                'event_date' => now()->addDays(25)->setTime(19, 30),
                'location' => 'Lapangan Utama Kampus Menoreh Unwahas',
                'price' => 50000,
                'price_civitas' => 25000,
                'quota' => 500,
                'is_active' => true,
            ],
            [
                'title' => 'Workshop Sertifikasi IT & Cloud Computing Santri',
                'organizer' => 'Fakultas Teknik Informatika Unwahas',
                'event_date' => now()->addDays(20)->setTime(9, 0),
                'location' => 'Lab Komputer Gedung B Lt. 3',
                'price' => 75000,
                'price_civitas' => 40000,
                'quota' => 80,
                'is_active' => true,
            ],
        ];

        foreach ($events as $evt) {
            Event::updateOrCreate(
                ['title' => $evt['title']],
                $evt
            );
        }
    }
}
