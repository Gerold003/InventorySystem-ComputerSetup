<?php

// app/Http/Controllers/Sales/OrderController.php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Sale;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'sale'])->latest()->paginate(10);
        return view('sales.orders.index', compact('orders'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'sale', 'items.product']);
        return view('sales.orders.show', compact('order'));
    }
    
    public function edit(Order $order)
    {
        $order->load(['user', 'sale', 'items.product']);
        
        if (!$order->sale) {
            // Create a pending sale record if none exists
            $order->sale()->create([
                'user_id' => auth()->id(),
                'total_amount' => $order->total,
                'payment_status' => 'pending',
                'fulfillment_status' => 'pending'
            ]);
        } elseif ($order->sale->fulfillment_status == 'delivered') {
            return redirect()->route('sales.orders.show', $order)
                ->with('error', 'Delivered orders cannot be edited');
        }
        
        return view('sales.orders.edit', compact('order'));
    }
    
    public function fulfill(Order $order)
    {
        if (!$order->sale) {
            return back()->with('error', 'Order does not have a sale record');
        }
        
        if ($order->sale->fulfillment_status == 'delivered') {
            return back()->with('error', 'Order is already delivered');
        }
        
        $order->sale->update(['fulfillment_status' => 'delivered']);
        return back()->with('success', 'Order marked as delivered');
    }
    
    public function cancel(Order $order)
    {
        if ($order->sale && $order->sale->fulfillment_status == 'delivered') {
            return back()->with('error', 'Delivered orders cannot be cancelled');
        }
        
        // Restore inventory if order is cancelled
        if ($order->sale) {
            foreach ($order->items as $item) {
                $currentStock = $item->product->current_stock;
                if ($currentStock) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }
            
            $order->sale->update([
                'payment_status' => 'refunded',
                'fulfillment_status' => 'cancelled'
            ]);
        } else {
            // If no sale record exists (shouldn't happen), just cancel the order
            Sale::create([
                'user_id' => $order->user_id,
                'order_id' => $order->id,
                'total_amount' => $order->total,
                'payment_status' => 'refunded',
                'fulfillment_status' => 'cancelled'
            ]);
        }
        
        return back()->with('success', 'Order cancelled and inventory restored');
    }
}