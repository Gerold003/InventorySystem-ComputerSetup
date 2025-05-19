@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Sales Report</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>User</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stockMovements as $movement)
                        <tr>
                            <td>{{ $movement->created_at->format('Y-m-d H:i') }}</td>
                            <td>{{ $movement->product->name }}</td>
                            <td>{{ abs($movement->quantity) }}</td>
                            <td>{{ $movement->user->name }}</td>
                            <td>{{ $movement->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $stockMovements->links() }}
        </div>
    </div>
</div>
@endsection 