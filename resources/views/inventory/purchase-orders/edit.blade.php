@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Purchase Order</h2>
    <form method="POST" action="{{ route('inventory.purchase-orders.update', $purchaseOrder) }}">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Supplier</label>
            <select class="form-control" name="supplier_id">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $supplier->id == $purchaseOrder->supplier_id ? 'selected' : '' }}>
                        {{ $supplier->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <!-- Additional fields -->
        <button type="submit" class="btn btn-success">Update</button>
    </form>
</div>
@endsection
