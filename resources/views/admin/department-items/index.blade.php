@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Assign Items to Departments</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.department-items.assign') }}" method="POST" id="assignForm">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="department_id">Select Department</label>
                            <select name="department_id" id="department_id" class="form-control @error('department_id') is-invalid @enderror" required>
                                <option value="">Choose Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-8">
                                <label>Select Item</label>
                                <select name="items[]" class="form-control item-select" required>
                                    <option value="">Choose Item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-available="{{ $product->inventory ? $product->inventory->quantity : 0 }}">
                                            {{ $product->name }} (Available: {{ $product->inventory ? $product->inventory->quantity : 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label>Quantity</label>
                                <input type="number" name="quantities[]" class="form-control quantity-input" min="1" required>
                                <small class="text-muted available-text"></small>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Assign Item
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary ms-2">
                                <i class="fas fa-times"></i> Exit to Dashboard
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h4>Current Department Assignments</h4>
                </div>
                <div class="card-body">
                    @foreach($departments as $department)
                        <h5>{{ $department->name }}</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th>Assigned Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($department->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ $item->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">No items assigned</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Update available text when item is selected
        $('.item-select').change(function() {
            const selectedOption = $(this).find('option:selected');
            const available = selectedOption.data('available');
            const quantityInput = $('.quantity-input');
            const availableText = $('.available-text');
            
            if (available !== undefined) {
                availableText.text(`Available: ${available}`);
                quantityInput.attr('max', available);
            } else {
                availableText.text('');
                quantityInput.removeAttr('max');
            }
        });

        // Validate quantity on input
        $('.quantity-input').on('input', function() {
            const select = $('.item-select');
            const available = select.find('option:selected').data('available');
            const value = parseInt($(this).val());

            if (value > available) {
                $(this).val(available);
            }
        });

        // Form validation before submit
        $('#assignForm').on('submit', function(e) {
            const select = $('.item-select');
            const quantity = $('.quantity-input');
            const available = select.find('option:selected').data('available');
            
            if (parseInt(quantity.val()) > available) {
                alert('Quantity cannot exceed available stock');
                quantity.focus();
                return false;
            }
            return true;
        });
    });
</script>
@endpush
@endsection 