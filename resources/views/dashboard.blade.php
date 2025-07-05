@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Welcome, {{ auth()->user()->name }}!</h2>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-3x text-primary mb-3"></i>
                            <h5>My Orders</h5>
                            <p class="text-muted">View your order history</p>
                            <a href="{{ route('orders.index') }}" class="btn btn-primary">View Orders</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-box fa-3x text-success mb-3"></i>
                            <h5>Browse Products</h5>
                            <p class="text-muted">Discover amazing products</p>
                            <a href="{{ route('products.index') }}" class="btn btn-success">Shop Now</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-user fa-3x text-info mb-3"></i>
                            <h5>My Profile</h5>
                            <p class="text-muted">Manage your account</p>
                            <a href="{{ route('profile.edit') }}" class="btn btn-info">Edit Profile</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders -->
            @php
                $recentOrders = \App\Models\Order::where('user_id', auth()->id())
                                                ->with('orderItems.product')
                                                ->latest()
                                                ->take(3)
                                                ->get();
            @endphp
            
            @if($recentOrders->count() > 0)
                <div class="mt-5">
                    <h4 class="mb-3">Recent Orders</h4>
                    <div class="row">
                        @foreach($recentOrders as $order)
                            <div class="col-md-4 mb-3">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="card-title">Order #{{ $order->order_number }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small><br>
                                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : 'primary' }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </p>
                                        <p class="card-text">
                                            <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                        </p>
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection