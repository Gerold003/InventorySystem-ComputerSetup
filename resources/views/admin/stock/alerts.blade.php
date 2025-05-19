@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Stock Alerts</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Item Code</th>
                                    <th>Name</th>
                                    <th>Current Stock</th>
                                    <th>Minimum Stock</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lowStockItems as $item)
                                    <tr class="{{ $item->inventory->quantity <= $item->inventory->low_stock_threshold ? 'table-danger' : 'table-warning' }}">
                                        <td>{{ $item->sku }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->inventory->quantity }}</td>
                                        <td>{{ $item->inventory->low_stock_threshold }}</td>
                                        <td>
                                            @if($item->inventory->quantity <= 0)
                                                <span class="badge bg-danger">Out of Stock</span>
                                            @elseif($item->inventory->quantity <= $item->inventory->low_stock_threshold)
                                                <span class="badge bg-warning text-dark">Low Stock</span>
                                            @endif
                                        </td>
                                        <td>{{ $item->inventory->updated_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <a href="{{ route('admin.purchase-orders.create', ['product' => $item->id]) }}" class="btn btn-primary btn-sm">
                                                Create PO
                                            </a>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#stockHistory{{ $item->id }}">
                                                View History
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Stock History Modal -->
                                    <div class="modal fade" id="stockHistory{{ $item->id }}" tabindex="-1" role="dialog" aria-labelledby="stockHistoryLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="stockHistoryLabel{{ $item->id }}">Stock History - {{ $item->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Date</th>
                                                                <th>Type</th>
                                                                <th>Quantity</th>
                                                                <th>Reference</th>
                                                                <th>User</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse($item->stockMovements()->with('user')->latest()->take(10)->get() as $movement)
                                                                <tr>
                                                                    <td>{{ $movement->created_at->format('Y-m-d H:i:s') }}</td>
                                                                    <td>{{ ucfirst($movement->type) }}</td>
                                                                    <td>{{ $movement->quantity }}</td>
                                                                    <td>{{ $movement->reference }}</td>
                                                                    <td>{{ $movement->user->name }}</td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="5" class="text-center">No stock movements found</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('admin.dashboard') }}'">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No low stock items found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 