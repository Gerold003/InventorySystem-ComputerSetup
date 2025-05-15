@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Stock Alerts</h2>
    @forelse($alerts as $alert)
        <div class="alert alert-{{ $alert->type === 'low_stock' ? 'warning' : 'danger' }}">
            <strong>{{ $alert->product->name }}</strong>: {{ $alert->message }}
        </div>
    @empty
        <div class="alert alert-info">No alerts found.</div>
    @endforelse

    {{ $alerts->links() }}
</div>
@endsection
