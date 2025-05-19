<!-- resources/views/website/products/show.blade.php -->

@extends('website.layouts.app')

@section('title', $product->name)

@push('styles')
<style>
    .product-image {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: #fff;
        padding: 1rem;
    }

    .product-details {
        padding: 2rem;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        background: #f8f9fa;
        color: #212529;
        text-decoration: none;
        transition: all 0.3s ease;
        border: 1px solid #dee2e6;
    }

    .back-button i {
        margin-right: 8px;
    }

    .back-button:hover {
        background: #e9ecef;
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .product-title {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #212529;
    }

    .product-category {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .product-price {
        font-size: 2rem;
        font-weight: 700;
        color: #212529;
        margin-bottom: 1.5rem;
    }

    .stock-badge {
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .specs-list {
        list-style: none;
        padding: 0;
    }

    .specs-list li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
    }

    .quantity-input {
        width: 100px;
        text-align: center;
        border-radius: 25px;
        border: 1px solid #dee2e6;
        padding: 0.5rem;
    }

    .add-to-cart-btn {
        border-radius: 25px;
        padding: 0.75rem 2rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
    }

    .add-to-cart-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .related-title {
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #eee;
    }

    .related-product-card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .related-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-4 justify-content-end">
        <a href="{{ route('products.index') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            <span>Back to Products</span>
        </a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/placeholder.png') }}" 
                 class="img-fluid product-image" 
                 alt="{{ $product->name }}">
        </div>
        <div class="col-md-6 product-details">
            <h1 class="product-title">{{ $product->name }}</h1>
            <p class="product-category">
                <i class="fas fa-tag me-2"></i>{{ $product->category->name }}
            </p>
            <h3 class="product-price">${{ number_format($product->price, 2) }}</h3>
            
            @if($product->current_stock > 0)
                <span class="stock-badge bg-success text-white">
                    <i class="fas fa-check-circle me-1"></i>
                    In Stock ({{ $product->current_stock }} available)
                </span>
            @else
                <span class="stock-badge bg-danger text-white">
                    <i class="fas fa-times-circle me-1"></i>
                    Out of Stock
                </span>
            @endif
            
            <div class="mt-4">
                <h4 class="mb-3">Description</h4>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
            
            <div class="mt-4">
                <h4 class="mb-3">Specifications</h4>
                <ul class="specs-list">
                    <li>
                        <span class="text-muted">SKU</span>
                        <span class="fw-bold">{{ $product->sku }}</span>
                    </li>
                    <!-- Add more specifications as needed -->
                </ul>
            </div>
            
            <div class="mt-4">
                @if($product->current_stock > 0)
                    <form action="{{ route('cart.store') }}" method="POST" class="d-flex align-items-center gap-3">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="form-group mb-0">
                            <input type="number" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="{{ $product->current_stock }}" 
                                   class="form-control quantity-input">
                        </div>
                        <button type="submit" class="btn btn-primary add-to-cart-btn flex-grow-1">
                            <i class="fas fa-shopping-cart me-2"></i>Add to Cart
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary add-to-cart-btn w-100" disabled>
                        <i class="fas fa-times-circle me-2"></i>Out of Stock
                    </button>
                    <div class="text-center mt-3">
                        <a href="#" class="text-muted text-decoration-none">
                            <i class="fas fa-bell me-1"></i>Notify me when available
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($relatedProducts->count() > 0)
    <div class="mt-5">
        <h3 class="related-title">Related Products</h3>
        <div class="row">
            @foreach($relatedProducts as $related)
                <div class="col-md-3 mb-4">
                    <div class="card related-product-card h-100">
                        <img src="{{ $related->image ? asset('storage/'.$related->image) : asset('images/placeholder.png') }}" 
                             class="card-img-top p-3" 
                             alt="{{ $related->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $related->name }}</h5>
                            <p class="card-text fw-bold">${{ number_format($related->price, 2) }}</p>
                            <a href="{{ route('products.show', $related) }}" 
                               class="btn btn-outline-primary w-100">
                                <i class="fas fa-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
