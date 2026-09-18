<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Damage;
use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DamageController extends Controller
{
    public function index() { $damages = Damage::with('item')->latest('reported_at')->paginate(10); return view('damages.index', compact('damages')); }
    public function create() { $items = Item::orderBy('name')->get(); return view('damages.create', compact('items')); }

    public function store(Request $request)
    {
        $data = $request->validate(['item_id' => 'required|exists:items,id', 'type' => 'required|in:damage,lost', 'quantity' => 'required|integer|min:1', 'reported_at' => 'required|date', 'notes' => 'nullable|string|max:1000']);
        DB::transaction(function () use ($data) {
            $item = Item::findOrFail($data['item_id']);
            Damage::create($data + ['user_id' => auth()->id()]);
            if ($data['type'] === 'lost') { $item->decrement('total', min($data['quantity'], $item->total)); } else { $item->increment('repair', $data['quantity']); }
            StockHistory::create(['item_id' => $item->id, 'user_id' => auth()->id(), 'type' => $data['type'], 'quantity' => -$data['quantity'], 'notes' => $data['notes'] ?? null, 'transaction_at' => $data['reported_at']]);
            ActivityLog::create(['user_id' => auth()->id(), 'action' => 'damage.created', 'description' => 'Mencatat barang rusak atau hilang.', 'ip_address' => request()->ip()]);
        });
        return redirect()->route('damages.index')->with('success', 'Laporan kerusakan berhasil dicatat.');
    }
}