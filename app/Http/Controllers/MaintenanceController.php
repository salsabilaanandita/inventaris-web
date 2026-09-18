<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index() { $maintenances = Maintenance::with('item')->latest()->paginate(10); return view('maintenances.index', compact('maintenances')); }
    public function create() { $items = Item::orderBy('name')->get(); return view('maintenances.create', compact('items')); }

    public function store(Request $request)
    {
        $data = $request->validate(['item_id' => 'required|exists:items,id', 'title' => 'required|string|max:255', 'status' => 'required|in:open,process,done', 'cost' => 'required|integer|min:0', 'started_at' => 'required|date', 'completed_at' => 'nullable|date|after_or_equal:started_at', 'notes' => 'nullable|string|max:1000']);
        Maintenance::create($data + ['user_id' => auth()->id()]);
        ActivityLog::create(['user_id' => auth()->id(), 'action' => 'maintenance.created', 'description' => 'Mencatat maintenance barang.', 'ip_address' => request()->ip()]);
        return redirect()->route('maintenances.index')->with('success', 'Maintenance berhasil dicatat.');
    }
}