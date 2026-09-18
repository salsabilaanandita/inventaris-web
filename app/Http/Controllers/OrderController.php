<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Order::with('customer')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10)->withQueryString();

        return view('orders.index', compact('orders', 'status'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $items = Item::where('total', '>', 0)->orderBy('name')->get();
        return view('orders.create', compact('customers', 'items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'customer_name' => 'required_without:customer_id|nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $customerName = $request->customer_name;
            $customerPhone = null;

            if ($request->customer_id) {
                $customer = Customer::find($request->customer_id);
                $customerName = $customer->name;
                $customerPhone = $customer->phone;
            }

            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'customer_id' => $request->customer_id,
                'customer_name' => $customerName,
                'customer_phone' => $customerPhone,
                'status' => 'Menunggu Pembayaran',
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $itemData) {
                $item = Item::find($itemData['id']);
                $subtotal = $item->price * $itemData['quantity'];
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_id' => $item->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $item->price,
                    'subtotal' => $subtotal,
                ]);

                $totalAmount += $subtotal;
            }

            $order->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    public function show(Order $order)
    {
        $order->load('orderItems.item', 'customer');
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $newStatus = $request->input('status');
        $validStatuses = ['Menunggu Pembayaran', 'Pesanan Diproses', 'Pesanan Dikirim', 'Pesanan Selesai', 'Dibatalkan'];

        if (!in_array($newStatus, $validStatuses)) {
            return back()->with('error', 'Status pesanan tidak valid.');
        }

        // Jika status berubah dari 'Menunggu Pembayaran' ke status yang memproses (Diproses, Dikirim, Selesai), kurangi stok
        $forwardStatuses = ['Pesanan Diproses', 'Pesanan Dikirim', 'Pesanan Selesai'];
        if (in_array($newStatus, $forwardStatuses) && $order->status === 'Menunggu Pembayaran') {
            DB::transaction(function () use ($order) {
                foreach ($order->orderItems as $orderItem) {
                    $item = $orderItem->item;
                    if ($item->total >= $orderItem->quantity) {
                        $item->decrement('total', $orderItem->quantity);
                    } else {
                        throw new \Exception("Stok {$item->name} tidak mencukupi.");
                    }
                }
            });
        }

        $order->update(['status' => $newStatus]);

        return back()->with('success', "Status pesanan diperbarui menjadi $newStatus.");
    }
}
