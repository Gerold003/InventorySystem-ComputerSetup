<?php

// app/Http/Controllers/Admin/AdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Department;
use App\Models\DepartmentItem;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalPurchaseOrders = PurchaseOrder::count();
        $totalDepartments = Department::count();
        
        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        $lowStockItems = Product::with('inventory')
            ->whereHas('inventory', function($query) {
                $query->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->orWhere('quantity', '<=', 0);
            })->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalPurchaseOrders',
            'recentUsers',
            'recentOrders',
            'lowStockItems',
            'totalDepartments'
        ));
    }

    public function inventory()
    {
        $products = Product::with('inventory')->paginate(10);
        return view('admin.inventory.index', compact('products'));
    }

    public function purchaseOrders()
    {
        $purchaseOrders = PurchaseOrder::with(['user', 'user.department'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.purchase-orders.index', compact('purchaseOrders'));
    }

    public function approvePurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->update(['status' => 'approved']);
        return redirect()->back()->with('success', 'Purchase order approved successfully');
    }

    public function departmentItems()
    {
        $departments = Department::with('items')->get();
        $products = Product::all();
        return view('admin.department-items.index', compact('departments', 'products'));
    }

    public function assignItemsToDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'items' => 'required|array',
            'quantities' => 'required|array'
        ]);

        foreach ($request->items as $key => $itemId) {
            $quantity = $request->quantities[$key];
            
            // Check if product is already assigned to department
            $departmentItem = DepartmentItem::where('department_id', $request->department_id)
                ->where('product_id', $itemId)
                ->first();

            // Update inventory quantity first to ensure we have stock
            $product = Product::with('inventory')->findOrFail($itemId);
            if (!$product->inventory || $product->inventory->quantity < $quantity) {
                return redirect()->back()->with('error', "Insufficient stock for product: {$product->name}");
            }

            if ($departmentItem) {
                // Update existing assignment
                $departmentItem->increment('quantity', $quantity);
            } else {
                // Create new assignment
                DepartmentItem::create([
                    'department_id' => $request->department_id,
                    'product_id' => $itemId,
                    'quantity' => $quantity
                ]);
            }

            // Deduct from inventory
            $product->inventory->decrement('quantity', $quantity);
        }

        return redirect()->back()->with('success', 'Items assigned to department successfully');
    }

    public function stockAlerts()
    {
        $lowStockItems = Product::with(['inventory', 'stockMovements.user'])
            ->whereHas('inventory', function($query) {
                $query->whereColumn('quantity', '<=', 'low_stock_threshold')
                    ->orWhere('quantity', '<=', 0);
            })->get();
        return view('admin.stock.alerts', compact('lowStockItems'));
    }

    public function reports()
    {
        $monthlyOrders = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->get();
            
        try {
            $departmentUsage = DepartmentItem::with(['department', 'product'])
                ->selectRaw('department_id, SUM(quantity) as total_items')
                ->groupBy('department_id')
                ->get();
        } catch (\Exception $e) {
            $departmentUsage = collect(); // Empty collection if table doesn't exist
        }

        return view('admin.reports.index', compact('monthlyOrders', 'departmentUsage'));
    }

    public function createPurchaseOrder(Request $request)
    {
        $products = Product::with('inventory')->get();
        $suppliers = \App\Models\Supplier::all();
        return view('admin.purchase-orders.create', compact('products', 'suppliers'));
    }

    public function storePurchaseOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'expected_delivery_date' => 'nullable|date|after:today',
            'notes' => 'nullable|string'
        ]);

        try {
            DB::transaction(function() use ($request) {
                // Generate PO number
                $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

                PurchaseOrder::create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'supplier_id' => $request->supplier_id,
                    'user_id' => auth()->id(),
                    'po_number' => $poNumber,
                    'order_date' => now(),
                    'expected_delivery_date' => $request->expected_delivery_date ?? now()->addDays(7),
                    'status' => 'pending',
                    'notes' => $request->notes
                ]);
            });

            return redirect()
                ->route('admin.purchase-orders.index')
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create purchase order. ' . $e->getMessage());
        }
    }

    public function showPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['product', 'supplier', 'user', 'user.department']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }
}