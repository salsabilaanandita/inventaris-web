<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class FixStatusesSeeder extends Seeder
{
    public function run(): void
    {
        Order::where('status', 'pending')->update(['status' => 'Menunggu Pembayaran']);
        Order::where('status', 'completed')->update(['status' => 'Pesanan Selesai']);
        Order::where('status', 'cancelled')->update(['status' => 'Dibatalkan']);
        
        $orders = Order::where('status', 'Pesanan Selesai')->get();
        
        $count = 0;
        foreach ($orders as $order) {
            if ($count % 3 == 0) {
                $order->update(['status' => 'Pesanan Diproses']);
            } elseif ($count % 3 == 1) {
                $order->update(['status' => 'Pesanan Dikirim']);
            }
            $count++;
        }
    }
}
