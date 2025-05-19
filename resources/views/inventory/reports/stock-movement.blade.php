@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Stock Movement Report</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>User</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>
                                <span class="badge bg-{{ $movement->type === 'in' ? 'success' : ($movement->type === 'out' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($movement->type) }}
                                </span>
                            </td>
                            <td>{{ abs($movement->quantity) }}</td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $movements->links() }}
        </div>
    </div>
</div>
@endsection 