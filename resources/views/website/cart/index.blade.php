@extends('website.layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<section class="py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary"><i class="fas fa-cart-shopping me-2"></i>Shopping Cart</h2>
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Continue Shopping
        </a>
    </div>

    @if($cartItems->count() > 0)
        <div class="table-responsive shadow-sm rounded bg-white p-3">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th style="width: 120px;">Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : asset('images/placeholder.png') }}"
                                         alt="{{ $item->product->name }}" class="img-thumbnail me-3" style="width: 60px; height: 60px;">
                                    <div>
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        <small class="text-muted">{{ $item->product->category->name }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>${{ number_format($item->product->price, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}"
                                           min="1" max="{{ $item->product->stock }}"
                                           class="form-control form-control-sm me-2 text-center" style="width: 60px;">
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                            </td>
                            <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            <td>
                                <form action="{{ route('cart.destroy', $item) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                        <td colspan="2">${{ number_format($subtotal, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tax ({{ config('cart.tax_rate') }}%):</td>
                        <td colspan="2">${{ number_format($tax, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end fw-bold fs-5 text-primary">Total:</td>
                        <td colspan="2" class="fs-5 text-primary">${{ number_format($total, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-4">
            <a href="{{ route('checkout.index') }}" class="btn btn-lg btn-primary">
                Proceed to Checkout <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    @else
        <div class="alert alert-info text-center">
            <i class="fas fa-info-circle me-2"></i>Your cart is empty.
            <a href="{{ route('products.index') }}" class="text-decoration-none fw-bold">Start shopping</a>.
        </div>
    @endif
</section>
@endsection