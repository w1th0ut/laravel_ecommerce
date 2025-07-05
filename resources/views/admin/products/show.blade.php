@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Product Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($product->images && count($product->images) > 0)
                                <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        @foreach($product->images as $index => $image)
                                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     class="d-block w-100 rounded" alt="{{ $product->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                    @if(count($product->images) > 1)
                                        <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                                            <span class="carousel-control-next-icon"></span>
                                        </button>
                                    @endif
                                </div>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 300px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-6">
                            <h3>{{ $product->name }}</h3>
                            <p class="text-muted">{{ $product->short_description }}</p>
                            
                            <div class="mb-3">
                                <strong>Category:</strong> 
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </div>
                            
                            <div class="mb-3">
                                <strong>SKU:</strong> <code>{{ $product->sku }}</code>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Price:</strong>
                                @if($product->sale_price)
                                    <span class="h5 text-danger fw-bold">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-muted text-decoration-line-through ms-2">${{ number_format($product->price, 2) }}</span>
                                    <span class="badge bg-danger ms-2">
                                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                    </span>
                                @else
                                    <span class="h5 fw-bold">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Stock:</strong>
                                @if($product->stock_quantity > 0)
                                    <span class="badge bg-success">{{ $product->stock_quantity }} in stock</span>
                                @else
                                    <span class="badge bg-danger">Out of stock</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                                
                                @if($product->is_featured)
                                    <span class="badge bg-warning">Featured</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Created:</strong> {{ $product->created_at->format('M d, Y H:i') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Last Updated:</strong> {{ $product->updated_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-4">
                        <h5>Description</h5>
                        <p>{{ $product->description }}</p>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Product
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Products
                        </a>
                        <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View on Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Quick Stats</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $product->orderItems()->sum('quantity') ?? 0 }}</h4>
                                <small class="text-muted">Total Sold</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">${{ number_format($product->orderItems()->sum('total') ?? 0, 2) }}</h4>
                            <small class="text-muted">Revenue</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Related Products</h6>
                </div>
                <div class="card-body">
                    @php
                        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                                                             ->where('id', '!=', $product->id)
                                                             ->where('is_active', true)
                                                             ->take(3)
                                                             ->get();
                    @endphp
                    
                    @forelse($relatedProducts as $related)
                        <div class="d-flex align-items-center mb-3">
                            @if($related->images && count($related->images) > 0)
                                <img src="{{ asset('storage/' . $related->images[0]) }}" 
                                     class="rounded me-3" alt="{{ $related->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $related->name }}</h6>
                                <small class="text-muted">${{ number_format($related->current_price, 2) }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">No related products found.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection