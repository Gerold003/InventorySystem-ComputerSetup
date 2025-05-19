@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Column -->
        <div class="col-md-3">
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-primary text-white py-3 rounded-top">
                    <h6 class="m-0 font-weight-semibold">
                        <i class="fas fa-cubes me-2"></i> Inventory Navigation
                    </h6>
                </div>
                <div class="card-body px-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('inventory.products.index') }}" class="list-group-item list-group-item-action d-flex align-items-center hover-highlight">
                            <i class="fas fa-boxes text-primary me-2"></i>
                            <span>Products</span>
                        </a>
                        <a href="{{ route('inventory.purchase-orders.index') }}" class="list-group-item list-group-item-action d-flex align-items-center hover-highlight">
                            <i class="fas fa-clipboard-list text-primary me-2"></i>
                            <span>Purchase Orders</span>
                        </a>
                        <a href="{{ route('inventory.stock.alerts') }}" class="list-group-item list-group-item-action d-flex align-items-center hover-highlight">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            <span>Stock Alerts</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .list-group-item {
                transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
                border: none;
            }
        
            .list-group-item:hover {
                background-color: #f8f9fa;
                transform: translateX(3px);
            }
        
        
            .card-header h6 {
                font-size: 1rem;
                font-weight: 600;
            }
        </style>
        

        <!-- Main Content Column -->
        <div class="col-md-9">
            <div class="row mb-4">
                <div class="col-md-12">
                    <h1 class="mb-0">Inventory Dashboard</h1>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Products</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Low Stock Items</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockProducts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Pending Orders</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingPurchaseOrders }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-danger shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                        Active Alerts</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $alerts }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-bell fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Alerts -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Alerts</h6>
                            <a href="{{ route('inventory.stock.alerts') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            @forelse($recentAlerts as $alert)
                            <div class="alert alert-{{ $alert->type === 'low_stock' ? 'warning' : 'danger' }} alert-dismissible fade show mb-3">
                                <strong>{{ $alert->product->name }}</strong> - {{ $alert->message }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            @empty
                            <div class="alert alert-info">No recent alerts</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent Purchase Orders -->
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Recent Purchase Orders</h6>
                            <a href="{{ route('inventory.purchase-orders.index') }}" class="btn btn-sm btn-primary">View All</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>PO #</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentPurchaseOrders as $po)
                                        <tr>
                                            <td><a href="{{ route('inventory.purchase-orders.show', $po) }}">{{ $po->po_number }}</a></td>
                                            <td>{{ $po->supplier ? $po->supplier->name : 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ 
                                                    $po->status === 'pending' ? 'warning text-dark' :  
                                                    ($po->status === 'approved' ? 'info text-dark' : 
                                                    ($po->status === 'delivered' ? 'success' : 'secondary')) 
                                                }}">
                                                    {{ ucfirst($po->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $po->order_date->format('m/d/Y') }}</td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No purchase orders found</td>
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
    </div>
</div>
@endsection