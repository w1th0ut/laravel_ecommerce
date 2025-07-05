<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Cart;

class CheckoutController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = Cart::getTotalQuantity();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }
        
        return view('checkout.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_phone' => 'required|string|max:20',
            'billing_address' => 'required|string|max:500',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:10',
            'billing_country' => 'required|string|max:255',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'same_as_billing' => 'boolean'
        ]);
        
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty!');
        }
        
        // Create billing address array
        $billingAddress = [
            'first_name' => $request->billing_first_name,
            'last_name' => $request->billing_last_name,
            'email' => $request->billing_email,
            'phone' => $request->billing_phone,
            'address' => $request->billing_address,
            'city' => $request->billing_city,
            'state' => $request->billing_state,
            'zip' => $request->billing_zip,
            'country' => $request->billing_country
        ];
        
        // Create shipping address
        $shippingAddress = $billingAddress;
        if (!$request->same_as_billing) {
            $shippingAddress = [
                'first_name' => $request->shipping_first_name,
                'last_name' => $request->shipping_last_name,
                'address' => $request->shipping_address,
                'city' => $request->shipping_city,
                'state' => $request->shipping_state,
                'zip' => $request->shipping_zip,
                'country' => $request->shipping_country
            ];
        }
        
        // Generate order number
        $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
        
        // Create order
        $order = Order::create([
            'order_number' => $orderNumber,
            'user_id' => auth()->id(),
            'total_amount' => $cartTotal,
            'status' => 'pending',
            'billing_address' => $billingAddress,
            'shipping_address' => $shippingAddress,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending'
        ]);
        
        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total' => $item->price * $item->quantity
            ]);
        }
        
        // Clear cart
        Cart::clear();
        
        return redirect()->route('orders.show', $order)
            ->with('success', 'Order placed successfully!');
    }
}