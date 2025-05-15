@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Purchase Orders</h2>
    <a href="{{ route('inventory.purchase-orders.create') }}" class="btn btn-primary mb-3">Create Purchase Order</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>PO #</th>
                <th>Supplier</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($purchaseOrders as $po)
            <tr>
                <td>{{ $po->po_number }}</td>
                <td>{{ $po->supplier->name }}</td>
                <td>{{ ucfirst($po->status) }}</td>
                <td>{{ $po->order_date->format('m/d/Y') }}</td>
                <td>
                    <a href="{{ route('inventory.purchase-orders.show', $po) }}" class="btn btn-sm btn-info">View</a>
                    <a href="{{ route('inventory.purchase-orders.edit', $po) }}" class="btn btn-sm btn-warning">Edit</a>
                    <form method="POST" action="{{ route('inventory.purchase-orders.destroy', $po) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this order?')">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5">No purchase orders available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $purchaseOrders->links() }}
</div>
@endsection
