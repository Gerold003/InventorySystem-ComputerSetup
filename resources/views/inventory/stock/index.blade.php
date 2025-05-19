@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventory Stock Management</h1>
        <div>
            <a href="{{ route('inventory.stock.low') }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-exclamation-triangle"></i>
                </span>
                <span class="text">Low Stock Items</span>
            </a>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Current Stock Levels</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="stockTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product Name</th>
                            <th>Current Stock</th>
                            <th>Reorder Point</th>
                            <th>Department</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr class="{{ $product->isLowStock() ? 'table-warning' : '' }}">
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->inventory->quantity ?? 0 }}</td>
                            <td>{{ $product->reorder_point }}</td>
                            <td>{{ $product->department->name ?? 'Unassigned' }}</td>
                            <td>
                                @if($product->isLowStock())
                                    <span class="badge badge-warning">Low Stock</span>
                                @else
                                    <span class="badge badge-success">In Stock</span>
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adjustStockModal{{ $product->id }}">
                                    Adjust Stock
                                </button>
                                @if($product->isLowStock())
                                <form action="{{ route('inventory.stock.reorder', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Reorder
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>

                        <!-- Stock Adjustment Modal -->
                        <div class="modal fade" id="adjustStockModal{{ $product->id }}" tabindex="-1" role="dialog" aria-labelledby="adjustStockModalLabel{{ $product->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{ route('inventory.stock.adjust', $product) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="adjustStockModalLabel{{ $product->id }}">Adjust Stock - {{ $product->name }}</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="quantity">Quantity Adjustment</label>
                                                <input type="number" class="form-control" id="quantity" name="quantity" required
                                                    placeholder="Enter positive number to add, negative to subtract">
                                            </div>
                                            <div class="form-group">
                                                <label for="reason">Reason for Adjustment</label>
                                                <input type="text" class="form-control" id="reason" name="reason" required
                                                    placeholder="Enter reason for adjustment">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save Adjustment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#stockTable').DataTable({
            "order": [[2, "asc"]],
            "pageLength": 25
        });
    });
</script>
@endpush
