<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Location;
use App\Models\Unit;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ItemsExport;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $query = Item::with(['category', 'location', 'unit', 'supplier'])
            ->withSum('lendings', 'total'); // Automatically adds 'lendings_sum_total'

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter
        $filter = $request->get('filter', 'all');
        if ($filter === 'available') {
            $query->whereRaw('(total - repair - COALESCE((SELECT SUM(total) FROM lendings WHERE item_id = items.id), 0)) > 0');
        } elseif ($filter === 'low_stock') {
            $query->whereRaw('(total - repair - COALESCE((SELECT SUM(total) FROM lendings WHERE item_id = items.id), 0)) > 0')
                  ->whereRaw('(total - repair - COALESCE((SELECT SUM(total) FROM lendings WHERE item_id = items.id), 0)) <= 5');
        } elseif ($filter === 'repair') {
            $query->where('repair', '>', 0);
        }

        $items = $query->paginate(10)->appends($request->all());
        
        $totalItems = Item::count();

        return view('items.index', compact('items', 'filter', 'totalItems'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $locations = Location::all();
        $units = Unit::all();
        $suppliers = Supplier::all();
        
        // PENTING: Jangan taruh tabel @foreach($items) di file create.blade.php
        // Tapi kalau terlanjur ada, kamu harus kirim $items di sini:
        // $items = Item::all(); 
        // return view('items.create', compact('categories', 'items'));

        return view('items.create', compact('categories', 'locations', 'units', 'suppliers'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'unit_id' => 'nullable|exists:units,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'price' => 'required|numeric|min:0',
            'total' => 'required|integer|min:0',
        ]);

        Item::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location_id' => $request->location_id,
            'unit_id' => $request->unit_id,
            'supplier_id' => $request->supplier_id,
            'price' => $request->price,
            'total' => $request->total,
            'repair' => 0, // Default nilai awal
        ]);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specif/ied resource.
     */
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all(); // Dibutuhkan jika ingin ganti kategori saat edit
        $locations = Location::all();
        $units = Unit::all();
        $suppliers = Supplier::all();
        return view('items.edit', compact('item', 'categories', 'locations', 'units', 'suppliers'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $item = Item::findOrFail($id);
        
        // Logika penambahan repair: Nilai lama + Input Baru
        $newBroke = $request->input('new_broke_item', 0);
        $totalRepair = $item->repair + $newBroke;

        $item->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'location_id' => $request->has('location_id') ? $request->location_id : $item->location_id,
            'unit_id' => $request->has('unit_id') ? $request->unit_id : $item->unit_id,
            'supplier_id' => $request->has('supplier_id') ? $request->supplier_id : $item->supplier_id,
            'price' => $request->has('price') ? $request->price : $item->price,
            'total' => $request->total,
            'repair' => $totalRepair,
        ]);

        return redirect()->route('items.index')->with('success', 'Data diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        if (auth()->user()->role === 'staff') {
            abort(403, 'Staff tidak memiliki akses untuk menghapus produk.');
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }

    public function exportExcel()
    {
        return Excel::download(new ItemsExport, 'data-barang-inventaris.xlsx');
    }
}
