<?php

// app/Http/Controllers/Website/HomeController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'inventory'])
            ->where('is_featured', true)
            ->take(6)
            ->get();
            
        return view('website.home', compact('featuredProducts'));
    }
}