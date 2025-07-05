@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Checkout</h2>
        </div>
    </div>
    
    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-8">
                <!-- Billing Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Billing Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_first_name" class="form-label">First Name *</label>
                                    <input type="text" class="form-control @error('billing_first_name') is-invalid @enderror" 
                                           id="billing_first_name" name="billing_first_name" value="{{ old('billing_first_name', auth()->user()->name ?? '') }}" required>
                                    @error('billing_first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_last_name" class="form-label">Last Name *</label>
                                    <input type="text" class="form-control @error('billing_last_name') is-invalid @enderror" 
                                           id="billing_last_name" name="billing_last_name" value="{{ old('billing_last_name') }}" required>
                                    @error('billing_last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_email" class="form-label">Email *</label>
                                    <input type="email" class="form-control @error('billing_email') is-invalid @enderror" 
                                           id="billing_email" name="billing_email" value="{{ old('billing_email', auth()->user()->email ?? '') }}" required>
                                    @error('billing_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_phone" class="form-label">Phone *</label>
                                    <input type="tel" class="form-control @error('billing_phone') is-invalid @enderror" 
                                           id="billing_phone" name="billing_phone" value="{{ old('billing_phone') }}" required>
                                    @error('billing_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Address *</label>
                            <textarea class="form-control @error('billing_address') is-invalid @enderror" 
                                      id="billing_address" name="billing_address" rows="3" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_city" class="form-label">City *</label>
                                    <input type="text" class="form-control @error('billing_city') is-invalid @enderror" 
                                           id="billing_city" name="billing_city" value="{{ old('billing_city') }}" required>
                                    @error('billing_city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_state" class="form-label">State *</label>
                                    <input type="text" class="form-control @error('billing_state') is-invalid @enderror" 
                                           id="billing_state" name="billing_state" value="{{ old('billing_state') }}" required>
                                    @error('billing_state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_zip" class="form-label">ZIP Code *</label>
                                    <input type="text" class="form-control @error('billing_zip') is-invalid @enderror" 
                                           id="billing_zip" name="billing_zip" value="{{ old('billing_zip') }}" required>
                                    @error('billing_zip')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_country" class="form-label">Country *</label>
                                    <select class="form-select @error('billing_country') is-invalid @enderror" 
                                            id="billing_country" name="billing_country" required>
                                        <option value="">Select Country</option>
                                        <option value="US" {{ old('billing_country') == 'US' ? 'selected' : '' }}>United States</option>
                                        <option value="CA" {{ old('billing_country') == 'CA' ? 'selected' : '' }}>Canada</option>
                                        <option value="ID" {{ old('billing_country') == 'ID' ? 'selected' : '' }}>Indonesia</option>
                                        <option value="MY" {{ old('billing_country') == 'MY' ? 'selected' : '' }}>Malaysia</option>
                                        <option value="SG" {{ old('billing_country') == 'SG' ? 'selected' : '' }}>Singapore</option>
                                    </select>
                                    @error('billing_country')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="credit_card" value="credit_card" {{ old('payment_method', 'credit_card') == 'credit_card' ? 'checked' : '' }}>
                            <label class="form-check-label" for="credit_card">
                                <i class="fas fa-credit-card"></i> Credit Card
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="paypal" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                            <label class="form-check-label" for="paypal">
                                <i class="fab fa-paypal"></i> PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" 
                                   id="bank_transfer" value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                            <label class="form-check-label" for="bank_transfer">
                                <i class="fas fa-university"></i> Bank Transfer
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <div>
                                    <h6 class="mb-0">{{ $item->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <span>${{ number_format($item->price * $item->quantity, 2) }}</span>
                            </div>
                        @endforeach
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>${{ number_format($cartTotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span>Free</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax</span>
                            <span>$0.00</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between mb-4">
                            <strong>Total</strong>
                            <strong>${{ number_format($cartTotal, 2) }}</strong>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 btn-lg">
                            <i class="fas fa-lock"></i> Place Order
                        </button>
                        
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            <i class="fas fa-arrow-left"></i> Back to Cart
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection