@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Stock Alerts</h2>
        
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('inventory.dashboard') }}" 
               class="btn btn-primary btn-icon-split shadow-sm"
               data-toggle="tooltip" 
               data-placement="bottom" 
               title="Return to Dashboard">
                <span class="icon text-white bg-gradient-primary">
                    <i class="fas fa-tachometer-alt"></i>
                </span>
                <span class="d-none d-md-inline-block text-white font-weight-medium">
                    Dashboard
                </span>
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            @forelse($alerts as $alert)
                <div class="alert alert-{{ $alert->type === 'low_stock' ? 'warning' : 'danger' }} mb-3">
                    <strong>{{ $alert->product->name }}</strong>: {{ $alert->message }}
                </div>
            @empty
                <div class="alert alert-info mb-0">No alerts found.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-4">
        {{ $alerts->links() }}
    </div>
</div>
@endsection