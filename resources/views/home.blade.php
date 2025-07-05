@extends('layouts.app')

@section('title', 'Home')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">Welcome to Our Store</h1>
                <p class="lead">Discover amazing products at unbeatable prices. Shop now and enjoy fast delivery!</p>
                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-light btn-lg me-3">Shop Now</a>
                    <a href="#featured" class="btn btn-outline-light btn-lg">View Featured</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/667eea/ffffff?text=E-Commerce" 
                     class="img-fluid rounded" alt="Hero Image">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Shop by Category</h2>
                <p class="text-muted">Browse our wide selection of products</p>
            </div>
        </div>
        <div class="row g-4">
            @forelse($categories as $category)
                <div class="col-lg-3 col-md-6">
                    <div class="category-card position-relative">
                        <img src="https://via.placeholder.com/300x200/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($category->name) }}" 
                             class="img-fluid w-100" alt="{{ $category->name }}" style="height: 200px; object-fit: cover;">
                        <div class="category-overlay">
                            <div class="text-center">
                                <h5 class="mb-1">{{ $category->name }}</h5>
                                <small>{{ $category->products_count }} Products</small>
                            </div>
                        </div>
                        <a href="{{ route('products.category', $category->slug) }}" class="stretched-link"></a>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No categories available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="featured" class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Featured Products</h2>
                <p class="text-muted">Check out our most popular items</p>
            </div>
        </div>
        <div class="row g-4">
            @forelse($featuredProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                 class="card-img-top" alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/300x250/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($product->name) }}" 
                                 class="card-img-top" alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->short_description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No featured products available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Latest Products Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Latest Products</h2>
                <p class="text-muted">Discover our newest arrivals</p>
            </div>
        </div>
        <div class="row g-4">
            @forelse($latestProducts as $product)
                <div class="col-lg-3 col-md-6">
                    <div class="card product-card h-100">
                        @if($product->images && count($product->images) > 0)
                            <img src="{{ asset('storage/' . $product->images[0]) }}" 
                                 class="card-img-top" alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/300x250/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($product->name) }}" 
                                 class="card-img-top" alt="{{ $product->name }}"
                                 style="height: 250px; object-fit: cover;">
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->short_description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if($product->sale_price)
                                        <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                        <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                    @else
                                        <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-primary btn-sm w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">No products available</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection