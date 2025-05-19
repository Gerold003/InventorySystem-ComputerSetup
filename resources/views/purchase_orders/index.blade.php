@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Purchase Orders</h1>
        <a href="{{ route('purchase-orders.create') }}" class="btn btn-primary btn-icon-split shadow-sm">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="d-none d-md-inline-block">Create New PO</span>
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pending Approvals</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="purchaseOrdersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>PO Number</th>
                            <th>Supplier</th>
                            <th>Order Date</th>
                            <th>Total Items</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchaseOrders as $po)
                        <tr>
                            <td>{{ $po->po_number }}</td>
                            <td>{{ $po->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $po->order_date->format('M d, Y') }}</td>
                            <td>{{ $po->items->count() }}</td>
                            <td>
                                <span class="badge badge-{{ 
                                    $po->status === 'pending' ? 'warning' : 
                                    ($po->status === 'approved' ? 'success' : 'secondary') 
                                }}">
                                    {{ ucfirst($po->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('purchase-orders.show', $po) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($po->status === 'pending' && auth()->user()->role === 'admin')
                                <form action="{{ route('purchase-orders.approve', $po) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm btn-success" 
                                        onclick="return confirm('Approve this purchase order?')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#purchaseOrdersTable').DataTable({
            responsive: true,
            order: [[2, 'desc']]
        });
    });
</script>
@endsection