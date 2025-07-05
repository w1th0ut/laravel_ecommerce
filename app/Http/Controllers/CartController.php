<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = Cart::getTotalQuantity();
        
        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }
    
    public function add(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->stock_quantity
        ]);
        
        Cart::add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->current_price,
            'quantity' => $request->quantity,
            'attributes' => [
                'image' => $product->images[0] ?? null,
                'slug' => $product->slug,
                'stock' => $product->stock_quantity
            ]
        ]);
        
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);
        
        Cart::update($id, [
            'quantity' => [
                'relative' => false,
                'value' => $request->quantity
            ]
        ]);
        
        return redirect()->back()->with('success', 'Cart updated successfully!');
    }
    
    public function remove($id)
    {
        Cart::remove($id);
        
        return redirect()->back()->with('success', 'Item removed from cart!');
    }
    
    public function clear()
    {
        Cart::clear();
        
        return redirect()->back()->with('success', 'Cart cleared successfully!');
    }
}