<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Order;
use App\Models\StoreNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $role = auth()->user()->role;
        $notifications = StoreNotification::where(function ($query) use ($role) {
                $query->where('role', $role)
                      ->orWhereNull('role');
            })
            ->latest()
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead($id)
    {
        $notification = StoreNotification::findOrFail($id);
        $notification->update(['is_read' => true]);
        
        if ($notification->link) {
            return redirect($notification->link);
        }
        
        return back();
    }

    public function markAllAsRead()
    {
        $role = auth()->user()->role;
        StoreNotification::where('is_read', false)
            ->where(function ($query) use ($role) {
                $query->where('role', $role)
                      ->orWhereNull('role');
            })
            ->update(['is_read' => true]);
            
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }


}
