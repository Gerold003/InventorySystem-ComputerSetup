<?php

// app/Http/Controllers/Inventory/ProductController.php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'inventory'])->get();
        return view('inventory.products.index', compact('products'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('inventory.products.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', 'unique:products'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'initial_quantity' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0']
        ]);

        try {
            // Create the product first
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->sku = $request->sku;
            $product->is_featured = $request->has('is_featured');
            $product->reorder_point = $request->low_stock_threshold;
            $product->reorder_quantity = $request->initial_quantity;

            // Handle image upload if present
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                $product->image = $path;
            }

            // Save the product
            $product->save();

            // Create inventory record
            Inventory::create([
                'product_id' => $product->id,
                'quantity' => $request->initial_quantity,
                'low_stock_threshold' => $request->low_stock_threshold
            ]);

            return redirect()->route('inventory.products.index')
                ->with('success', "Product '{$product->name}' has been created successfully.");
        } catch (\Exception $e) {
            \Log::error('Product creation error: ' . $e->getMessage());
            if ($request->hasFile('image') && isset($path)) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()
                ->with('error', 'Failed to create product. Please try again.')
                ->withInput();
        }
    }

    
    public function show(Product $product)
    {
        return view('inventory.products.show', compact('product'));
    }
    
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('inventory.products.edit', compact('product', 'categories'));
    }
    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('products')->ignore($product->id)],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_featured' => ['nullable', 'boolean'],
            'low_stock_threshold' => ['required', 'integer', 'min:0']
        ]);

        try {
            // Update basic product information
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->sku = $request->sku;
            $product->is_featured = $request->has('is_featured');

            // Handle image upload if present
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products', $filename, 'public');
                $product->image = $path;
            }

            // Save the product
            $product->save();
            
            // Update inventory
            $product->ensureInventoryExists([
                'low_stock_threshold' => $request->low_stock_threshold
            ]);

            return redirect()->route('inventory.products.index')
                ->with('success', "Product '{$product->name}' has been updated successfully!");
        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            if ($request->hasFile('image') && isset($path)) {
                Storage::disk('public')->delete($path);
            }
            return redirect()->back()
                ->with('error', 'Failed to update product. Please try again.')
                ->withInput();
        }
    }
    public function destroy(Product $product)
{
    try {
        $productName = $product->name;
        
        // Delete product image if it exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        // Delete the product
        $product->delete();

        // Redirect with success message
        return redirect()->route('inventory.products.index')
            ->with('success', "Product '{$productName}' has been deleted successfully.");
    } catch (\Exception $e) {
        // Log the error if needed: Log::error($e);
        return redirect()->back()
            ->with('error', 'Failed to delete product. Please try again.');
    }
}
















  
}