<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reset Password</title>
    <style>
        .input-group {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .form-control {
            padding-right: 40px; /* Space for the eye icon */
        }
    </style>
</head>
<body>

<div class="container" style="margin-top: 50px">
    <h1>Reset Password</h1>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <!-- Token yang dikirim via email -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email terkait dengan token -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $email ?? old('email') }}" readonly>
        </div>

        <!-- Password baru -->
        <div class="mb-3">
            <label for="password" class="form-label">Password Baru</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('password')">
                    <i class="bi bi-eye"></i>
                </span>
            </div>
        </div>

        <!-- Konfirmasi password -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                <span class="eye-icon" onclick="togglePasswordVisibility('password_confirmation')">
                    <i class="bi bi-eye"></i>
                </span>
            </div>
        </div>

        <!-- Menampilkan error -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tombol submit -->
        <button type="submit" class="btn btn-primary">Reset Password</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePasswordVisibility(id) {
        var passwordField = document.getElementById(id);
        var eyeIcon = passwordField.nextElementSibling;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.innerHTML = '<i class="bi bi-eye-slash"></i>';
        } else {
            passwordField.type = "password";
            eyeIcon.innerHTML = '<i class="bi bi-eye"></i>';
        }
    }
</script>

<!-- Include Bootstrap Icons (for eye icon) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

</body>
</html>
