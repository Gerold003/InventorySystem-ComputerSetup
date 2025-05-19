@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">User Management</h4>
                    <div class="action-buttons">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> New User
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Close
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th width="180">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                                            $user->role == 'admin' ? 'danger' : 
                                            ($user->role == 'inventory' ? 'primary' : 
                                            ($user->role == 'sales' ? 'info' : 'secondary')) 
                                        }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="btn btn-info btn-sm" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="btn btn-warning btn-sm" title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        title="Delete User"
                                                        onclick="return confirm('Are you sure? This cannot be undone.')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} results
                        </div>
                        <div class="d-flex gap-2">
                            @if($users->onFirstPage())
                                <span class="btn btn-secondary disabled">« Previous</span>
                            @else
                                <a href="{{ $users->previousPageUrl() }}" class="btn btn-primary">« Previous</a>
                            @endif

                            <div class="d-flex gap-1">
                                @for($i = 1; $i <= $users->lastPage(); $i++)
                                    <a href="{{ $users->url($i) }}" 
                                       class="btn {{ $users->currentPage() == $i ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>

                            @if($users->hasMorePages())
                                <a href="{{ $users->nextPageUrl() }}" class="btn btn-primary">Next »</a>
                            @else
                                <span class="btn btn-secondary disabled">Next »</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .btn.disabled {
        pointer-events: none;
        opacity: 0.65;
    }
    
    .gap-1 {
        gap: 0.25rem !important;
    }
    
    .gap-2 {
        gap: 0.5rem !important;
    }
</style>
@endsection