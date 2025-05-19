@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Purchase Orders</h4>
                    <div class="action-buttons">
                        <a href="{{ route('admin.purchase-orders.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> New Purchase Order
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Close
                        </a>
                    </div>
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
                                    <th>ID</th>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Requested By</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($purchaseOrders as $po)
                                    <tr>
                                        <td>{{ $po->id }}</td>
                                        <td>{{ $po->product->name ?? 'Product Deleted' }}</td>
                                        <td>{{ $po->quantity }}</td>
                                        <td>₱{{ number_format($po->total_amount ?? 0, 2) }}</td>
                                        <td>{{ $po->user->name ?? 'User Deleted' }}</td>
                                        <td>{{ optional(optional($po->user)->department)->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $po->status === 'pending' ? 'warning' : ($po->status === 'approved' ? 'success' : 'danger') }}">
                                                {{ ucfirst($po->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $po->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if($po->status === 'pending')
                                                    <form action="{{ route('admin.purchase-orders.approve', $po->id) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="btn btn-success btn-sm" 
                                                                title="Approve Order"
                                                                onclick="return confirm('Are you sure you want to approve this purchase order?')">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <a href="{{ route('admin.purchase-orders.show', $po->id) }}" 
                                                   class="btn btn-info btn-sm" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">No purchase orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $purchaseOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 