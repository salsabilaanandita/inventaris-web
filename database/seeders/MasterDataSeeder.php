<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Supplier;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $categories = [
            ['name' => 'Elektronik & IT', 'division_pj' => 'IT Dept'],
            ['name' => 'Perabotan Kantor', 'division_pj' => 'General Affairs'],
            ['name' => 'Alat Tulis Kantor (ATK)', 'division_pj' => 'General Affairs'],
            ['name' => 'Kendaraan Operasional', 'division_pj' => 'Logistik'],
        ];
        foreach ($categories as $cat) { Category::firstOrCreate($cat); }

        // Locations
        $locations = [
            ['name' => 'Gudang Utama (Lantai 1)'],
            ['name' => 'Ruang Server (Lantai 2)'],
            ['name' => 'Gudang Logistik'],
            ['name' => 'Ruang Staff IT'],
        ];
        foreach ($locations as $loc) { Location::firstOrCreate($loc); }

        // Units
        $units = [
            ['name' => 'Unit', 'symbol' => 'Unit'],
            ['name' => 'Pcs', 'symbol' => 'Pcs'],
            ['name' => 'Box', 'symbol' => 'Box'],
            ['name' => 'Rim', 'symbol' => 'Rim'],
            ['name' => 'Set', 'symbol' => 'Set'],
        ];
        foreach ($units as $unit) { Unit::firstOrCreate($unit); }

        // Suppliers
        $suppliers = [
            [
                'name' => 'PT Tekno Mandiri',
                'phone' => '081234567890',
                'address' => 'Jl. Sudirman No. 10, Jakarta'
            ],
            [
                'name' => 'CV Indo Perabot',
                'phone' => '089876543210',
                'address' => 'Jl. Merdeka No. 45, Bandung'
            ],
            [
                'name' => 'Toko ATK Jaya Sejahtera',
                'phone' => '081112223334',
                'address' => 'Kawasan Niaga Bintaro'
            ],
        ];
        foreach ($suppliers as $sup) { Supplier::firstOrCreate($sup); }
    }
}
