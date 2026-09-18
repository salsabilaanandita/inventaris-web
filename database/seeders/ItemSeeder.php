<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Supplier;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all master data IDs
        $catElektronik = Category::where('name', 'Elektronik & IT')->first()->id ?? null;
        $catPerabotan = Category::where('name', 'Perabotan Kantor')->first()->id ?? null;
        $catATK = Category::where('name', 'Alat Tulis Kantor (ATK)')->first()->id ?? null;
        $catKendaraan = Category::where('name', 'Kendaraan Operasional')->first()->id ?? null;

        $locGudangUtama = Location::where('name', 'Gudang Utama (Lantai 1)')->first()->id ?? null;
        $locServer = Location::where('name', 'Ruang Server (Lantai 2)')->first()->id ?? null;
        $locLogistik = Location::where('name', 'Gudang Logistik')->first()->id ?? null;
        $locStaffIT = Location::where('name', 'Ruang Staff IT')->first()->id ?? null;

        $unitUnit = Unit::where('name', 'Unit')->first()->id ?? null;
        $unitPcs = Unit::where('name', 'Pcs')->first()->id ?? null;
        $unitBox = Unit::where('name', 'Box')->first()->id ?? null;
        $unitRim = Unit::where('name', 'Rim')->first()->id ?? null;
        $unitSet = Unit::where('name', 'Set')->first()->id ?? null;

        $supTekno = Supplier::where('name', 'PT Tekno Mandiri')->first()->id ?? null;
        $supPerabot = Supplier::where('name', 'CV Indo Perabot')->first()->id ?? null;
        $supATK = Supplier::where('name', 'Toko ATK Jaya Sejahtera')->first()->id ?? null;

        $items = [
            // Elektronik & IT
            [
                'name' => 'Laptop ThinkPad T14',
                'category_id' => $catElektronik,
                'location_id' => $locStaffIT,
                'unit_id' => $unitUnit,
                'supplier_id' => $supTekno,
                'price' => 15000000,
                'total' => 10,
                'repair' => 0,
            ],
            [
                'name' => 'Monitor Dell 24 Inch',
                'category_id' => $catElektronik,
                'location_id' => $locStaffIT,
                'unit_id' => $unitUnit,
                'supplier_id' => $supTekno,
                'price' => 2500000,
                'total' => 20,
                'repair' => 1,
            ],
            [
                'name' => 'Switch Cisco 24 Port',
                'category_id' => $catElektronik,
                'location_id' => $locServer,
                'unit_id' => $unitUnit,
                'supplier_id' => $supTekno,
                'price' => 8500000,
                'total' => 5,
                'repair' => 0,
            ],
            [
                'name' => 'Kabel LAN Cat6 (Roll)',
                'category_id' => $catElektronik,
                'location_id' => $locServer,
                'unit_id' => $unitBox,
                'supplier_id' => $supTekno,
                'price' => 1200000,
                'total' => 8,
                'repair' => 0,
            ],

            // Perabotan Kantor
            [
                'name' => 'Kursi Kerja Ergonomis',
                'category_id' => $catPerabotan,
                'location_id' => $locGudangUtama,
                'unit_id' => $unitUnit,
                'supplier_id' => $supPerabot,
                'price' => 850000,
                'total' => 50,
                'repair' => 2,
            ],
            [
                'name' => 'Meja Staff 120cm',
                'category_id' => $catPerabotan,
                'location_id' => $locGudangUtama,
                'unit_id' => $unitUnit,
                'supplier_id' => $supPerabot,
                'price' => 1200000,
                'total' => 30,
                'repair' => 0,
            ],
            [
                'name' => 'Lemari Arsip Besi',
                'category_id' => $catPerabotan,
                'location_id' => $locGudangUtama,
                'unit_id' => $unitUnit,
                'supplier_id' => $supPerabot,
                'price' => 2100000,
                'total' => 15,
                'repair' => 0,
            ],
            [
                'name' => 'Sofa Ruang Tunggu',
                'category_id' => $catPerabotan,
                'location_id' => $locGudangUtama,
                'unit_id' => $unitSet,
                'supplier_id' => $supPerabot,
                'price' => 4500000,
                'total' => 2,
                'repair' => 0,
            ],

            // ATK
            [
                'name' => 'Kertas HVS A4 80gr',
                'category_id' => $catATK,
                'location_id' => $locLogistik,
                'unit_id' => $unitRim,
                'supplier_id' => $supATK,
                'price' => 55000,
                'total' => 100, // Banyak stok
                'repair' => 0,
            ],
            [
                'name' => 'Pulpen Gel Hitam',
                'category_id' => $catATK,
                'location_id' => $locLogistik,
                'unit_id' => $unitBox,
                'supplier_id' => $supATK,
                'price' => 35000,
                'total' => 50,
                'repair' => 0,
            ],
            [
                'name' => 'Tinta Printer Epson Hitam',
                'category_id' => $catATK,
                'location_id' => $locLogistik,
                'unit_id' => $unitPcs,
                'supplier_id' => $supATK,
                'price' => 95000,
                'total' => 4, // Stok tipis (restock alert)
                'repair' => 0,
            ],
            [
                'name' => 'Lakban Hitam Besar',
                'category_id' => $catATK,
                'location_id' => $locLogistik,
                'unit_id' => $unitPcs,
                'supplier_id' => $supATK,
                'price' => 15000,
                'total' => 3, // Stok tipis (restock alert)
                'repair' => 0,
            ],
            [
                'name' => 'Stapler Besar Max',
                'category_id' => $catATK,
                'location_id' => $locLogistik,
                'unit_id' => $unitPcs,
                'supplier_id' => $supATK,
                'price' => 75000,
                'total' => 12,
                'repair' => 1,
            ],

            // Kendaraan (Stok sedikit tapi mahal)
            [
                'name' => 'Mobil Avanza Operasional',
                'category_id' => $catKendaraan,
                'location_id' => $locGudangUtama, // Bisa juga parkiran kalau ada
                'unit_id' => $unitUnit,
                'supplier_id' => null, // Gak dari supplier atk
                'price' => 250000000,
                'total' => 2,
                'repair' => 0,
            ],
            [
                'name' => 'Motor Honda Beat Operasional',
                'category_id' => $catKendaraan,
                'location_id' => $locGudangUtama,
                'unit_id' => $unitUnit,
                'supplier_id' => null,
                'price' => 18000000,
                'total' => 5,
                'repair' => 1, // 1 lagi di bengkel
            ],
        ];

        foreach ($items as $item) {
            Item::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}
