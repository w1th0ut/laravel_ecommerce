@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Order #{{ $order->order_number }}</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Orders
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal">
                        <i class="fas fa-edit"></i> Update Status
                    </button>
                </div>
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
                            <div class="col-md-4">
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
                            <div class="col-md-2 text-end">
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
        </div>
        
        <div class="col-lg-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Order Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Customer:</strong><br>
                        @if($order->user)
                            {{ $order->user->name }}<br>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        @else
                            <span class="text-muted">Guest User</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Order Date:</strong><br>
                        {{ $order->created_at->format('M d, Y H:i') }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge bg-{{ 
                            $order->status == 'delivered' ? 'success' : 
                            ($order->status == 'cancelled' ? 'danger' : 
                            ($order->status == 'shipped' ? 'info' : 
                            ($order->status == 'processing' ? 'warning' : 'secondary'))) 
                        }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Payment Status:</strong><br>
                        <span class="badge bg-{{ 
                            $order->payment_status == 'paid' ? 'success' : 
                            ($order->payment_status == 'failed' ? 'danger' : 
                            ($order->payment_status == 'refunded' ? 'info' : 'warning')) 
                        }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Address -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Customer Address</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Billing Address:</strong><br>
                        <address class="mb-0">
                            {{ $order->billing_address['first_name'] }} {{ $order->billing_address['last_name'] }}<br>
                            {{ $order->billing_address['address'] }}<br>
                            {{ $order->billing_address['city'] }}, {{ $order->billing_address['state'] }} {{ $order->billing_address['zip'] }}<br>
                            {{ $order->billing_address['country'] }}<br>
                            <strong>Phone:</strong> {{ $order->billing_address['phone'] }}<br>
                            <strong>Email:</strong> {{ $order->billing_address['email'] }}
                        </address>
                    </div>
                    
                    <div>
                        <strong>Shipping Address:</strong><br>
                        <address class="mb-0">
                            {{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}<br>
                            {{ $order->shipping_address['address'] }}<br>
                            {{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} {{ $order->shipping_address['zip'] }}<br>
                            {{ $order->shipping_address['country'] }}
                        </address>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="payment_status" class="form-label">Payment Status</label>
                        <select class="form-select" id="payment_status" name="payment_status" required>
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Order</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection