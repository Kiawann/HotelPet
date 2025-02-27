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
            z-index: 10;
            /* Pastikan ikon tetap di atas */
        }

        .form-control {
            padding-right: 40px;
            /* Beri ruang untuk ikon mata */
        }
    </style>
</head>

<body>

    <div class="container" style="margin-top: 50px">
        <h1>Reset Password</h1>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token ?? '' }}">

            <div class="mb-3">
                <label for="phone" class="form-label">No Telepon</label>
                <input name="phone" type="text" class="form-control" id="phone" value="{{ request('phone') }}"
                    readonly>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input name="password" type="password" class="form-control" id="password" placeholder="Password"
                        autocomplete="new-password">
                    <span class="eye-icon" onclick="togglePassword('password')">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="input-group">
                    <input name="password_confirmation" type="password" class="form-control" id="password_confirmation"
                        placeholder="Confirm Password" autocomplete="new-password">
                    <span class="eye-icon" onclick="togglePassword('password_confirmation')">
                        <i class="bi bi-eye"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Reset Password</button>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Toggle Password Visibility -->
    <script>
        function togglePassword(id) {
            var passwordField = document.getElementById(id);
            var eyeIcon = passwordField.closest(".input-group").querySelector(".eye-icon i");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            }
        }
    </script>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Sembunyikan ikon 'show password' bawaan browser */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
            display: none;
        }

        input[type="password"]::-webkit-clear-button,
        input[type="password"]::-webkit-inner-spin-button,
        input[type="password"]::-webkit-credentials-auto-fill-button {
            display: none !important;
        }
    </style>
</body>

</html>
