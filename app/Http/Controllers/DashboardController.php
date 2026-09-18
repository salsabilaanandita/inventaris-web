<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Lending;
use App\Models\ActivityLog;
use App\Models\StockIn;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Perhitungan Statistik Dasar
        $totalBarang = Item::count();
        $totalAset = Item::sum(DB::raw('price * total'));
        $stokKritis = Item::where('total', '<=', 5)->count();
        $sedangDipinjam = Lending::whereNull('return_date')->sum('total');

        // 2. Data Barang Kritis (untuk list panel kanan)
        $itemsKritis = Item::with('category')->where('total', '<=', 5)->orderBy('total', 'asc')->take(4)->get();

        // 3. Data Aktivitas Terbaru
        $aktivitas = ActivityLog::with('user')->latest()->take(5)->get();

        // 4. Perhitungan Data Chart (7 Hari Terakhir)
        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];

        // Loop dari 6 hari yang lalu hingga hari ini
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            
            // Label misal: "12 Sep"
            $chartLabels[] = $date->translatedFormat('d M');

            // Hitung Barang Masuk (dari StockIn)
            $masuk = StockIn::whereDate('received_at', $dateString)->sum('quantity');
            $chartMasuk[] = (int) $masuk;

            // Hitung Barang Keluar (dari Lending + OrderItem)
            // Asumsi lending->created_at adalah tanggal pinjam
            $lendingOut = Lending::whereDate('created_at', $dateString)->sum('total');
            // Asumsi OrderItem memiliki tanggal berdasarkan orders.created_at
            $orderOut = DB::table('order_items')
                        ->join('orders', 'order_items.order_id', '=', 'orders.id')
                        ->whereDate('orders.created_at', $dateString)
                        ->sum('order_items.quantity');
            
            $chartKeluar[] = (int) ($lendingOut + $orderOut);
        }

        return view('dashboard', compact(
            'totalBarang', 
            'totalAset', 
            'stokKritis', 
            'sedangDipinjam', 
            'itemsKritis', 
            'aktivitas',
            'chartLabels',
            'chartMasuk',
            'chartKeluar'
        ));
    }
}
