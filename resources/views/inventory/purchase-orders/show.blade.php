@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-file-invoice me-2"></i>Purchase Order Details
                    </h4>
                    <button type="button" 
                            class="btn-close btn-close-white p-2" 
                            aria-label="Close"
                            onclick="window.history.back()"></button>
                </div>
                
                <div class="card-body p-4">
                    <dl class="row mb-0">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Supplier</dt>
                                <dd class="fs-5">{{ $purchaseOrder->supplier->name }}</dd>
                            </div>
                            
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Order Date</dt>
                                <dd class="fs-5">
                                    <i class="fas fa-calendar-day text-primary me-2"></i>
                                    {{ $purchaseOrder->order_date->format('M d, Y') }}
                                </dd>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <dt class="h6 text-muted mb-2">Expected Delivery</dt>
                                <dd class="fs-5">
                                    <i class="fas fa-truck text-primary me-2"></i>
                                    {{ $purchaseOrder->expected_delivery_date->format('M d, Y') }}
                                </dd>
                            </div>
                        </div>
                    </dl>

                    <!-- Approval Section -->
                    @if($purchaseOrder->status === 'pending' && auth()->user()->role === 'admin')
                    <div class="mt-4 border-top pt-4">
                        <h5 class="mb-3">Order Approval</h5>
                        <form action="{{ route('admin.purchase-orders.approve', $purchaseOrder) }}" method="POST">
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
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection