<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Inventory;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function inventory()
    {
        $products = Product::with(['inventory', 'department', 'supplier'])
            ->paginate(15);
            
        return view('admin.reports.inventory', compact('products'));
    }

    public function sales()
    {
        $products = Product::with(['inventory'])
            ->whereHas('inventory', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->latest()
            ->paginate(15);
            
        return view('admin.reports.sales', compact('products'));
    }
} 