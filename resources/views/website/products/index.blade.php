<!-- resources/views/website/products/index.blade.php -->

@extends('website.layouts.app')

@section('title', 'Products')

@push('styles')
<style>
    .product-card {
        border: none;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
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
    }

    .category-list .list-group-item.active,
    .category-list .list-group-item:hover {
        background: #e9ecef;
        border-left: 4px solid #0d6efd;
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
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card mb-4">
            <div class="card-header bg-white fw-bold">Categories</div>
            <div class="list-group list-group-flush category-list">
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
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                                </span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm w-100 me-1">View</a>
                            @if($product->stock > 0)
                                <form action="{{ route('cart.store') }}" method="POST" class="w-100 ms-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">Add</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection
