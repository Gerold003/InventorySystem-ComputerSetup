@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5><i class="fas fa-people-carry"></i> Assign Inventory</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('inventory.assignments.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label>Inventory Item</label>
                        <select name="inventory_id" class="form-select" required>
                            @foreach($inventories as $inventory)
                                <option value="{{ $inventory->id }}">
                                    {{ $inventory->product->name }} ({{ $inventory->quantity }} available)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Assign To Department</label>
                        <select name="department_id" class="form-select">
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Quantity</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
                    <div class="col-md-2 align-self-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-share-square"></i> Assign
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection