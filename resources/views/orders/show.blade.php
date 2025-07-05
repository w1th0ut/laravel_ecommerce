@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Order Details</h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="row align-items-center mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="col-md-2">
                                @if($item->product && $item->product->images && count($item->product->images) > 0)
                                    <img src="{{ asset('storage/' . $item->product->images[0]) }}" 
                                         alt="{{ $item->product->name }}" class="img-fluid rounded">
                                @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 80px;">
                                        <i class="fas fa-image text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-5">
                                <h6 class="mb-1">{{ $item->product->name ?? 'Product not found' }}</h6>
                                @if($item->product)
                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                @endif
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="badge bg-secondary">Qty: {{ $item->quantity }}</span>
                            </div>
                            <div class="col-md-2 text-center">
                                <strong>${{ number_format($item->price, 2) }}</strong>
                            </div>
                            <div class="col-md-1 text-end">
                                <strong>${{ number_format($item->total, 2) }}</strong>
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->orderItems->sum('total'), 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>Free</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>$0.00</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <strong>${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->status == 'pending' ? 'active' : 'completed' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'processing' ? 'active' : ($order->status == 'shipped' || $order->status == 'delivered' ? 'completed' : '') }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Processing</h6>
                                <small class="text-muted">
                                    @if($order->status == 'processing' || $order->status == 'shipped' || $order->status == 'delivered')
                                        {{ $order->updated_at->format('M d, Y H:i') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'shipped' ? 'active' : ($order->status == 'delivered' ? 'completed' : '') }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Shipped</h6>
                                <small class="text-muted">
                                    @if($order->shipped_at)
                                        {{ $order->shipped_at->format('M d, Y H:i') }}
                                    @elseif($order->status == 'shipped' || $order->status == 'delivered')
                                        {{ $order->updated_at->format('M d, Y H:i') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status == 'delivered' ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <small class="text-muted">
                                    @if($order->delivered_at)
                                        {{ $order->delivered_at->format('M d, Y H:i') }}
                                    @elseif($order->status == 'delivered')
                                        {{ $order->updated_at->format('M d, Y H:i') }}
                                    @else
                                        Pending
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Order Number:</strong><br>
                        <code>{{ $order->order_number }}</code>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'primary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Total Amount:</strong><br>
                        <span class="h5 text-primary">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
            
            <!-- Billing Address -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Billing Address</h6>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}</strong><br>
                        {{ $order->billing_address['address'] }}<br>
                        {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}<br>
                        {{ $order->billing_address['country'] }}<br>
                        <strong>Phone:</strong> {{ $order->billing_address['phone'] }}<br>
                        <strong>Email:</strong> {{ $order->billing_address['email'] }}
                    </address>
                </div>
            </div>
            
            <!-- Shipping Address -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Shipping Address</h6>
                </div>
                <div class="card-body">
                    <address class="mb-0">
                        <strong>{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</strong><br>
                        {{ $order->shipping_address['address'] }}<br>
                        {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                        {{ $order->shipping_address['country'] }}
                    </address>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 0;
    width: 16px;
    height: 16px;
    border-radius: 50%;
    background: #e9ecef;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-item.active .timeline-marker {
    background: #ffc107;
    box-shadow: 0 0 0 2px #ffc107;
}

.timeline-item.completed .timeline-marker {
    background: #28a745;
    box-shadow: 0 0 0 2px #28a745;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}
</style>
@endsection