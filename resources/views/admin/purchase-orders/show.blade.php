@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Purchase Order Details</h4>
                    <div class="action-buttons">
                        @if($purchaseOrder->status === 'pending')
                            <form action="{{ route('admin.purchase-orders.approve', $purchaseOrder->id) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                <button type="submit" 
                                        class="btn btn-success" 
                                        onclick="return confirm('Are you sure you want to approve this purchase order?')">
                                    <i class="fas fa-check"></i> Approve Order
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('admin.purchase-orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Order Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">PO Number:</th>
                                    <td>{{ $purchaseOrder->po_number }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge bg-{{ $purchaseOrder->status === 'pending' ? 'warning' : ($purchaseOrder->status === 'approved' ? 'success' : 'danger') }}">
                                            {{ ucfirst($purchaseOrder->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Order Date:</th>
                                    <td>{{ $purchaseOrder->order_date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Expected Delivery:</th>
                                    <td>{{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-muted mb-3">Requestor Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <th width="150">Requested By:</th>
                                    <td>{{ $purchaseOrder->user->name ?? 'User Deleted' }}</td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td>{{ optional(optional($purchaseOrder->user)->department)->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Supplier:</th>
                                    <td>{{ $purchaseOrder->supplier->name ?? 'N/A' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-muted mb-3">Product Details</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $purchaseOrder->product->name ?? 'Product Deleted' }}</td>
                                        <td>{{ $purchaseOrder->quantity }}</td>
                                        <td>₱{{ number_format($purchaseOrder->product->price ?? 0, 2) }}</td>
                                        <td>₱{{ number_format(($purchaseOrder->product->price ?? 0) * $purchaseOrder->quantity, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if($purchaseOrder->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="text-muted mb-3">Notes</h5>
                                <div class="p-3 bg-light rounded">
                                    {{ $purchaseOrder->notes }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 