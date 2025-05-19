@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Edit Purchase Order</h2>
        
        <div class="d-flex align-items-center gap-2">
            <!-- Cancel Button -->
            <button type="button" 
                    class="btn btn-primary btn-icon-split shadow-sm"
                    onclick="window.history.back()"
                    data-toggle="tooltip" 
                    data-placement="bottom" 
                    title="Cancel and go back">
                <span class="icon text-white bg-gradient-primary">
                    <i class="fas fa-times"></i>
                </span>
                <span class="d-none d-md-inline-block text-white font-weight-medium">
                    Cancel
                </span>
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('inventory.purchase-orders.update', $purchaseOrder) }}">
        @csrf @method('PUT')
        <div class="card shadow mb-4">
            <div class="card-body">
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
            </div>
            <div class="card-footer bg-white d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary btn-icon-split shadow-sm">
                    <span class="icon text-white-50 bg-gradient-primary">
                        <i class="fas fa-save"></i>
                    </span>
                    <span class="d-none d-md-inline-block font-weight-medium">
                        Update 
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
@endsection