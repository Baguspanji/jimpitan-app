<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Seed Categories
        $categories = [
            'Sembako' => DB::table('categories')->insertGetId(['name' => 'Sembako', 'created_at' => now(), 'updated_at' => now()]),
            'Snack' => DB::table('categories')->insertGetId(['name' => 'Snack', 'created_at' => now(), 'updated_at' => now()]),
            'Minuman' => DB::table('categories')->insertGetId(['name' => 'Minuman', 'created_at' => now(), 'updated_at' => now()]),
            'Paket' => DB::table('categories')->insertGetId(['name' => 'Paket Spesial', 'created_at' => now(), 'updated_at' => now()]),
        ];

        // 2. Seed Regular Items
        $itemsData = [
            // Sembako
            ['category_id' => $categories['Sembako'], 'name' => 'Ayam', 'unit' => '3 Kg', 'weekly_price' => 3300, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Beras 64', 'unit' => '25 Kg', 'weekly_price' => 8300, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Beras mentari', 'unit' => '25 Kg', 'weekly_price' => 9200, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Beras Zakat Super', 'unit' => '3 Kg', 'weekly_price' => 1200, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Bimoli', 'unit' => '5 Ltr', 'weekly_price' => 3500, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Daging Sapi', 'unit' => '1 Kg', 'weekly_price' => 3500, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Gula', 'unit' => '10 Kg', 'weekly_price' => 4500, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Mie Sedap Goreng', 'unit' => '1 Dos', 'weekly_price' => 2900, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Mie Sedap Soto', 'unit' => '1 Dos', 'weekly_price' => 2700, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Mie Indomie', 'unit' => '1 Dos', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Indomie Geprek', 'unit' => '1 Dos', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Telor (+ bak)', 'unit' => '3 Kg', 'weekly_price' => 2600, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Telor Asin', 'unit' => '25 Biji', 'weekly_price' => 2400, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Minyak Sovia', 'unit' => '1 Dos', 'weekly_price' => 6700, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Minyak Kita', 'unit' => '1 Dos', 'weekly_price' => 6000, 'type' => 'regular'],
            ['category_id' => $categories['Sembako'], 'name' => 'Minyak Sanco 2L', 'unit' => '1 Dos', 'weekly_price' => 6600, 'type' => 'regular'],

            // Snack
            ['category_id' => $categories['Snack'], 'name' => 'Kino Nastar', 'unit' => '2 Top', 'weekly_price' => 1200, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Belinjo', 'unit' => '1 Kg', 'weekly_price' => 2200, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Belinjo Manis', 'unit' => '1 Kg', 'weekly_price' => 2000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Roma kelapa', 'unit' => '1 Blek', 'weekly_price' => 1000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Jelly Inaco', 'unit' => '1 Kg', 'weekly_price' => 800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Roka', 'unit' => '1 Toples', 'weekly_price' => 2000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Kacang Kupas', 'unit' => '1 Kg', 'weekly_price' => 1200, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Kongguan Besar', 'unit' => '1 Blek', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Krispy', 'unit' => '1 Blek', 'weekly_price' => 1500, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Chokolatos', 'unit' => '2 Pak', 'weekly_price' => 800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Tanggo', 'unit' => '1 Blek', 'weekly_price' => 800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Monde Egg Roll', 'unit' => '1 Blek', 'weekly_price' => 2000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Mente', 'unit' => '1 Kg', 'weekly_price' => 4000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Monde', 'unit' => '1 Blek', 'weekly_price' => 4000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Permen Yupi', 'unit' => '1 Toples', 'weekly_price' => 700, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Wafer Nisin Kecil', 'unit' => '1 Blek', 'weekly_price' => 1400, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Wafer Nisin Besar', 'unit' => '1 Blek', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Rambak', 'unit' => '1 Kg', 'weekly_price' => 2800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Sosis', 'unit' => '1 Toples', 'weekly_price' => 600, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Kurma', 'unit' => '1 Kg', 'weekly_price' => 800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Beng - Beng', 'unit' => '1 Box', 'weekly_price' => 800, 'type' => 'regular'],
            ['category_id' => $categories['Snack'], 'name' => 'Nabati', 'unit' => '1 Blek', 'weekly_price' => 800, 'type' => 'regular'],

            // Minuman
            ['category_id' => $categories['Minuman'], 'name' => 'Aqua Cebol', 'unit' => '2 Dos', 'weekly_price' => 1600, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Fanta', 'unit' => '1 Dos', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Spriet', 'unit' => '1 Dos', 'weekly_price' => 3000, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Deal Apel', 'unit' => '2 Dos', 'weekly_price' => 1100, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Club Mini', 'unit' => '2 Dos', 'weekly_price' => 1200, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Teh Gelas', 'unit' => '2 Dos', 'weekly_price' => 1300, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Sirup Marjan', 'unit' => '1 Botol', 'weekly_price' => 500, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'The Eco', 'unit' => '2 Dos', 'weekly_price' => 1300, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Siiplah', 'unit' => '2 Dos', 'weekly_price' => 1400, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Susu indomilk', 'unit' => '1 Dos', 'weekly_price' => 2500, 'type' => 'regular'],
            ['category_id' => $categories['Minuman'], 'name' => 'Teh pucuk', 'unit' => '1 Dos', 'weekly_price' => 2000, 'type' => 'regular'],
        ];

        // Menyimpan ID barang reguler agar bisa direlasikan ke dalam paket
        $insertedItems = [];
        foreach ($itemsData as $item) {
            $item['created_at'] = now();
            $item['updated_at'] = now();
            $insertedId = DB::table('items')->insertGetId($item);
            $insertedItems[$item['name']] = $insertedId;
        }

        // 3. Seed Packages (Barang dengan tipe 'package')
        $paket1Id = DB::table('items')->insertGetId([
            'category_id' => $categories['Paket'],
            'name' => 'Paket 1',
            'unit' => '1 Paket',
            'weekly_price' => 26000,
            'type' => 'package',
            'bonus_desc' => 'Bonus: Handuk Tebal',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $paket2Id = DB::table('items')->insertGetId([
            'category_id' => $categories['Paket'],
            'name' => 'Paket 2',
            'unit' => '1 Paket',
            'weekly_price' => 8000,
            'type' => 'package',
            'bonus_desc' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Seed Item Packages (Relasi Pivot)

        // Asumsi isi Paket 1 berdasarkan deskripsi sebelumnya:
        // Beras 64 (25 Kg), Bimoli (5L), Gula (10 Kg - kita asumsikan yang 10kg), Telor (+ bak), Roka, Kongguan Besar, Wafer Nisin Kecil
        // *Catatan: Anda mungkin perlu menyesuaikan nama barang jika di data asli namanya sedikit berbeda.
        $paket1Items = [
            'Beras 64',
            'Bimoli',
            'Gula',
            'Telor (+ bak)',
            'Roka',
            'Kongguan Besar',
            'Wafer Nisin Kecil',
        ];

        foreach ($paket1Items as $itemName) {
            if (isset($insertedItems[$itemName])) {
                DB::table('item_package')->insert([
                    'package_id' => $paket1Id,
                    'item_id' => $insertedItems[$itemName],
                ]);
            }
        }

        // Asumsi isi Paket 2:
        // Krispy, Wafer Nisin Kecil, Nabati, Good Time Kecil (Asumsi tidak ada di daftar reguler, kita lewati atau sesuaikan), Jelly Inaco, Kongguan Kecil (Kita ganti Kongguan Besar jika tidak ada), Permen Yupi
        $paket2Items = [
            'Krispy',
            'Wafer Nisin Kecil',
            'Nabati',
            'Jelly Inaco',
            'Permen Yupi',
        ];

        foreach ($paket2Items as $itemName) {
            if (isset($insertedItems[$itemName])) {
                DB::table('item_package')->insert([
                    'package_id' => $paket2Id,
                    'item_id' => $insertedItems[$itemName],
                ]);
            }
        }
    }
}
