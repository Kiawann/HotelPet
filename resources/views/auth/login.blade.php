<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="text-center mb-3">Login</h4>
                        <hr>
                        
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif
                        
                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" 
                                       name="phone" 
                                       class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" 
                                       id="phone" 
                                       placeholder="081234567890" 
                                       value="{{ session('phone', old('phone')) }}" 
                                       maxlength="12"
                                       pattern="[0-9]{1,12}"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 12)"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" value="{{ session('password', '') }}" required>
                                    <span class="input-group-text bg-white border" id="togglePassword" style="cursor: pointer;">
                                        <i class="bi bi-eye"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <button type="submit" class="btn btn-success w-100">Login</button>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('register') }}" class="text-decoration-none">Register</a> | 
                    <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa Password?</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.querySelector('#togglePassword').addEventListener('click', function () {
            const passwordField = document.querySelector('#password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        // Phone number validation
        const phoneInput = document.querySelector('#phone');
        phoneInput.addEventListener('keypress', function(e) {
            // Prevent non-numeric input
            if (e.key < '0' || e.key > '9') {
                e.preventDefault();
            }
            // Prevent input if length is already 12
            if (this.value.length >= 12) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>