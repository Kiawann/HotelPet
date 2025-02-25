@extends('layouts.kasir')

@section('content')
<div class="container mt-5">
    <!-- Notifikasi Flash -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Verifikasi OTP</h5>
                </div>
                <div class="card-body">
                    <form id="verifyOtpForm" action="{{ route('validate-otp-change-password-kasir') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label small">Masukkan OTP (xxxxxx)</label>
                            <input type="text" class="form-control form-control-sm" id="otp" name="otp" 
                                maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');" 
                                placeholder="xxxxxx" required>
                        </div>
                        <button type="submit" id="btnVerify" class="btn btn-primary btn-sm w-100">Verifikasi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menampilkan loading di tombol Verifikasi OTP -->
<script>
    document.getElementById('verifyOtpForm').addEventListener('submit', function() {
        var btn = document.getElementById('btnVerify');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
    });
</script>
@endsection
