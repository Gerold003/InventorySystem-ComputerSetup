<?php

// app/Http/Controllers/Website/ProductController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'stockMovements']);
        
        // Filter by category if category ID is provided
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
         
        // Filter by price range
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        if ($request->has('featured') && $request->featured) {
            $query->where('is_featured', true);
        }
        
        
        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        return view('website.products.index', compact('products', 'categories'));
    }
    
    public function show(Product $product)
    {
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder()
            ->take(4)
            ->get();
            
        return view('website.products.show', compact('product', 'relatedProducts'));
    }
    
    public function category(Category $category)
    {
        $products = $category->products()
            ->with('stockMovements')
            ->paginate(12);
        $categories = Category::all();
        
        return view('website.products.index', compact('products', 'categories'));
    }
}
