@extends('layouts.kasir')

@section('content')
<div class="container mt-3">
    <!-- Notifikasi Flash (misal: OTP valid atau error) -->
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

    <h5 class="mb-3">Ubah Password</h5>
    <form action="{{ route('kasir-profil-change-Password') }}" method="POST">
        @csrf
        <div class="mb-2">
            <label for="current_password" class="form-label small">Password Saat Ini</label>
            <div class="input-group">
                <input type="password" class="form-control form-control-sm" id="current_password" name="current_password" required>
                <span class="input-group-text">
                    <i class="fa fa-eye toggle-password" data-target="current_password" style="cursor: pointer;"></i>
                </span>
            </div>
        </div>
        <div class="mb-2">
            <label for="new_password" class="form-label small">Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control form-control-sm" id="new_password" name="new_password" required>
                <span class="input-group-text">
                    <i class="fa fa-eye toggle-password" data-target="new_password" style="cursor: pointer;"></i>
                </span>
            </div>
        </div>
        <div class="mb-2">
            <label for="new_password_confirmation" class="form-label small">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password" class="form-control form-control-sm" id="new_password_confirmation" name="new_password_confirmation" required>
                <span class="input-group-text">
                    <i class="fa fa-eye toggle-password" data-target="new_password_confirmation" style="cursor: pointer;"></i>
                </span>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Ubah Password</button>
    </form>
</div>

<!-- Script untuk toggle tampilan password -->
<script>
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var targetId = this.getAttribute('data-target');
            var input = document.getElementById(targetId);
            if (input.getAttribute('type') === 'password') {
                input.setAttribute('type', 'text');
                this.classList.remove('fa-eye');
                this.classList.add('fa-eye-slash');
            } else {
                input.setAttribute('type', 'password');
                this.classList.remove('fa-eye-slash');
                this.classList.add('fa-eye');
            }
        });
    });
</script>

<!-- CSS untuk menyembunyikan ikon bawaan browser -->
<style>
    input[type="password"]::-ms-reveal,
    input[type="password"]::-ms-clear {
        display: none;
    }
    input[type="password"] {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>
@endsection
