@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Close
                </a>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Monthly Orders</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($monthlyOrders as $order)
                                <tr>
                                    <td>{{ date('F', mktime(0, 0, 0, $order->month, 1)) }}</td>
                                    <td>{{ $order->count }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No orders found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Department Usage</h4>
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Department</th>
                                <th>Total Items</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($departmentUsage as $usage)
                                <tr>
                                    <td>{{ $usage->department->name }}</td>
                                    <td>{{ $usage->total_items }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">No department usage data found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 