@extends('layouts.app')

@section('content')
<style>
/* Fix Laravel pagination arrow size and custom arrow shape */
.page-link svg,
.page-link .arrow,
.page-link::before {
    width: 0.6em !important;
    height: 0.6em !important;
    vertical-align: middle;
    display: inline-block;
    font-size: 0.6em !important;
    line-height: 1;
}

/* Additional fix for large custom arrows with rounded ends */
.page-link .arrow {
    border-radius: 0.15em !important;
    background-size: contain !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
}

/* If arrows are CSS shapes, reduce their size */
.page-link::before {
    content: '';
    display: inline-block;
    width: 0.6em !important;
    height: 0.6em !important;
    border-style: solid;
    border-width: 0.15em 0.15em 0 0;
    border-color: currentColor;
    transform: rotate(135deg);
    margin-right: 0.25em;
    vertical-align: middle;
}
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Inventory Management</h4>
                    <div class="action-buttons">
                        <a href="{{ route('admin.stock.alerts') }}" class="btn btn-warning">
                            <i class="fas fa-exclamation-triangle"></i> Stock Alerts
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Close
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th>Name</th>
                                    <th>Current Stock</th>
                                    <th>Low Stock Threshold</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        <td>{{ $product->sku }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->inventory->quantity ?? 0 }}</td>
                                        <td>{{ $product->inventory->low_stock_threshold ?? $product->reorder_point }}</td>
                                        <td>
                                            @if(($product->inventory->quantity ?? 0) <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif(($product->inventory->quantity ?? 0) <= ($product->inventory->low_stock_threshold ?? $product->reorder_point))
                                                <span class="badge bg-warning">Low Stock</span>
                                            @else
                                                <span class="badge bg-success">In Stock</span>
                                            @endif
                                        </td>
                                        <td>{{ optional($product->inventory)->updated_at?->format('Y-m-d H:i:s') ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.purchase-orders.create', ['product' => $product->id]) }}" 
                                                   class="btn btn-primary btn-sm" title="Create Purchase Order">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-info btn-sm" 
                                                        title="View History"
                                                        onclick="viewHistory({{ $product->id }})">
                                                    <i class="fas fa-history"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No products found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewHistory(productId) {
    // Implement history view functionality
    alert('History view not implemented yet');
}
</script>
@endpush

@endsection 