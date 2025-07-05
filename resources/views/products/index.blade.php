@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>All Products</h2>
                <div class="d-flex gap-2">
                    <select class="form-select" id="sortSelect" onchange="applySorting()">
                        <option value="latest">Latest</option>
                        <option value="price_low">Price: Low to High</option>
                        <option value="price_high">Price: High to Low</option>
                        <option value="name">Name A-Z</option>
                    </select>
                </div>
            </div>
            
            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('products.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search products..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="{{ route('products.index') }}">
                        <select name="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->slug }}" 
                                        {{ request('category') == $category->slug ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
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
                                    <span class="badge bg-secondary">{{ $product->category->name }}</span>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                    <form action="{{ route('cart.add', $product) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit" class="btn btn-primary btn-sm w-100">
                                            Add to Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4>No products found</h4>
                        <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
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