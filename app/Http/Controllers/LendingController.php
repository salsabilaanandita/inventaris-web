<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\DetailLending;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LendingController extends Controller {

    public function index(Request $request) {
        $query = Lending::with(['user', 'item'])->latest();

        if ($request->has('status')) {
            if ($request->status == 'returned') {
                $query->whereNotNull('return_date');
            } elseif ($request->status == 'not_returned') {
                $query->whereNull('return_date');
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereHas('item', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $lendings = $query->paginate(10)->appends($request->all());
        $totalLendings = Lending::count();

        return view('lendings.index', compact('lendings', 'totalLendings'));
    }

    public function create(Request $request)
    {
        // Mengambil semua data barang
        $items = Item::all(); 
        
        // Mengambil nilai 'count' dari URL (contoh: ?count=2)
        // Jika tidak ada di URL, maka default-nya adalah 1
        $count = $request->query('count', 1);

        // Pastikan 'count' ikut dikirim ke view
        return view('lendings.create', compact('items', 'count'));
    }

    public function store(Request $request)
    {
        foreach ($request->item_id as $key => $val) {
            Lending::create([
                'item_id' => $request->item_id[$key],
                'user_id' => Auth::id(),
                'name'    => $request->name,
                'total'   => $request->total[$key],
                'notes'   => $request->notes,
            ]);
        }
        return redirect()->route('lendings.index')->with('success', 'Berhasil meminjam barang!');
    }

    public function returnItem($id)
    {
        $lending = Lending::findOrFail($id);
        
        \Illuminate\Support\Facades\DB::transaction(function () use ($lending) {
            $now = now();
            \App\Models\ItemReturn::create([
                'lending_id' => $lending->id,
                'item_id' => $lending->item_id,
                'user_id' => Auth::id(),
                'quantity' => $lending->total,
                'returned_at' => $now,
                'notes' => $lending->notes // Salin catatan dari form peminjaman awal
            ]);

            $lending->update([
                'return_date' => $now
            ]);

            \App\Models\ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'return.created',
                'description' => 'Mencatat pengembalian barang.',
                'ip_address' => request()->ip()
            ]);
        });

        return back()->with('success', 'Item has been returned!');
    }

    public function show($id)
    {
        $item = Item::with('lendings')->findOrFail($id);
        return view('lendings.show', compact('item'));
    }
    
    public function update(Request $request, $id)
    {
        $lending = Lending::findOrFail($id);
        $lending->update(['status' => 'returned']); // Ganti status jadi returned

        return back()->with('success', 'Barang telah dikembalikan');
}

    public function destroy($id)
    {
        $lending = Lending::findOrFail($id);
        $lending->delete();

        return back()->with('success', 'Data berhasil dihapus');
    }
}