@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Admin Dashboard</h2>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ \App\Models\Product::count() }}</h4>
                                    <p class="mb-0">Total Products</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-box fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ \App\Models\Category::count() }}</h4>
                                    <p class="mb-0">Categories</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-tags fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ \App\Models\Order::count() }}</h4>
                                    <p class="mb-0">Total Orders</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shopping-cart fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ \App\Models\User::count() }}</h4>
                                    <p class="mb-0">Total Users</p>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-3">Quick Actions</h4>
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                                    <h5>Manage Products</h5>
                                    <p class="text-muted">Add, edit, or delete products</p>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Manage Products</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-tags fa-3x text-success mb-3"></i>
                                    <h5>Manage Categories</h5>
                                    <p class="text-muted">Add, edit, or delete categories</p>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-success">Manage Categories</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-chart-bar fa-3x text-info mb-3"></i>
                                    <h5>View Orders</h5>
                                    <p class="text-muted">Monitor and manage orders</p>
                                    <a href="#" class="btn btn-info">View Orders</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
                                    <h5>Manage Orders</h5>
                                    <p class="text-muted">View and manage customer orders</p>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-info">Manage Orders</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection