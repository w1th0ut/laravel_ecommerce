@extends('layouts.app')

@section('title', $category->name . ' Products')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <!-- Category Header -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <h2>{{ $category->name }}</h2>
                    @if($category->description)
                        <p class="text-muted">{{ $category->description }}</p>
                    @endif
                    <p class="text-muted">{{ $products->total() }} products found</p>
                </div>
                <div class="col-md-4">
                    <div class="d-flex justify-content-end">
                        <select class="form-select" id="sortSelect" onchange="applySorting()" style="width: auto;">
                            <option value="latest">Latest</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="name">Name A-Z</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="row g-4">
                @forelse($products as $product)
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
                                <p class="card-text text-muted small flex-grow-1">
                                    {{ Str::limit($product->short_description, 80) }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        @if($product->sale_price)
                                            <span class="text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                            <small class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</small>
                                        @else
                                            <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    @if($product->stock_quantity > 0)
                                        <span class="badge bg-success">In Stock</span>
                                    @else
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @endif
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    @if($product->stock_quantity > 0)
                                        <form action="{{ route('cart.add', $product) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                Add to Cart
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-secondary btn-sm w-100" disabled>
                                            Out of Stock
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                        <h4>No products found in this category</h4>
                        <p class="text-muted">This category doesn't have any products yet.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            Browse All Products
                        </a>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function applySorting() {
    const sort = document.getElementById('sortSelect').value;
    const url = new URL(window.location);
    url.searchParams.set('sort', sort);
    window.location.href = url.toString();
}

// Set selected sort option based on URL parameter
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const sort = urlParams.get('sort');
    if (sort) {
        document.getElementById('sortSelect').value = sort;
    }
});
</script>
@endsection