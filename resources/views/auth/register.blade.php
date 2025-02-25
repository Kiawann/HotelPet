<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 400px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3 class="text-center">Form Registrasi</h3>

        <div id="alertContainer">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <form action="{{ route('register.store') }}" method="POST" id="registrationForm">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">User Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    name="phone" placeholder="081234567890" value="{{ old('phone') }}" maxlength="12"
                    pattern="\d{1,12}" oninput="validatePhone(this)">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-secondary" id="sendOtpBtn" onclick="sendOtp()">Kirim OTP</button>
                <span id="otpTimer" class="text-muted ms-2"></span> <!-- Timer countdown -->
            </div>

            <div class="mb-3 mt-3">
                <label for="otp" class="form-label">Kode OTP</label>
                <input type="text" class="form-control @error('otp') is-invalid @enderror" id="otp"
                    name="otp" value="{{ old('otp') }}" maxlength="6" pattern="\d{6}"
                    oninput="validateOtp(this)">
                @error('otp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="password-container">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password">
                    <i class="toggle-password fas fa-eye-slash" onclick="togglePassword('password')"></i>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                <div class="password-container">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    <i class="toggle-password fas fa-eye-slash" onclick="togglePassword('password_confirmation')"></i>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="{{ route('login') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>

    <script>
        let countdownTime = sessionStorage.getItem("otpCountdown") || 0;
        let countdownInterval;

        function validateOtp(input) {
            input.value = input.value.replace(/\D/g, '').slice(0, 6);
        }

        function sendOtp() {
            const phone = document.getElementById('phone').value;
            const sendOtpBtn = document.getElementById('sendOtpBtn');
            const otpInput = document.getElementById('otp');
            const otpTimer = document.getElementById('otpTimer');

            if (!phone) {
                showError("Silakan masukkan nomor telepon.");
                return;
            }

            sendOtpBtn.innerHTML = "Mengirim...";
            sendOtpBtn.disabled = true;

            fetch("{{ route('send-otp') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        phone: phone
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showSuccess("OTP telah dikirim ke " + phone);
                        otpInput.removeAttribute('readonly'); // Mengaktifkan input OTP
                        startOtpTimer(sendOtpBtn, otpTimer, true); // Memulai countdown & simpan state
                    } else {
                        showError(data.message || "Gagal mengirim OTP, coba lagi nanti.");
                        sendOtpBtn.innerHTML = "Kirim OTP";
                        sendOtpBtn.disabled = false;
                    }
                })
                .catch(() => {
                    showError("Terjadi kesalahan, silakan coba lagi nanti.");
                    sendOtpBtn.innerHTML = "Kirim OTP";
                    sendOtpBtn.disabled = false;
                });
        }

        function startOtpTimer(button, timerSpan, reset = false) {
            if (reset) {
                countdownTime = 60; // Atur ulang jika mengirim OTP baru
            }

            sessionStorage.setItem("otpCountdown", countdownTime);
            button.disabled = true; // Pastikan tombol tetap dinonaktifkan

            countdownInterval = setInterval(() => {
                if (countdownTime > 0) {
                    timerSpan.innerHTML = `Tunggu ${countdownTime} detik`;
                    button.innerHTML = `Tunggu ${countdownTime}s`;
                    countdownTime--;
                    sessionStorage.setItem("otpCountdown", countdownTime);
                } else {
                    clearInterval(countdownInterval);
                    button.innerHTML = "Kirim OTP";
                    button.disabled = false;
                    timerSpan.innerHTML = "";
                    sessionStorage.removeItem("otpCountdown");
                }
            }, 1000);
        }

        function restoreCountdown() {
            const sendOtpBtn = document.getElementById("sendOtpBtn");
            const otpTimer = document.getElementById("otpTimer");

            if (countdownTime > 0) {
                startOtpTimer(sendOtpBtn, otpTimer, false);
            }
        }

        function showError(message) {
            document.getElementById('alertContainer').innerHTML =
                `<div class="alert alert-danger my-2" role="alert">${message}</div>`;
        }

        function showSuccess(message) {
            document.getElementById('alertContainer').innerHTML =
                `<div class="alert alert-success my-2" role="alert">${message}</div>`;
        }

        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const icon = passwordInput.nextElementSibling;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.getElementById("registrationForm").addEventListener("submit", function(event) {
            const otpInput = document.getElementById("otp");
            const sendOtpBtn = document.getElementById("sendOtpBtn");

            if (otpInput.value.length !== 6) {
                showError("Kode OTP harus 6 digit.");
                otpInput.value = ""; // Reset hanya input OTP
                event.preventDefault();
                return;
            }
        });

        window.onload = function() {
            restoreCountdown();

            // Mengisi ulang password agar tidak hilang setelah refresh
            if (localStorage.getItem("password")) {
                document.getElementById("password").value = localStorage.getItem("password");
                document.getElementById("password_confirmation").value = localStorage.getItem("password_confirmation");
            }
        };

        function validatePhone(input) {
            // Menghapus semua karakter non-digit
            input.value = input.value.replace(/\D/g, '').slice(0, 12);
        }
    </script>

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
