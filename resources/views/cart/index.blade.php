@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Shopping Cart</h2>
            
            @if($cartItems->count() > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-body">
                                @foreach($cartItems as $item)
                                    <div class="cart-item">
                                        <div class="row align-items-center">
                                            <div class="col-md-2">
                                                <img src="https://via.placeholder.com/100x100/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($item->name) }}" 
                                                     class="img-fluid rounded" alt="{{ $item->name }}">
                                            </div>
                                            <div class="col-md-4">
                                                <h6 class="mb-1">{{ $item->name }}</h6>
                                                <small class="text-muted">ID: {{ $item->id }}</small>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group">
                                                    <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                               min="1" max="{{ $item->attributes->stock ?? 99 }}" 
                                                               class="form-control quantity-input">
                                                        <button type="submit" class="btn btn-outline-secondary btn-sm">Update</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <strong>${{ number_format($item->price, 2) }}</strong>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                                            </div>
                                            <div class="col-md-12 mt-2">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Items ({{ $cartCount }})</span>
                                    <span>${{ number_format($cartTotal, 2) }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-3">
                                    <span>Shipping</span>
                                    <span>Free</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Total</strong>
                                    <strong>${{ number_format($cartTotal, 2) }}</strong>
                                </div>
                                
                                @auth
                                    <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mb-2">
                                        Proceed to Checkout
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">
                                        Login to Checkout
                                    </a>
                                @endauth
                                
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <form action="{{ route('cart.clear') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" 
                                onclick="return confirm('Are you sure you want to clear the cart?')">
                            Clear Cart
                        </button>
                    </form>
                </div>
            @else
                <div class="text-center py-5">
                    <h4>Your cart is empty</h4>
                    <p class="text-muted">Add some products to your cart to see them here.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection