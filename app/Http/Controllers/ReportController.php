<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\Lending;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function stock(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $items = Item::with('category')
            ->withSum([
                'lendings as active_lending_quantity' => fn ($query) => $query->whereNull('return_date'),
            ], 'total')
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->category_id))
            ->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%' . $request->search . '%'))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('reports.stock', compact('items', 'categories'));
    }

    public function lendings(Request $request)
    {
        $lendings = Lending::with(['item', 'user'])
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->to))
            ->when($request->status === 'active', fn ($query) => $query->whereNull('return_date'))
            ->when($request->status === 'returned', fn ($query) => $query->whereNotNull('return_date'))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.lendings', compact('lendings'));
    }

    public function mutations(Request $request)
    {
        $lendings = Lending::with(['item', 'user'])
            ->when($request->filled('from'), fn ($query) => $query->whereDate('created_at', '>=', $request->from))
            ->when($request->filled('to'), fn ($query) => $query->whereDate('created_at', '<=', $request->to))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('reports.mutations', compact('lendings'));
    }

    public function lowStock()
    {
        $items = Item::with('category')
            ->withSum(['lendings as active_lending_quantity' => fn ($query) => $query->whereNull('return_date')], 'total')
            ->get()
            ->filter(fn ($item) => $item->total - $item->repair - ($item->active_lending_quantity ?? 0) <= 2);

        return view('reports.low_stock', compact('items'));
    }
}