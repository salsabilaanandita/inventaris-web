<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Item::with('category');

        if ($filter === 'low') {
            $query->where('total', '<=', 5)->where('total', '>', 0);
        } elseif ($filter === 'empty') {
            $query->where('total', '<=', 0);
        }

        $items = $query->orderBy('name')->paginate(10)->withQueryString();

        $stats = [
            'total' => Item::count(),
            'safe' => Item::where('total', '>', 5)->count(),
            'low' => Item::where('total', '<=', 5)->where('total', '>', 0)->count(),
            'empty' => Item::where('total', '<=', 0)->count(),
        ];

        return view('stocks.index', compact('items', 'filter', 'stats'));
    }

    public function updateStock(Request $request, Item $item)
    {
        $request->validate([
            'total' => 'required|integer|min:0'
        ]);

        DB::transaction(function () use ($request, $item) {
            $oldTotal = $item->total;
            $newTotal = $request->total;
            $diff = $newTotal - $oldTotal;

            $item->update(['total' => $newTotal]);

            if ($diff != 0) {
                StockHistory::create([
                    'item_id' => $item->id,
                    'user_id' => auth()->id(),
                    'type' => 'adjustment',
                    'quantity' => $diff,
                    'notes' => 'Quick stock update by ' . auth()->user()->name,
                    'transaction_at' => now()
                ]);
            }
        });

        return redirect()->route('stocks.index')->with('success', 'Stok berhasil diperbarui.');
    }
}
