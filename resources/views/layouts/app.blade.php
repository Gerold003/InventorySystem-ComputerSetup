<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    @stack('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-laptop-code mr-2"></i></i> TrackNet
                </a>
    
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Function to show notifications
        function showNotification(type, message) {
            Swal.fire({
                icon: type,
                title: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }

        // Show Laravel flash messages using SweetAlert
        @if(session('success'))
            showNotification('success', "{{ session('success') }}");
        @endif

        @if(session('error'))
            showNotification('error', "{{ session('error') }}");
        @endif

        @if(session('warning'))
            showNotification('warning', "{{ session('warning') }}");
        @endif

        @if(session('info'))
            showNotification('info', "{{ session('info') }}");
        @endif
    </script>

    @stack('scripts')

    <style>
        /* Global Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease-in-out;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn i {
            font-size: 0.875rem;
        }

        /* Primary Button */
        .btn-primary {
            background: linear-gradient(135deg, #4e73df, #224abe);
            border: none;
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #224abe, #1a3a94);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Secondary Button */
        .btn-secondary {
            background: linear-gradient(135deg, #858796, #6b6d7d);
            border: none;
            color: white;
        }

        .btn-secondary:hover {
            background: linear-gradient(135deg, #6b6d7d, #565964);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Success Button */
        .btn-success {
            background: linear-gradient(135deg, #1cc88a, #169b6b);
            border: none;
            color: white;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #169b6b, #107d55);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Danger Button */
        .btn-danger {
            background: linear-gradient(135deg, #e74a3b, #be3e31);
            border: none;
            color: white;
        }

        .btn-danger:hover {
            background: linear-gradient(135deg, #be3e31, #983228);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Warning Button */
        .btn-warning {
            background: linear-gradient(135deg, #f6c23e, #dfa821);
            border: none;
            color: white;
        }

        .btn-warning:hover {
            background: linear-gradient(135deg, #dfa821, #b88a1b);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Info Button */
        .btn-info {
            background: linear-gradient(135deg, #36b9cc, #2a91a1);
            border: none;
            color: white;
        }

        .btn-info:hover {
            background: linear-gradient(135deg, #2a91a1, #217281);
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(50, 50, 93, 0.11), 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        /* Small Button */
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        /* Button Group */
        .btn-group {
            display: inline-flex;
            gap: 0.5rem;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
        }

        /* Action Buttons Container */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        /* Card Header Buttons */
        .card-header .btn {
            margin-left: 0.5rem;
        }
    </style>
</body>
</html>


