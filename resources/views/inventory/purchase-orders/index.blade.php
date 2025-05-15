@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-file-invoice me-2"></i>Purchase Orders</h2>
        <a href="{{ route('inventory.purchase-orders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Create New
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>PO Number</th>
                        <th>Supplier</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Delivery Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($purchaseOrders as $po)
                    <tr>
                        <td class="fw-bold">{{ $po->po_number }}</td>
                        <td>{{ $po->supplier->name ?? '<span class="text-muted">Supplier deleted</span>' }}</td>
                        <td>
                            <span class="badge status-{{ $po->status }}">
                                {{ ucfirst($po->status) }}
                            </span>
                        </td>
                        <td>{{ $po->order_date?->format('M d, Y') ?? 'N/A' }}</td>
                        <td>{{ $po->expected_delivery_date?->format('M d, Y') ?? 'N/A' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                <a href="{{ route('inventory.purchase-orders.show', $po) }}" 
                                   class="btn btn-sm btn-outline-primary"
                                   title="View details">
                                   <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($po->status === 'pending')
                                <a href="{{ route('inventory.purchase-orders.edit', $po) }}" 
                                   class="btn btn-sm btn-outline-secondary"
                                   title="Edit">
                                   <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                
                                <form method="POST" action="{{ route('inventory.purchase-orders.destroy', $po) }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-sm btn-outline-danger"
                                            title="Delete"
                                            onclick="return confirm('This will permanently delete the purchase order. Continue?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <div class="text-muted mb-2">
                                <i class="fas fa-box-open fa-2x"></i>
                            </div>
                            No purchase orders found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $purchaseOrders->links() }}
    </div>
</div>

<style>
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-approved {
        background-color: #d4edda;
        color: #155724;
    }
    .status-delivered {
        background-color: #d1ecf1;
        color: #0c5460;
    }
    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }
    .badge {
        padding: 0.35em 0.65em;
        font-weight: 500;
    }
</style>
@endsection