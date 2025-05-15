<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>Log in</title>
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

        .input-group {
            position: relative;
            display: flex;
            align-items: center;
        }

        input[type="password"], input[type="text"] {
            border-radius: 8px !important;
            padding: 12px;
            border: 1px solid #ccc;
            transition: all 0.3s;
            box-shadow: 2px 2px 8px rgba(80, 80, 80, 0.2);
            background-color: #303134;
            color: #E8EAED;
        }

        input[type="password"] {
            padding-right: 50px;
            border-radius: 10px;
            position: relative;
            z-index: 0;
        }

        input::placeholder {
            color: #ccc !important;
        }

        .btn-custom {
            background: #ff7b54;
            color: white;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: #ff5733;
        }

        .bookmark-icon {
            position: absolute;
            top: -27px;
            left: 80%;
            transform: translateX(-50%) scaleY(0.8);
            font-size: 60px;
            color: #A82810;
            padding: 4.8px;
            transition: transform 0.3s ease-in-out;
            transform-origin: top center;
        }

        .bookmark-icon:hover {
            transform: translateX(-50%) scaleY(1.2);
        }

        a {
            color: #00A8E8;
            transition: color 0.3s;
        }

        a:hover {
            color: #00C2CB;
        }

        #toggleIcon {
            position: absolute;
            top: 0;
            right: 0;
            height: 90%;
            width: 50px;
            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;
            background-color: transparent;
            color: #ccc;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            pointer-events: auto;
            cursor: pointer;
        }

        #toggleIcon:hover i {
            color: #ff5733;
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
    <div class="card text-start position-relative" style="width: 450px;">
        <i class="bi bi-bookmark-fill bookmark-icon"></i>
        <div class="logo-container">
            <img src="/images/logo.png" alt="Logo">
        </div>
        <h4 class="mb-3 text-center" style="color: white; font-weight: bold;">TrackNET</h4>
        <p class="mb-4 text-center" style="color: #9AA0A6; font-size: 14px;">Login with your account to continue.</p>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        <p style="color: red;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif
            <div class="mb-3">
                <input type="text" name="email" id="email" class="form-control" placeholder="Email" required autofocus style="background-color: #303134; color: #E8EAED; border: 1px solid #303134;">
            </div>
            <div class="mb-3 input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required style="background-color: #303134; color: #E8EAED; border: 1px solid #303134;">
                <span id="toggleIcon" onclick="togglePassword()">
                    <i class="bi bi-eye-slash"></i>
                </span>
            </div>
            <div class="text-start mb-3">
                <a href="#" style="color: #8AB4F8; font-size: 14px; text-decoration: none;">Forgot password?</a>
            </div>
            <div class="text-start mb-3" style="font-size: 12px; color: #9AA0A6;">
                Don't have an account? <a href="#" style="color: #8AB4F8; text-decoration: none;">Create one.</a>
            </div>
            <button type="submit" class="btn btn-custom w-100">Next</button>
        </form>
        <div class="mt-4 text-start">
            <a href="/"style="color: #8AB4F8; font-size: 14px; text-decoration: none;">Return to homepage</a>
        </div>
    </div>
<script>
    function togglePassword() {
        var passwordInput = document.getElementById("password");
        var toggleIcon = document.getElementById("toggleIcon").querySelector("i");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleIcon.classList.remove("bi-eye-slash");
            toggleIcon.classList.add("bi-eye-fill");
        } else {
            passwordInput.type = "password";
            toggleIcon.classList.remove("bi-eye-fill");
            toggleIcon.classList.add("bi-eye-slash");
        }
    }
</script>
</body>
</html>
