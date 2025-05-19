<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function stockMovement()
    {
        $movements = StockMovement::with(['product', 'user'])
            ->latest()
            ->paginate(15);
            
        return view('inventory.reports.stock-movement', compact('movements'));
    }

    public function lowStock()
    {
        $products = Product::with(['stockMovements', 'supplier'])
            ->withSum('stockMovements', 'quantity')
            ->having('stock_movements_sum_quantity', '<=', DB::raw('reorder_point'))
            ->paginate(15);
            
        return view('inventory.reports.low-stock', compact('products'));
    }
} 