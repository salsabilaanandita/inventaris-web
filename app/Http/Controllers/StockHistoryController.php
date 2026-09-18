<?php

namespace App\Http\Controllers;

use App\Models\StockHistory;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    public function index(Request $request)
    {
        $histories = StockHistory::with(['item', 'user'])
            ->when($request->filled('item_id'), fn ($query) => $query->where('item_id', $request->item_id))
            ->latest('transaction_at')->paginate(10)->withQueryString();
        return view('stock_histories.index', compact('histories'));
    }
}