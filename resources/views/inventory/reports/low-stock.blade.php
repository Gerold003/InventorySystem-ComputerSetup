@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Low Stock Report</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Reorder Point</th>
                            <th>Supplier</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->inventory->quantity ?? 0 }}</td>
                            <td>{{ $product->reorder_point }}</td>
                            <td>{{ $product->supplier->name ?? 'N/A' }}</td>
                            <td><span class="badge bg-warning">Low Stock</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection 