@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Purchase Order Details</h2>
    <p><strong>PO Number:</strong> {{ $purchaseOrder->po_number }}</p>
    <p><strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}</p>
    <p><strong>Status:</strong> {{ ucfirst($purchaseOrder->status) }}</p>
    <p><strong>Date:</strong> {{ $purchaseOrder->order_date->format('m/d/Y') }}</p>
</div>
@endsection
