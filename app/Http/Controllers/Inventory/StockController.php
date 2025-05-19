<?php

// app/Http/Controllers/Inventory/StockController.php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Alert;
use App\Models\Supplier;
use App\Models\Inventory;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with(['inventory', 'department'])
            ->withSum('stockMovements', 'quantity')
            ->paginate(10);
        return view('inventory.stock.index', compact('products'));
    }
    
    public function alerts()
    {
        $alerts = Alert::with('product')->latest()->paginate(10);
        return view('inventory.stock.alerts', compact('alerts'));
    }
    
    public function markAsRead(Alert $alert)
    {
        $alert->update(['is_read' => true]);
        return back()->with('success', 'Alert marked as read');
    }
    
    public function lowStock()
    {
        $products = Product::whereHas('inventory', function($query) {
            $query->whereRaw('quantity <= low_stock_threshold');
        })->with(['inventory', 'department'])->paginate(10);
            
        return view('inventory.stock.low', compact('products'));
    }
    
    public function reorder(Product $product)
    {
        // Create a new purchase order for the product
        $purchaseOrder = PurchaseOrder::create([
            'supplier_id' => $product->supplier_id,
            'user_id' => auth()->id(),
            'status' => 'pending',
            'total_amount' => $product->cost_price * $product->reorder_quantity
        ]);
        
        // Add the product to the purchase order
        $purchaseOrder->items()->create([
            'product_id' => $product->id,
            'quantity' => $product->reorder_quantity,
            'unit_price' => $product->cost_price,
            'total_price' => $product->cost_price * $product->reorder_quantity
        ]);
        
        return redirect()->route('inventory.purchase-orders.show', $purchaseOrder)
            ->with('success', 'Purchase order created for low stock item');
    }
    
    public function adjust(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'reason' => 'required|string'
        ]);
        
        $inventory = $product->inventory;
        $inventory->quantity = $request->quantity;
        $inventory->save();
            
        return back()->with('success', 'Stock adjusted successfully');
    }
}