<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>

<div class="container" style="margin-top: 50px">
    <div class="row">
        <div class="col-md-5 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <label>Login</label>
                    <hr>

                    <!-- Menampilkan Pesan Status -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Menampilkan Pesan Error -->
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="email">Email</label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" 
                                id="email" 
                                placeholder="Email"
                                value="{{ old('email') }}" 
                                required
                            >
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group mb-2">
                            <label for="password">Password</label>
                            <div class="position-relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" 
                                    id="password" 
                                    placeholder="Password"
                                    required
                                >
                                <i 
                                    class="bi bi-eye position-absolute top-50 translate-middle-y end-0 me-3" 
                                    id="togglePassword" 
                                    style="cursor: pointer;">
                                </i>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-login btn-block btn-success">Login</button>
                    </form>
                </div>
            </div>

            <div class="text-center" style="margin-top: 15px">
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('password.request') }}" class="btn btn-link">Lupa Password?</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');

    togglePassword.addEventListener('click', function () {
        // Toggle the type attribute
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        // Toggle the icon class
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>
</body>
</html>
