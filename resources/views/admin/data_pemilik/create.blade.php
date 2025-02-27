@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah User Baru</h3>
        </div>
        <div class="card-body">

            <div class="card-body">
                <div id="alert-message" class="alert d-none"></div> <!-- Pesan akan muncul di sini -->
                <form action="{{ route('admin-user-store') }}" method="POST">
            
            <form action="{{ route('admin-user-store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name') }}" >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" 
                    class="form-control @error('phone') is-invalid @enderror" 
                    id="phone"
                    name="phone" 
                    placeholder="081234567890"
                    value="{{ old('phone') }}"
                    maxlength="12"
                    pattern="\d{1,12}"
                    oninput="validatePhone(this)">
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
                    <input type="text" 
                        class="form-control @error('otp') is-invalid @enderror" 
                        id="otp"
                        name="otp" 
                        value="{{ old('otp') }}" 
                        maxlength="6" 
                        pattern="\d{6}"
                        oninput="validateOtp(this)">
                    @error('otp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

               

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" >
                        <option value="">Pilih Role</option>
                        <option value="perawat">Perawat</option>
                        <option value="kasir">Kasir</option>
                    </select>
                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('data_pemilik.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>

<script>
    function sendOtp() {
        const phone = document.getElementById('phone').value;
        const sendOtpBtn = document.getElementById('sendOtpBtn');
        const otpInput = document.getElementById('otp');
        const otpTimer = document.getElementById('otpTimer');
        const alertMessage = document.getElementById('alert-message');
    
        if (!phone) {
            showMessage("Silakan masukkan nomor telepon.", "danger");
            return;
        }
    
        sendOtpBtn.innerHTML = "Mengirim...";
        sendOtpBtn.disabled = true;
    
        fetch("{{ route('send-otp-create-kasir-perawat') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showMessage("OTP telah dikirim ke " + phone, "success");
                otpInput.removeAttribute('readonly'); // Mengaktifkan input OTP
                startOtpTimer(sendOtpBtn, otpTimer, true); // Mulai countdown
            } else {
                showMessage(data.message || "Gagal mengirim OTP, coba lagi nanti.", "danger");
                sendOtpBtn.innerHTML = "Kirim OTP";
                sendOtpBtn.disabled = false;
            }
        })
        .catch(() => {
            showMessage("Terjadi kesalahan, silakan coba lagi nanti.", "danger");
            sendOtpBtn.innerHTML = "Kirim OTP";
            sendOtpBtn.disabled = false;
        });
    }
    
    function showMessage(message, type) {
        const alertMessage = document.getElementById('alert-message');
        alertMessage.className = `alert alert-${type}`;
        alertMessage.innerHTML = message;
        alertMessage.classList.remove('d-none'); // Munculkan pesan
    
        setTimeout(() => {
            alertMessage.classList.add('d-none'); // Sembunyikan setelah 5 detik
        }, 5000);
    }
    
    function startOtpTimer(button, timerSpan, reset = false) {
        if (reset) {
            countdownTime = 60;
        }
    
        sessionStorage.setItem("otpCountdown", countdownTime);
        button.disabled = true;
    
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
    </script>
    
@endsection
