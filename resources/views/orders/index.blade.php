@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Orders</h2>
            
            @if($orders->count() > 0)
                <div class="row">
                    @foreach($orders as $order)
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="row align-items-center">
                                        <div class="col-md-3">
                                            <strong>Order #{{ $order->order_number }}</strong>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                        </div>
                                        <div class="col-md-2">
                                            <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="col-md-2">
                                            <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                        </div>
                                        <div class="col-md-3 text-end">
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                View Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($order->orderItems->take(3) as $item)
                                            <div class="col-md-4 mb-3">
                                                <div class="d-flex align-items-center">
                                                    @if($item->product && $item->product->images && count($item->product->images) > 0)
                                                        <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                                             alt="{{ $item->product->name }}" class="rounded me-3" 
                                                             style="width: 60px; height: 60px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                             style="width: 60px; height: 60px;">
                                                            <i class="fas fa-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-1">{{ $item->product->name ?? 'Product not found' }}</h6>
                                                        <small class="text-muted">Qty: {{ $item->quantity }} × ${{ number_format($item->price, 2) }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($order->orderItems->count() > 3)
                                            <div class="col-md-12">
                                                <small class="text-muted">
                                                    + {{ $order->orderItems->count() - 3 }} more items
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                    <h4>No orders yet</h4>
                    <p class="text-muted">You haven't placed any orders yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection