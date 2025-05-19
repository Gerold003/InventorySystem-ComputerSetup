@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice me-2"></i>Purchase Order #{{ $purchaseOrder->po_number }}
                    </h4>
                    <button type="button" 
                            class="btn-close btn-close-white p-2" 
                            aria-label="Close"
                            onclick="window.history.back()"></button>
                </div>
                
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Supplier</dt>
                                <dd class="fs-5">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</dd>
                            </div>
                            
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Order Date</dt>
                                <dd class="fs-5">
                                    {{ $purchaseOrder->order_date->format('M d, Y') }}
                                </dd>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Expected Delivery</dt>
                                <dd class="fs-5">
                                    {{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}
                                </dd>
                            </div>
                            
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Status</dt>
                                <dd class="fs-5">
                                    <span class="badge badge-{{ 
                                        $purchaseOrder->status === 'pending' ? 'warning' : 
                                        ($purchaseOrder->status === 'approved' ? 'success' : 'secondary') 
                                    }}">
                                        {{ ucfirst($purchaseOrder->status) }}
                                    </span>
                                </dd>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($purchaseOrder->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>${{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    @if($purchaseOrder->status === 'pending' && auth()->user()->role === 'admin')
                    <div class="mt-4 border-top pt-4">
                        <h5 class="mb-3">Approval Actions</h5>
                        <form action="{{ route('purchase-orders.approve', $purchaseOrder) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-icon-split shadow-sm">
                                <span class="icon text-white bg-gradient-success">
                                    <i class="fas fa-check"></i>
                                </span>
                                <span class="d-none d-md-inline-block font-weight-medium">
                                    Approve Purchase Order
                                </span>
                            </button>
                            
                            <a href="{{ route('purchase-orders.edit', $purchaseOrder) }}" 
                               class="btn btn-warning btn-icon-split shadow-sm ml-2">
                                <span class="icon text-white bg-gradient-warning">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="d-none d-md-inline-block font-weight-medium">
                                    Modify PO
                                </span>
                            </a>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection