@extends('layouts.app')

@section('title', 'Category Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Category Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $category->name }}</h5>
                            <p class="text-muted">{{ $category->description ?: 'No description provided.' }}</p>
                            
                            <div class="mb-3">
                                <strong>Slug:</strong> <code>{{ $category->slug }}</code>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Status:</strong>
                                @if($category->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </div>
                            
                            <div class="mb-3">
                                <strong>Products:</strong> {{ $category->products->count() }} products
                            </div>
                            
                            <div class="mb-3">
                                <strong>Created:</strong> {{ $category->created_at->format('M d, Y H:i') }}
                            </div>
                            
                            <div class="mb-3">
                                <strong>Last Updated:</strong> {{ $category->updated_at->format('M d, Y H:i') }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Category
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                        <a href="{{ route('products.category', $category->slug) }}" class="btn btn-outline-info" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View on Website
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Recent Products in this Category</h6>
                </div>
                <div class="card-body">
                    @if($category->products->count() > 0)
                        @foreach($category->products as $product)
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://via.placeholder.com/50x50/{{ sprintf('%06X', mt_rand(0, 0xFFFFFF)) }}/ffffff?text={{ urlencode($product->name) }}" 
                                     class="rounded me-3" alt="{{ $product->name }}">
                                <div>
                                    <h6 class="mb-1">{{ $product->name }}</h6>
                                    <small class="text-muted">${{ number_format($product->current_price, 2) }}</small>
                                </div>
                            </div>
                        @endforeach
                        
                        @if($category->products->count() >= 10)
                            <a href="{{ route('admin.products.index', ['category' => $category->slug]) }}" class="btn btn-sm btn-outline-primary w-100">
                                View All Products
                            </a>
                        @endif
                    @else
                        <p class="text-muted text-center">No products in this category yet.</p>
                        <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-sm btn-primary w-100">
                            Add First Product
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection