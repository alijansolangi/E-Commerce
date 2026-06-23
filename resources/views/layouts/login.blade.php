<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="adminHMD authentication page">
    <title>Login | adminHMD</title>

    {{-- Asset Locator with Laravel's asset() helper --}}
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="auth-body">
    {{-- Theme Toggle Button --}}
    <button class="icon-button theme-toggle auth-theme-toggle" type="button" data-theme-toggle aria-label="Switch color theme" title="Switch color theme">
        <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
    </button>

    <main class="auth-page">
        <section class="auth-card">
            {{-- Login Form --}}
            <form method="POST" action="{{ route('adminlogin') }}" class="needs-validation" novalidate>
                @csrf

                <div class="mb-4">
                    <h1 class="h3 mb-1">Login</h1>
                    <p class="text-muted mb-0">Sign in to your admin workspace.</p>
                </div>

                {{-- Email Field --}}
                <div class="mb-3">
                    <label class="form-label" for="loginEmail">Email address</label>
                    <input class="form-control @error('email') is-invalid @enderror" 
                           id="loginEmail" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           autofocus>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Password Field --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="loginPassword">Password</label>
                        <a class="small fw-semibold" href="#">Forgot?</a>
                    </div>
                    <input class="form-control @error('password') is-invalid @enderror" 
                           id="loginPassword" 
                           type="password" 
                           name="password" 
                           minlength="6" 
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>

                {{-- Submit Button --}}
                <button class="btn btn-primary w-100" type="submit">
                    <i class="bi bi-box-arrow-in-right" aria-hidden="true"></i> Sign In
                </button>
            </form>
        </section>
    </main>

    {{-- Scripts --}}
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>