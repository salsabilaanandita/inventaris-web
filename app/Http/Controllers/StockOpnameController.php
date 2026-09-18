<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockHistory;
use App\Models\StockOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOpnameController extends Controller
{
    public function index() { $opnames = StockOpname::with('item')->latest('opname_date')->paginate(10); return view('stock_opnames.index', compact('opnames')); }
    public function create() { $items = Item::orderBy('name')->get(); return view('stock_opnames.create', compact('items')); }

    public function store(Request $request)
    {
        $data = $request->validate(['item_id' => 'required|exists:items,id', 'actual_stock' => 'required|integer|min:0', 'opname_date' => 'required|date', 'notes' => 'nullable|string|max:1000']);
        DB::transaction(function () use ($data) {
            $item = Item::findOrFail($data['item_id']);
            $difference = $data['actual_stock'] - $item->total;
            StockOpname::create($data + ['user_id' => auth()->id(), 'system_stock' => $item->total]);
            $item->update(['total' => $data['actual_stock']]);
            StockHistory::create(['item_id' => $item->id, 'user_id' => auth()->id(), 'type' => 'opname', 'quantity' => $difference, 'notes' => $data['notes'] ?? null, 'transaction_at' => $data['opname_date']]);
            ActivityLog::create(['user_id' => auth()->id(), 'action' => 'stock_opname.created', 'description' => 'Mencatat stok opname.', 'ip_address' => request()->ip()]);
        });
        return redirect()->route('stock-opnames.index')->with('success', 'Stok opname berhasil dicatat.');
    }
}