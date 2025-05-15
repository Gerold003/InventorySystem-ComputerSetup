@extends('layouts.app')

@section('content')
<style>
    /* CSS Reset */
    *, *::before, *::after {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html, body {
        height: 100%;
        font-family: 'Segoe UI', system-ui, sans-serif;
        line-height: 1.6;
    }

    :root {
        --primary: #FF7B54;
        --secondary: #4CAF50;
        --dark: #1A1A1A;
        --dark-alt: #2D2D2D;
        --light: #F8F9FA;
        --text-muted: #8B8E94;
    }

    body {
        background: linear-gradient(to bottom, 
            rgba(26, 26, 26, 0.95), 
            rgba(0, 0, 0, 0.95)), 
            url('/images/TrackNETbg.jpg') center/cover fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        color: var(--light);
    }

    .card {
        background: rgba(33, 33, 33, 0.95) !important;
        border-radius: 15px !important;
        border: 1px solid rgba(255, 255, 255, 0.15) !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5) !important;
        backdrop-filter: blur(12px);
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .card-header {
        background: linear-gradient(135deg, var(--primary), #FF6B6B) !important;
        color: var(--light) !important;
        font-size: 1.35rem;
        font-weight: 600;
        padding: 1.5rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.15) !important;
        letter-spacing: 0.75px;
    }

    .card-body {
        background: rgba(45, 45, 45, 0.6) !important;
        padding: 2rem !important;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.07) !important;
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        color: var(--light) !important;
        border-radius: 8px !important;
        padding: 0.75rem 1.25rem !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .form-control::placeholder {
        color: var(--text-muted) !important;
        opacity: 0.7 !important;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.12) !important;
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(255, 123, 84, 0.25) !important;
    }

    .form-label {
        color: var(--text-muted) !important;
        font-weight: 500 !important;
        margin-bottom: 0.5rem !important;
        font-size: 0.95rem;
    }

    .invalid-feedback {
        background: #dc3545 !important;
        color: var(--light) !important;
        padding: 0.5rem 1rem !important;
        border-radius: 6px !important;
        margin-top: 0.5rem !important;
        font-size: 0.9rem;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .invalid-feedback::before {
        content: "⚠ ";
        margin-right: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary), #FF6B6B) !important;
        border: none !important;
        padding: 0.75rem 1.75rem !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(255, 123, 84, 0.4) !important;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6C757D, #5A6268) !important;
        border: none !important;
        padding: 0.75rem 1.75rem !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 20px rgba(108, 117, 125, 0.4) !important;
    }

    .input-icon {
        position: absolute;
        right: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .card {
            margin: 1.25rem !important;
            border-radius: 12px !important;
        }
        
        .card-header {
            font-size: 1.2rem !important;
            padding: 1.25rem !important;
        }
        
        .card-body {
            padding: 1.5rem !important;
        }
        
        .btn-primary, .btn-secondary {
            width: 100% !important;
            margin-bottom: 0.75rem !important;
        }
    }
</style>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card">
                <div class="card-header">Create New Department</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.departments.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Department Name</label>
                            <div class="position-relative">
                                <input type="text" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    placeholder="Enter department name"
                                    required>
                                <i class="bi bi-building input-icon"></i>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">Description</label>
                            <div class="position-relative">
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                    id="description" 
                                    name="description" 
                                    rows="4"
                                    placeholder="Enter department description">{{ old('description') }}</textarea>
                                <i class="bi bi-text-paragraph input-icon"></i>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mt-4">
                            <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle me-2"></i>Create Department
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection