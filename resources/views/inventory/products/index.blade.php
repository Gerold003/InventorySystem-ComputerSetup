@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>

        
        <div class="d-flex align-items-center gap-2">
            <!-- Dashboard Button -->
            <a href="{{ route('inventory.dashboard') }}" 
               class="btn btn-primary btn-icon-split shadow-sm"
               data-toggle="tooltip" 
               data-placement="bottom" 
               title="Return to Dashboard">
                <span class="icon text-white bg-gradient-primary">
                    <i class="fas fa-tachometer-alt"></i>
                </span>
                <span class="d-none d-md-inline-block text-white font-weight-medium">
                    Dashboard
                </span>
            </a>
        
            <!-- New Product Button -->
            <a href="{{ route('inventory.products.create') }}" 
               class="btn btn-primary btn-icon-split shadow-sm"
               data-toggle="tooltip" 
               data-placement="bottom" 
               title="Create New Product">
                <span class="icon text-white bg-gradient-primary">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="d-none d-md-inline-block text-white font-weight-medium">
                    New Product
                </span>
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Product List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>SKU</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td class="text-center">
                                <div class="product-image-container">
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="product-thumbnail"
                                         onclick="showImagePreview('{{ $product->image_url }}', '{{ $product->name }}')">
                                </div>
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                {{ $product->inventory_quantity }}
                                @if($product->inventory_quantity <= $product->low_stock_threshold)
                                    <span class="badge bg-warning text-dark">Low Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_featured)
                                    <span class="badge bg-success">Featured</span>
                                @else
                                    <span class="badge bg-secondary">Regular</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inventory.products.show', $product) }}" 
                                       class="btn btn-sm btn-info"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('inventory.products.edit', $product) }}" 
                                       class="btn btn-sm btn-primary"
                                       title="Edit Product">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form id="delete-form-{{ $product->id }}" 
                                          action="{{ route('inventory.products.destroy', $product) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                title="Delete Product"
                                                onclick="confirmDelete('delete-form-{{ $product->id }}', '{{ $product->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@include('inventory.products.delete')

@endsection

@push('styles')
<style>
    .product-image-container {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #fff;
    }

    .product-thumbnail {
        width: 100%;
        height: 100%;
        object-fit: contain;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .product-thumbnail:hover {
        transform: scale(1.1);
    }

    .btn-group {
        gap: 0.25rem;
    }

    /* Fix pagination arrows size */
    .pagination svg {
        width: 1em !important;
        height: 1em !important;
    }

    /* Image Preview Modal Styles */
    .image-preview-modal {
        display: none;
        position: fixed;
        z-index: 1050;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .image-preview-content {
        margin: auto;
        display: block;
        max-width: 80%;
        max-height: 80vh;
    }

    .image-preview-close {
        position: absolute;
        right: 35px;
        top: 15px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
    }

    .image-preview-caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }
</style>

@push('scripts')
<script>
    function showImagePreview(imageSrc, productName) {
        Swal.fire({
            imageUrl: imageSrc,
            imageAlt: productName,
            title: productName,
            width: 800,
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                image: 'img-fluid'
            }
        });
    }

    $(document).ready(function() {
        $('#productsTable').DataTable({
            responsive: true,
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ],
            order: [[1, 'asc']]
        });

        // Initialize tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<!-- Add Bootstrap 4 and 5 JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
@endpush