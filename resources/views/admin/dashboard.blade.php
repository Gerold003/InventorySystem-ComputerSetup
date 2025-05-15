@extends('layouts.app')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background-image: url('/images/TrackNETbg.jpg');
        background-size: cover;
        background-attachment: fixed;
        color: white;
        min-height: 100vh;
    }

    /* Sidebar Styles */
    .sidebar {
        height: 100vh;
        width: 250px;
        position: fixed;
        top: 0;
        left: 0;
        background-color: #1e1e1e;
        color: white;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        z-index: 1000;
        overflow-x: hidden;
    }

    /* Toggle Button Styles */
    .sidebar-toggle {
        position: absolute;
        right: -40px;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1001;
        background: #1e1e1e;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 0 5px 5px 0;
        transition: all 0.3s ease;
        height: 60px;
        display: flex;
        align-items: center;
    }

    .sidebar-toggle i {
        transition: transform 0.3s ease;
    }

    /* Logo Header Styles */
    .sidebar-header {
        display: flex;
        align-items: center;
        padding: 20px;
        margin-bottom: 20px;
        border-bottom: 1px solid #444;
        position: relative;
        min-height: 80px;
    }

    .sidebar-logo {
        display: flex;
        align-items: center; 
        transition: all 0.3s ease;
        width: 100%;
    }

    .sidebar-logo img {
        position: fixed;
        width: 100px;
        height: 100px;
        transition: all 0.3s ease;
    }

    .sidebar-logo-text {
        font-size: 1.5rem;
        position: fixed;
        left: 98px;
        white-space: nowrap;
        opacity: 1;
        transition: opacity 0.2s ease;
    }

    /* Collapsed State */
    .sidebar.collapsed {
        width: 70px;
    }

    .sidebar.collapsed .sidebar-logo-text {
        opacity: 0;
        position: absolute;
    }

    .sidebar.collapsed .sidebar-logo {
        justify-content: center;
    }

    .sidebar.collapsed .sidebar-logo img {
        width: 35px;
        height: 35px;
    }

    .sidebar.collapsed .sidebar-toggle {
        right: -35px;
    }

    .sidebar.collapsed .sidebar-toggle i {
        transform: rotate(180deg);
    }

    /* Navigation Links */
    .sidebar a {
        padding: 15px 25px;
        text-decoration: none;
        color: white;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.2s ease;
    }

    .sidebar a i {
        font-size: 1.5rem;
        min-width: 30px;
    }

    .sidebar a:not(.logout-btn):hover {
        background: #444;
        border-left: 4px solid #ff7b54;
    }

    /* Main Content */
    .main-content {
        margin-left: 250px;
        transition: all 0.3s ease;
        padding: 20px;
    }

    .sidebar.collapsed + .main-content {
        margin-left: 70px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .sidebar {
            left: -250px;
        }

        .sidebar.collapsed {
            left: 0;
            width: 250px;
        }

        .main-content {
            margin-left: 0 !important;
        }

        .sidebar.collapsed + .main-content {
            margin-left: 0;
        }

        .sidebar-toggle {
            right: -45px;
        }

        .sidebar.collapsed .sidebar-toggle {
            right: -40px;
        }
    }

    /* Card Styles */
    .card {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .card-header {
        background: rgba(255, 255, 255, 0.95);
        font-weight: bold;
    }
    .btn-custom {
            background: #ff7b54;
            color: white;
            transition: 0.3s;
        }
    .btn-custom:hover {
            background: #ff5733;
        }
</style>

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="/images/logo.png" alt="TrackNET Logo">
            <span class="sidebar-logo-text">TrackNET</span>
        </div>
        <button class="sidebar-toggle">
            <i class="bi bi-caret-left-fill"></i>
        </button>
    </div>

    <a href="{{ route('admin.dashboard') }}" class="active">
        <i class="bi bi-graph-up"></i>
        <span class="nav-text">Dashboard</span>
    </a>

    <a href="#">
        <i class="bi bi-box-seam"></i>
        <span class="nav-text">Item Management</span>
    </a>

    <a href="#">
        <i class="bi bi-people"></i>
        <span class="nav-text">Suppliers</span>
    </a>

    <a href="#">
        <i class="bi bi-map"></i>
        <span class="nav-text">Orders</span>
    </a>

    <a href="#">
        <i class="bi bi-info-lg"></i>
        <span class="nav-text">Reports</span>
    </a>

    
      
    

</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Admin Dashboard</div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Stats Cards -->
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Total Users</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-users fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Departments</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-building fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Recent Users Table -->
                            <div class="col-md-12 mt-4">
                                <div class="card shadow">
                                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                        <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-custom">View All</a>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Role</th>
                                                        <th>Joined</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($recentUsers as $user)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ ucfirst($user->role) }}</td>
                                                        <td>{{ $user->created_at->format('m/d/Y') }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar');
        const toggleBtn = document.querySelector('.sidebar-toggle');
        const toggleIcon = toggleBtn.querySelector('i');

        // Toggle Sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        // Auto-close on mobile when clicking outside
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.add('collapsed');
                }
            }
        });

        // Window Resize Handler
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('collapsed');
            }
        });
    });
</script>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">

@endsection