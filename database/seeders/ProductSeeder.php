<?php

namespace Database\Seeders;

use App\Enums\Unit;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'unit' => Unit::Air,
                'name' => 'Galon Air Mineral Unwahas 19L',
                'info' => 'Air mineral higienis bersumber dari mata air alami teruji laboratorium. Cocok untuk kantor, rumah & asrama.',
                'price' => 18000,
                'price_civitas' => 15000,
                'price_bulk' => 13500,
                'bulk_min' => 10,
                'stock' => 500,
                'uom' => 'galon',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Air,
                'name' => 'Air Mineral Unwahas 600ml (Karton 24 Botol)',
                'info' => 'Kemasan botol sedang 600ml isi 24 botol per karton. Pas untuk seminar, rapat dekanat & tamu instansi.',
                'price' => 45000,
                'price_civitas' => 40000,
                'price_bulk' => 38000,
                'bulk_min' => 5,
                'stock' => 300,
                'uom' => 'karton',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Air,
                'name' => 'Air Mineral Unwahas 330ml (Karton 24 Botol)',
                'info' => 'Kemasan botol mini praktis 330ml isi 24 botol. Ideal untuk konsumsi kegiatan mahasiswa dan workshop.',
                'price' => 38000,
                'price_civitas' => 34000,
                'price_bulk' => 31000,
                'bulk_min' => 5,
                'stock' => 250,
                'uom' => 'karton',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Air,
                'name' => 'Air Mineral Cup/Gelas 220ml (Karton 48 Cup)',
                'info' => 'Kemasan cup praktis isi 48 cup per karton dengan sedotan higienis. Favorit untuk hajatan, rapat & pengajian.',
                'price' => 25000,
                'price_civitas' => 22000,
                'price_bulk' => 20000,
                'bulk_min' => 10,
                'stock' => 600,
                'uom' => 'karton',
                'photo' => null,
                'is_active' => true,
            ],
            // 2. Buana Food Services
            [
                'unit' => Unit::Food,
                'name' => 'Paket Rapat Fakultas',
                'info' => 'Snack box + air mineral higienis Unwahas khusus kebutuhan rapat dekanat, dosen, dan seminar kampus.',
                'price' => 15000,
                'price_civitas' => 15000,
                'price_bulk' => 15000,
                'bulk_min' => 10,
                'stock' => 1500,
                'uom' => 'paket',
                'photo' => '/images/paket_rapat_fakultas.jpg',
                'is_active' => true,
            ],
            [
                'unit' => Unit::Food,
                'name' => 'Paket Snack Box Regular',
                'info' => 'Isi 3 macam snack (lemper, pastel, bolu kukus) + Air Mineral Unwahas Cup. Cocok untuk seminar & rapat.',
                'price' => 12000,
                'price_civitas' => 10000,
                'price_bulk' => 9500,
                'bulk_min' => 50,
                'stock' => 1000,
                'uom' => 'box',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Food,
                'name' => 'Paket Snack Box Premium VIP',
                'info' => 'Isi 4 macam snack premium (risoles mayo, pie buah, soes, lapis legit) + jus buah botol mini.',
                'price' => 18000,
                'price_civitas' => 15500,
                'price_bulk' => 14500,
                'bulk_min' => 30,
                'stock' => 500,
                'uom' => 'box',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Food,
                'name' => 'Nasi Kotak Ayam Bakar Menoreh',
                'info' => 'Nasi putih pulen, ayam bakar rempah khas Menoreh, tahu tempe bacem, lalapan segar, sambal bajak & pisang.',
                'price' => 25000,
                'price_civitas' => 22000,
                'price_bulk' => 20000,
                'bulk_min' => 30,
                'stock' => 800,
                'uom' => 'kotak',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Food,
                'name' => 'Nasi Kotak Daging Rendang Unwahas',
                'info' => 'Nasi putih, rendang sapi empuk bumbu Padang asli, perkedel kentang, gulai daun singkong & kerupuk udang.',
                'price' => 32000,
                'price_civitas' => 29000,
                'price_bulk' => 27000,
                'bulk_min' => 30,
                'stock' => 500,
                'uom' => 'kotak',
                'photo' => null,
                'is_active' => true,
            ],
            [
                'unit' => Unit::Food,
                'name' => 'Paket Katering Prasmanan Lengkap',
                'info' => 'Menu prasmanan acara: Nasi, 2 olahan daging/ayam, sop buntut/kimlo, capcay, kerupuk, puding & es buah.',
                'price' => 45000,
                'price_civitas' => 40000,
                'price_bulk' => 38000,
                'bulk_min' => 50,
                'stock' => 1200,
                'uom' => 'porsi',
                'photo' => null,
                'is_active' => true,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
