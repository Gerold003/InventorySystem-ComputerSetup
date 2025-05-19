<?php

// app/Http/Controllers/Website/CartController.php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Auth::user()->cart;
        $cartItems = $cart ? $cart->items()->with('product')->get() : collect();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $taxRate = config('cart.tax_rate', 0);
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;
        
        return view('website.cart.index', compact('cartItems', 'subtotal', 'tax', 'total'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);
        
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;
        
        if ($product->current_stock < $quantity) {
            return back()->with('error', 'Not enough stock available');
        }
        
        $user = Auth::user();
        $cart = $user->cart ?? Cart::create(['user_id' => $user->id]);
        
        $cartItem = $cart->items()->where('product_id', $product->id)->first();
        
        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($product->current_stock < $newQuantity) {
                return back()->with('error', 'Cannot add more items than available in stock');
            }
            
            $cartItem->update([
                'quantity' => $newQuantity
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }
        
        return back()->with('success', 'Product added to cart');
    }
    
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $product = $cartItem->product;
        
        if (!$product || $product->current_stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'error' => 'Not enough stock available'
            ], 422);
        }

        $cartItem->update([
            'quantity' => $request->quantity
        ]);

        // Recalculate cart totals
        $cart = $cartItem->cart;
        $cartItems = $cart->items()->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $taxRate = config('cart.tax_rate', 0);
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;

        return response()->json([
            'success' => true,
            'item_price' => $product->price,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'cart_count' => $cartItems->count()
        ]);
    }
    
    public function destroy(CartItem $cartItem)
    {
        $cart = $cartItem->cart;
        
        // Delete the cart item
        $cartItem->delete();
        
        // Recalculate cart totals
        $cartItems = $cart->items()->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $taxRate = config('cart.tax_rate', 0);
        $tax = $subtotal * ($taxRate / 100);
        $total = $subtotal + $tax;
        
        // If this was the last item, delete the cart too
        if ($cartItems->count() === 0) {
            $cart->delete();
        }
        
        return response()->json([
            'success' => true,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'cart_count' => $cartItems->count(),
            'message' => 'Item removed from cart'
        ]);
    }
}