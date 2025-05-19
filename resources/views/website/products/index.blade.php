<!-- resources/views/website/products/index.blade.php -->

@extends('website.layouts.app')

@section('title', 'Products')

@push('styles')
<style>
    .product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
    }

    .product-img {
        height: 200px;
        object-fit: contain;
        background: #f8f9fa;
        padding: 1rem;
    }

    .category-list .list-group-item {
        border: none;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
        padding: 0.75rem 1rem;
        color: #666;
    }

    .category-list .list-group-item:hover {
        background-color: rgba(33, 37, 41, 0.1);
        border-left-color: #212529;
        color: #212529;
    }

    .category-list .list-group-item.active {
        background-color: rgba(33, 37, 41, 0.1);
        border-left-color: #212529;
        color: #212529;
        font-weight: 500;
    }

    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }

    .badge-discount {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #dc3545;
        color: #fff;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        border-radius: 5px;
    }

    .btn-action {
        flex: 1;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border-radius: 8px;
        padding: 0.5rem;
    }

    .btn-action i {
        font-size: 1rem;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-view {
        background-color: #fff;
        border: 1px solid #0d6efd;
        color: #0d6efd;
    }

    .btn-view:hover {
        background-color: #0d6efd;
        color: #fff;
    }

    .btn-add {
        background-color: #0d6efd;
        border: 1px solid #0d6efd;
        color: #fff;
    }

    .btn-add:hover {
        background-color: #0b5ed7;
        border-color: #0b5ed7;
    }

    /* Pagination Styles */
    .pagination-btn {
        padding: 8px 16px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .pagination-btn:hover:not(.disabled) {
        transform: translateY(-2px);
    }

    .pagination-numbers {
        display: flex;
        gap: 5px;
    }

    .pagination-numbers .btn {
        min-width: 40px;
        height: 38px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-white fw-bold">Categories</div>
            <div class="list-group list-group-flush category-list">
                <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action {{ !request()->is('categories/*') ? 'active' : '' }}">
                    All Products
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.category', $category) }}" class="list-group-item list-group-item-action {{ request()->is('categories/'.$category->id) ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-white fw-bold">Filters</div>
            <div class="card-body">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="form-group mb-2">
                        <label for="price_min">Min Price</label>
                        <input type="number" class="form-control" id="price_min" name="price_min" value="{{ request('price_min') }}">
                    </div>
                    <div class="form-group mb-3">
                        <label for="price_max">Max Price</label>
                        <input type="number" class="form-control" id="price_max" name="price_max" value="{{ request('price_max') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    @if(request()->has('price_min') || request()->has('price_max'))
                        <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Clear Filters</a>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <div class="row mb-4 align-items-center">
            <div class="col-md-6">
                <h2>All Products</h2>
            </div>
            <div class="col-md-6 text-end">
                <form method="GET" action="{{ route('products.index') }}">
                    <div class="form-group">
                        <select class="form-select" name="sort" onchange="this.form.submit()">
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price (Low to High)</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price (High to Low)</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="product-card card h-100 position-relative">
                        @if($product->discount > 0)
                            <div class="badge-discount">-{{ $product->discount }}%</div>
                        @endif
                        <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/placeholder.png') }}" class="card-img-top product-img" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ Str::limit($product->name, 40) }}</h5>
                            <div class="d-flex justify-content-between align-items-center">
                                @if($product->discount > 0)
                                    <div>
                                        <span class="text-danger fw-bold">${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                                        <small class="text-decoration-line-through text-muted ms-2">${{ number_format($product->price, 2) }}</small>
                                    </div>
                                @else
                                    <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                @endif
                                <span class="badge bg-{{ $product->current_stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->current_stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex gap-2">
                            <a href="{{ route('products.show', $product) }}" 
                               class="btn-action btn-view" 
                               title="View Product">
                                <i class="fas fa-search"></i>
                            </a>
                            @if($product->current_stock > 0)
                                <form action="{{ route('cart.store') }}" method="POST" class="flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" 
                                            class="btn-action btn-add w-100" 
                                            title="Add to Cart">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
            </div>
            <div class="d-flex gap-3 align-items-center">
                @if($products->onFirstPage())
                    <span class="pagination-btn btn btn-secondary disabled">« Previous</span>
                @else
                    <a href="{{ $products->previousPageUrl() }}" class="pagination-btn btn btn-primary">« Previous</a>
                @endif

                <div class="pagination-numbers">
                    @for($i = 1; $i <= $products->lastPage(); $i++)
                        <a href="{{ $products->url($i) }}" 
                           class="btn {{ $products->currentPage() == $i ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $i }}
                        </a>
                    @endfor
                </div>

                @if($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}" class="pagination-btn btn btn-primary">Next »</a>
                @else
                    <span class="pagination-btn btn btn-secondary disabled">Next »</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
