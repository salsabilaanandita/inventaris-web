<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreNotification extends Model
{
    protected $fillable = ['title', 'message', 'role', 'type', 'link', 'is_read'];

    public static function generateAutomated()
    {
        // Peringatan stok menipis
        $lowStockItems = \App\Models\Item::where('total', '<=', 5)->get();
        foreach ($lowStockItems as $item) {
            $roles = ['admin_gudang', 'staff_gudang', 'manager']; // Manager memantau stok juga
            
            foreach ($roles as $role) {
                $existingNotif = self::where('type', 'low_stock')
                    ->where('title', 'like', "%{$item->name}%")
                    ->where('role', $role)
                    ->first();
                    
                if (!$existingNotif) {
                    self::create([
                        'title' => 'Stok Menipis: ' . $item->name,
                        'message' => 'Stok produk ' . $item->name . ' tersisa ' . $item->total . '. Segera periksa ketersediaan.',
                        'role' => $role,
                        'type' => 'low_stock',
                        'link' => route('stocks.index', ['filter' => 'low']),
                    ]);
                } else if (!$existingNotif->is_read) {
                    $existingNotif->update([
                        'message' => 'Stok produk ' . $item->name . ' tersisa ' . $item->total . '. Segera periksa ketersediaan.',
                        'created_at' => now()
                    ]);
                }
            }
        }

        // Peringatan pesanan baru / menunggu konfirmasi
        $pendingOrdersCount = \App\Models\Order::where('status', 'Menunggu Pembayaran')->count();
        if ($pendingOrdersCount > 0) {
            $roles = ['staff_gudang'];
            
            foreach ($roles as $role) {
                $existingOrderNotif = self::where('type', 'pending_order')
                    ->where('role', $role)
                    ->whereDate('created_at', now()->toDateString())
                    ->first();
                
                if (!$existingOrderNotif) {
                    self::create([
                        'title' => 'Pesanan Menunggu Diproses',
                        'message' => "Terdapat $pendingOrdersCount pesanan yang sedang menunggu konfirmasi pembayaran.",
                        'role' => $role,
                        'type' => 'pending_order',
                        'link' => route('orders.index', ['status' => 'Menunggu Pembayaran']),
                    ]);
                } else if (!$existingOrderNotif->is_read) {
                    $existingOrderNotif->update([
                        'message' => "Terdapat $pendingOrdersCount pesanan yang sedang menunggu konfirmasi pembayaran.",
                        'created_at' => now()
                    ]);
                }
            }
        }
    }
}
