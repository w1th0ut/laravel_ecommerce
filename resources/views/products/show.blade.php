@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="product-images">
                <img src="https://via.placeholder.com/600x600/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($product->name) }}" 
                     class="img-fluid rounded" alt="{{ $product->name }}">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product-details">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                        <li class="breadcrumb-item active">{{ $product->name }}</li>
                    </ol>
                </nav>
                
                <h1 class="h3 mb-3">{{ $product->name }}</h1>
                
                <div class="mb-3">
                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                    <span class="badge bg-info">SKU: {{ $product->sku }}</span>
                </div>
                
                <div class="price mb-4">
                    @if($product->sale_price)
                        <span class="h4 text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                        <span class="h6 text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                        <span class="badge bg-danger ms-2">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    @else
                        <span class="h4 fw-bold">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>
                
                <div class="description mb-4">
                    <h6>Description</h6>
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="stock-info mb-4">
                    @if($product->stock_quantity > 0)
                        <span class="text-success">
                            <i class="fas fa-check-circle"></i> In Stock ({{ $product->stock_quantity }} available)
                        </span>
                    @else
                        <span class="text-danger">
                            <i class="fas fa-times-circle"></i> Out of Stock
                        </span>
                    @endif
                </div>
                
                @if($product->stock_quantity > 0)
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-3">
                            <div class="col-4">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="quantity" name="quantity" 
                                       value="1" min="1" max="{{ $product->stock_quantity }}">
                            </div>
                            <div class="col-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-shopping-cart"></i> Add to Cart
                                </button>
                            </div>
                        </div>
                    </form>
                @endif
                
                <div class="product-meta">
                    <small class="text-muted">
                        Category: <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a><br>
                        Added: {{ $product->created_at->format('M d, Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4">Related Products</h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-lg-3 col-md-6">
                            <div class="card product-card h-100">
                                <img src="https://via.placeholder.com/300x250/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($related->name) }}" 
                                     class="card-img-top" alt="{{ $related->name }}">
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ $related->name }}</h6>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold">${{ number_format($related->current_price, 2) }}</span>
                                        </div>
                                        <a href="{{ route('products.show', $related->slug) }}" class="btn btn-outline-primary btn-sm w-100">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection