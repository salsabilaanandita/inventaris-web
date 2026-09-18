<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockHistory;
use App\Models\StockIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index() { $stockIns = StockIn::with('item')->latest('received_at')->paginate(10); return view('stock_ins.index', compact('stockIns')); }
    public function create() { $items = Item::orderBy('name')->get(); return view('stock_ins.create', compact('items')); }

    public function store(Request $request)
    {
        $data = $request->validate(['item_id' => 'required|exists:items,id', 'quantity' => 'required|integer|min:1', 'received_at' => 'required|date', 'notes' => 'nullable|string|max:1000']);
        DB::transaction(function () use ($data) {
            $stockIn = StockIn::create($data + ['user_id' => auth()->id()]);
            $stockIn->item()->increment('total', $data['quantity']);
            StockHistory::create(['item_id' => $data['item_id'], 'user_id' => auth()->id(), 'type' => 'stock_in', 'quantity' => $data['quantity'], 'notes' => $data['notes'] ?? null, 'transaction_at' => $data['received_at']]);
            ActivityLog::create(['user_id' => auth()->id(), 'action' => 'stock_in.created', 'description' => 'Menambahkan stok masuk.', 'ip_address' => request()->ip()]);
        });
        return redirect()->route('stock-ins.index')->with('success', 'Stok masuk berhasil dicatat.');
    }

    public function destroy(StockIn $stockIn) { return back()->with('error', 'Riwayat stok masuk tidak dapat dihapus karena memengaruhi stok.'); }
}