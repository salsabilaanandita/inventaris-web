<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) return; // Butuh setidaknya 1 user
        
        $items = Item::take(5)->get();
        if ($items->count() < 1) return;

        $now = Carbon::now();

        // 1. Data Peminjaman (Lending)
        $itemLending = $items[0]; // Ambil barang pertama (Laptop Thinkpad)
        $lendingId = DB::table('lendings')->insertGetId([
            'item_id' => $itemLending->id,
            'user_id' => $user->id,
            'name' => 'Budi Staf IT',
            'total' => 1,
            'notes' => 'Dipinjam untuk tugas luar kota',
            'return_date' => null, // Belum dikembalikan
            'created_at' => $now->copy()->subDays(5),
            'updated_at' => $now->copy()->subDays(5),
        ]);

        // 2. Data Pengembalian (ItemReturn) - Barang kedua
        $itemReturn = $items[1];
        $lendingReturnedId = DB::table('lendings')->insertGetId([
            'item_id' => $itemReturn->id,
            'user_id' => $user->id,
            'name' => 'Andi Staf Marketing',
            'total' => 2,
            'notes' => 'Dipinjam untuk meeting',
            'return_date' => $now->copy()->subDays(8),
            'created_at' => $now->copy()->subDays(10),
            'updated_at' => $now->copy()->subDays(8),
        ]);
        
        DB::table('item_returns')->insert([
            'lending_id' => $lendingReturnedId,
            'item_id' => $itemReturn->id,
            'user_id' => $user->id,
            'quantity' => 2,
            'returned_at' => $now->copy()->subDays(8),
            'notes' => 'Dikembalikan tepat waktu dalam kondisi baik.',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 3. Data Barang Masuk (StockIn)
        $itemStockIn = $items[2];
        DB::table('stock_ins')->insert([
            'item_id' => $itemStockIn->id,
            'user_id' => $user->id,
            'quantity' => 50,
            'received_at' => $now->copy()->subDays(2),
            'notes' => 'Restock bulanan dari supplier',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 4. Data Stok Opname (StockOpname)
        $itemOpname = $items[3];
        DB::table('stock_opnames')->insert([
            'item_id' => $itemOpname->id,
            'user_id' => $user->id,
            'system_stock' => 15,
            'actual_stock' => 14,
            'notes' => 'Satu barang hilang saat dicek di gudang',
            'opname_date' => $now->copy()->subDays(1),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 5. Data Perawatan (Maintenance)
        $itemMaintenance = $items[4];
        DB::table('maintenances')->insert([
            'item_id' => $itemMaintenance->id,
            'user_id' => $user->id,
            'title' => 'Service Berkala',
            'status' => 'completed',
            'cost' => 150000,
            'notes' => 'Membersihkan komponen dalam dan ganti oli',
            'started_at' => $now->copy()->subMonths(1),
            'completed_at' => $now->copy()->subMonths(1)->addDays(2),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 6. Data Kerusakan (Damage)
        DB::table('damages')->insert([
            'item_id' => $itemMaintenance->id,
            'user_id' => $user->id,
            'type' => 'berat',
            'quantity' => 1,
            'notes' => 'Layar retak karena jatuh',
            'reported_at' => $now->copy()->subDays(3),
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 7. Data Pesanan (Customer, Order, OrderItem)
        $customerId = DB::table('customers')->insertGetId([
            'name' => 'PT Cahaya Bangsa',
            'phone' => '081199887766',
            'address' => 'Jl. Pahlawan No. 20, Jakarta',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $orderId = DB::table('orders')->insertGetId([
            'order_number' => 'ORD-' . date('YmdHis'),
            'customer_id' => $customerId,
            'customer_name' => 'PT Cahaya Bangsa',
            'customer_phone' => '081199887766',
            'total_amount' => 5500000,
            'status' => 'completed',
            'payment_method' => 'transfer',
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        DB::table('order_items')->insert([
            'order_id' => $orderId,
            'item_id' => $itemLending->id, // Jual Laptop misalnya
            'quantity' => 1,
            'price' => 5500000,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        // 8. Data Riwayat Stok (StockHistory)
        DB::table('stock_histories')->insert([
            ['item_id' => $itemStockIn->id, 'user_id' => $user->id, 'type' => 'stock_in', 'quantity' => 50, 'notes' => 'Restock', 'transaction_at' => $now->copy()->subDays(2), 'created_at' => $now, 'updated_at' => $now],
            ['item_id' => $itemOpname->id, 'user_id' => $user->id, 'type' => 'stock_opname', 'quantity' => -1, 'notes' => 'Penyesuaian stok opname (-1)', 'transaction_at' => $now->copy()->subDays(1), 'created_at' => $now, 'updated_at' => $now],
            ['item_id' => $itemLending->id, 'user_id' => $user->id, 'type' => 'order', 'quantity' => -1, 'notes' => 'Barang terjual / keluar', 'transaction_at' => $now->copy()->subDays(4), 'created_at' => $now, 'updated_at' => $now],
        ]);
        
        // 9. Tambah beberapa notifikasi
        DB::table('store_notifications')->insert([
            ['role' => 'admin_gudang', 'title' => 'Stok Menipis', 'message' => 'Stok Tinta Printer sisa 4', 'type' => 'warning', 'is_read' => false, 'created_at' => $now, 'updated_at' => $now],
            ['role' => 'super_admin', 'title' => 'Pesanan Baru', 'message' => 'Ada pesanan dari PT Cahaya Bangsa', 'type' => 'info', 'is_read' => false, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
