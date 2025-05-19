<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Create User</title>
    <style>
        body {
            background-image: url('/images/TrackNETbg.jpg');
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: #1E1E1E;
            border-radius: 35px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
            padding: 25px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, #1E1E1E 10%, transparent 80%);
            opacity: 0.4;
            z-index: 0;
            pointer-events: none;
        }

        .card::after {
            content: "";
            position: absolute;
            top: -2px;
            left: -2px;
            width: calc(100% + 4px);
            height: calc(100% + 4px);
            border-radius: 35px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            opacity: 0.2;
            pointer-events: none;
        }

        .form-control, .form-select {
            background-color: #303134;
            color: #E8EAED;
            border: 1px solid #303134;
            border-radius: 8px;
            padding: 12px;
            transition: all 0.3s;
            box-shadow: 2px 2px 8px rgba(80, 80, 80, 0.2);
        }

        .form-control:focus, .form-select:focus {
            border-color: #ff7b54;
            box-shadow: 0 0 5px rgba(255, 123, 84, 0.5);
        }

        .form-label {
            color: #9AA0A6;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .btn-custom {
            background: #ff7b54;
            color: white;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #ff5733;
        }

        .btn-secondary {
            background: #6C757D;
            color: white;
            transition: 0.3s;
        }

        .btn-secondary:hover {
            background: #5A6268;
        }

        .logo-container {
            text-align: center;
            margin-bottom: -25px;
        }

        .logo-container img {
            width: 120px;
            height: auto;
            margin-top: -10px;
        }
    </style>
</head>
<body>
    <div class="card text-start position-relative" style="width: 600px;">
        <div class="logo-container">
            <img src="/images/logo.png" alt="Logo">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0" style="color: white; font-weight: bold;">Create New User</h4>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-light">
                <i class="fas fa-times"></i> Close
            </a>
        </div>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            <div class="row g-4">
                <!-- Full Name -->
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" 
                           class="form-control @error('name') is-invalid @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="col-md-6">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" 
                           class="form-control @error('email') is-invalid @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row g-4 mt-3">
                <!-- Password -->
                <div class="col-md-6">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" 
                           class="form-control @error('password') is-invalid @enderror" 
                           id="password" 
                           name="password" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6">
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <input type="password" 
                           class="form-control" 
                           id="password-confirm" 
                           name="password_confirmation" 
                           required>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <!-- Role -->
                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" 
                            id="role" 
                            name="role" 
                            required>
                        <option value="">Select Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="inventory" {{ old('role') == 'inventory' ? 'selected' : '' }}>Inventory</option>
                        <option value="sales" {{ old('role') == 'sales' ? 'selected' : '' }}>Sales</option>
                        <option value="customer" {{ old('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Department -->
                <div class="col-md-6">
                    <label for="department_id" class="form-label">Department</label>
                    <select class="form-select @error('department_id') is-invalid @enderror" 
                            id="department_id" 
                            name="department_id">
                        <option value="">No Department</option>
                        @foreach($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mt-4">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Back to Users
                </a>
                <button type="submit" class="btn btn-custom w-100">
                    <i class="fas fa-save"></i> Create User
                </button>
            </div>
        </form>
    </div>
</body>
</html>