<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Department;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index()
    {
        $products = Product::with('department')->paginate(15);
        $departments = Department::all();
        return view('inventory.assignments.index', compact('products', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'department_id' => 'required|exists:departments,id'
        ]);

        Product::findOrFail($request->product_id)
            ->update(['department_id' => $request->department_id]);

        return redirect()->back()->with('success', 'Product assigned successfully');
    }
} 