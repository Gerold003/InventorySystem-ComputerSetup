@extends('website.layouts.app')

@section('title', 'Shopping Cart')

@push('styles')
<style>
    .cart-container {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
    }

    .cart-item {
        padding: 1.5rem;
        border-bottom: 1px solid #eee;
        transition: all 0.3s ease;
    }

    .cart-item.removing {
        opacity: 0;
        transform: translateX(-100%);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .product-image {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        object-fit: cover;
        padding: 0.5rem;
        background: #fff;
        border: 1px solid #eee;
        transition: transform 0.3s ease;
    }

    .product-image:hover {
        transform: scale(1.05);
    }

    .product-name {
        color: #2d3436;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .product-name:hover {
        color: #0d6efd;
    }

    .quantity-control {
        display: inline-flex;
        align-items: center;
        border: 1px solid #dee2e6;
        border-radius: 25px;
        overflow: hidden;
        background: #fff;
    }

    .quantity-btn {
        border: none;
        background: none;
        padding: 0.5rem 0.75rem;
        color: #6c757d;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #f8f9fa;
        color: #0d6efd;
    }

    .quantity-input {
        width: 50px;
        border: none;
        text-align: center;
        font-weight: 500;
        background: transparent;
    }

    .quantity-input:focus {
        outline: none;
    }

    .delete-btn {
        width: 35px;
        height: 35px;
        padding: 0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        background: #fff;
        color: #dc3545;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .delete-btn:hover {
        background: #dc3545;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }

    .cart-summary {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 1rem 0;
        border-bottom: 1px dashed #dee2e6;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .checkout-btn {
        background: #0d6efd;
        color: #fff;
        border: none;
        border-radius: 25px;
        padding: 1rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        width: 100%;
    }

    .checkout-btn:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    .empty-cart {
        text-align: center;
        padding: 3rem;
    }

    .empty-cart-icon {
        font-size: 5rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }

    .modal-confirm {
        border-radius: 15px;
        overflow: hidden;
    }

    .modal-confirm .modal-header {
        background: #dc3545;
        color: #fff;
        border: none;
    }

    .modal-confirm .modal-footer {
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }
</style>
@endpush

@section('content')
<div class="container py-5">
    <div class="row">
        @if($cartItems->count() > 0)
            <div class="col-lg-8">
                <div class="cart-container mb-4">
                    <div class="p-4 bg-light border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h4 mb-0">
                                <i class="fas fa-shopping-cart me-2"></i>Shopping Cart
                                <span class="badge bg-primary ms-2">{{ $cartItems->count() }} items</span>
                            </h2>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                            </a>
                        </div>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item" id="cart-item-{{ $item->id }}">
                            <div class="d-flex align-items-center">
                                <img src="{{ $item->product->image ? asset('storage/'.$item->product->image) : asset('images/placeholder.png') }}"
                                     alt="{{ $item->product->name }}" 
                                     class="product-image me-4">
                                
                                <div class="flex-grow-1">
                                    <a href="{{ route('products.show', $item->product) }}" class="product-name h5 mb-1 d-block">
                                        {{ $item->product->name }}
                                    </a>
                                    <p class="text-muted mb-2">{{ $item->product->category->name }}</p>
                                    
                                    <div class="d-flex align-items-center">
                                        <div class="quantity-control me-4">
                                            <button type="button" class="quantity-btn" onclick="updateQuantity({{ $item->id }}, 'decrease')">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   id="quantity-{{ $item->id }}"
                                                   value="{{ $item->quantity }}"
                                                   min="1" 
                                                   max="{{ $item->product->inventory->quantity ?? 0 }}"
                                                   class="quantity-input"
                                                   onchange="updateQuantity({{ $item->id }}, 'input')">
                                            <button type="button" class="quantity-btn" onclick="updateQuantity({{ $item->id }}, 'increase')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="ms-auto text-end">
                                            <div class="text-primary h5 mb-1">${{ number_format($item->product->price * $item->quantity, 2) }}</div>
                                            <small class="text-muted">${{ number_format($item->product->price, 2) }} each</small>
                                        </div>
                                        
                                        <button type="button" 
                                                class="delete-btn ms-4"
                                                onclick="confirmDelete({{ $item->id }}, '{{ $item->product->name }}')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="col-lg-4">
                <div class="cart-summary">
                    <h3 class="h5 mb-4">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-bold">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="text-muted">Tax ({{ config('cart.tax_rate') }}%)</span>
                        <span id="tax-amount">${{ number_format($tax, 2) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold h4 mb-0" id="total-amount">${{ number_format($total, 2) }}</span>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('checkout.index') }}" class="checkout-btn btn">
                            Proceed to Checkout
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p class="text-muted">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-confirm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Removal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="mb-1">Are you sure you want to remove this item?</p>
                <p class="text-muted mb-0" id="deleteItemName"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Remove Item</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateQuantity(itemId, action) {
    const quantityInput = document.getElementById(`quantity-${itemId}`);
    let currentQty = parseInt(quantityInput.value);
    const maxQty = parseInt(quantityInput.getAttribute('max'));
    
    if (action === 'increase') {
        if (currentQty < maxQty) currentQty++;
    } else if (action === 'decrease') {
        if (currentQty > 1) currentQty--;
    } else if (action === 'input') {
        currentQty = Math.min(Math.max(1, currentQty), maxQty);
    }
    
    quantityInput.value = currentQty;
    
    // Send AJAX request to update cart
    fetch(`/cart/update/${itemId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            quantity: currentQty
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update item total
            const itemTotal = document.querySelector(`#cart-item-${itemId} .text-primary`);
            itemTotal.textContent = `$${(data.item_price * currentQty).toFixed(2)}`;
            
            // Update summary
            const subtotalElement = document.querySelector('.summary-row .fw-bold');
            const taxElement = document.querySelector('#tax-amount');
            const totalElement = document.querySelector('#total-amount');
            
            subtotalElement.textContent = `$${data.subtotal.toFixed(2)}`;
            taxElement.textContent = `$${data.tax.toFixed(2)}`;
            totalElement.textContent = `$${data.total.toFixed(2)}`;
            
            // Update cart count in header if exists
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) cartCount.textContent = data.cart_count;
        }
    })
    .catch(error => {
        console.error('Error updating cart:', error);
        alert('Failed to update cart. Please try again.');
    });
}

function confirmDelete(itemId, productName) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    document.getElementById('deleteItemName').textContent = productName;
    
    // Set up the confirm button click handler
    document.getElementById('confirmDeleteBtn').onclick = function() {
        modal.hide();
        removeFromCart(itemId);
    };
    
    modal.show();
}

function removeFromCart(itemId) {
    const cartItem = document.getElementById(`cart-item-${itemId}`);
    cartItem.classList.add('removing');
    
    fetch(`/cart/remove/${itemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            setTimeout(() => {
                cartItem.remove();
                
                // Update summary
                const subtotalElement = document.querySelector('.summary-row .fw-bold');
                const taxElement = document.querySelector('#tax-amount');
                const totalElement = document.querySelector('#total-amount');
                
                if (data.cart_count > 0) {
                    subtotalElement.textContent = `$${data.subtotal.toFixed(2)}`;
                    taxElement.textContent = `$${data.tax.toFixed(2)}`;
                    totalElement.textContent = `$${data.total.toFixed(2)}`;
                    
                    // Update cart count in header if exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) cartCount.textContent = data.cart_count;
                } else {
                    // If cart is empty, reload page to show empty cart message
                    window.location.reload();
                }
            }, 300);
        }
    })
    .catch(error => {
        console.error('Error removing item:', error);
        alert('Failed to remove item. Please try again.');
        cartItem.classList.remove('removing');
    });
}
</script>
@endpush
@endsection