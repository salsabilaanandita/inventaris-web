<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\ItemReturn;
use App\Models\Lending;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index() { $returns = ItemReturn::with(['item', 'lending'])->latest('returned_at')->paginate(10); return view('returns.index', compact('returns')); }
    public function create() { $lendings = Lending::with('item')->whereNull('return_date')->latest()->get(); return view('returns.create', compact('lendings')); }

    public function store(Request $request)
    {
        $data = $request->validate(['lending_id' => 'required|exists:lendings,id', 'returned_at' => 'required|date', 'notes' => 'nullable|string|max:1000']);
        DB::transaction(function () use ($data) {
            $lending = Lending::with('item')->whereNull('return_date')->findOrFail($data['lending_id']);
            ItemReturn::create(['lending_id' => $lending->id, 'item_id' => $lending->item_id, 'user_id' => auth()->id(), 'quantity' => $lending->total, 'returned_at' => $data['returned_at'], 'notes' => $data['notes'] ?? null]);
            $lending->update(['return_date' => $data['returned_at']]);
            ActivityLog::create(['user_id' => auth()->id(), 'action' => 'return.created', 'description' => 'Mencatat pengembalian barang.', 'ip_address' => request()->ip()]);
        });
        return redirect()->route('returns.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}