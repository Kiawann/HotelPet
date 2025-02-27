@extends('layouts.kasir')

@section('content')
<style>
    .profile-picture {
        max-width: 150px;    
        max-height: 150px;
        border-radius: 50%;
    }
    .btn-icon {
        padding: 0.4rem 0.8rem;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-icon i {
        margin-right: 6px;
    }
    .profile-card {
        background: #fff;
        padding: 20px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }
    .profile-header {
        margin-bottom: 1.5rem;
    }
    .profile-info p {
        margin-bottom: 0.5rem;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Notification Section -->
<div class="container mt-3">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
</div>

<!-- Profile Header -->
<section class="profile-header text-center mt-3">
    <div class="container">
        <img src="{{ asset('storage/' . Auth::user()->dataPemilik->foto) }}" alt="User Avatar"
            class="img-fluid profile-picture mb-2">
        <h4 class="mb-1">{{ Auth::user()->name }}</h4>
        <p class="text-muted small mb-2">{{ Auth::user()->email }}</p>
    </div>
</section>

<!-- Profile Card -->
<section class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="profile-card">
                <h5 class="text-primary mb-3">Informasi Profil</h5>
                <div class="profile-info">
                    <div class="mb-2">
                        <strong>Nama Lengkap:</strong>
                        <p class="ps-3">{{ $dataPemilik->nama ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="mb-2">
                        <strong>Jenis Kelamin:</strong>
                        <p class="ps-3">
                            @if(isset($dataPemilik->jenis_kelamin))
                                {{ $dataPemilik->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div class="mb-2">
                        <strong>No. Telepon:</strong>
                        <p class="ps-3">{{ Auth::user()->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Button Section -->
            <div class="d-flex gap-2 justify-content-center mt-3">
                <button type="button" class="btn btn-outline-secondary btn-icon btn-sm" data-bs-toggle="modal"
                    data-bs-target="#editProfilModal">
                    <i class="bi bi-pencil-square"></i> Edit
                </button>
                <button type="button" class="btn btn-outline-secondary btn-icon btn-sm" data-bs-toggle="modal"
                    data-bs-target="#sendOtpModal">
                    <i class="bi bi-lock"></i> Ubah Password
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Modal Edit Profil -->
<div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kasir-profil.update', $dataPemilik->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="mb-2">
                        <label for="name" class="form-label small">Username</label>
                        <input type="text" class="form-control form-control-sm" id="name" name="name" value="{{ Auth::user()->name }}">
                    </div>

                    <div class="mb-2">
                        <label for="phone" class="form-label small">No. Telepon</label>
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}" readonly>
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#changePhoneModal">
                                Ubah
                            </button>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label for="nama" class="form-label small">Nama Lengkap</label>
                        <input type="text" class="form-control form-control-sm" id="nama" name="nama" value="{{ $dataPemilik->nama }}">
                    </div>

                    <div class="mb-2">
                        <label for="jenis_kelamin" class="form-label small">Jenis Kelamin</label>
                        <select class="form-select form-select-sm" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="L" {{ $dataPemilik->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ $dataPemilik->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="foto" class="form-label small">Foto Profil</label>
                        <input type="file" class="form-control form-control-sm" id="foto" name="foto">
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm w-100">Update Profil</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Change Phone -->
<div class="modal fade" id="changePhoneModal" tabindex="-1" aria-labelledby="changePhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="changePhoneModalLabel">Ubah Nomor Telepon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePhoneForm" action="{{ route('kasir-change-phone') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="new_phone" class="form-label small">Masukkan Nomor Baru</label>
                        <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            maxlength="13" class="form-control form-control-sm text-center" id="new_phone"
                            name="phone" placeholder="08XXXXXXXXXX" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary btn-sm">Kirim OTP</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Send OTP -->
<div class="modal fade" id="sendOtpModal" tabindex="-1" aria-labelledby="sendOtpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h5 class="modal-title" id="sendOtpModalLabel">Kirim OTP ke Nomor Telepon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="sendOtpForm" action="{{ route('send-otp-change-password-kasir') }}" method="POST">
                    @csrf
                    <div class="mb-2">
                        <label for="phone_send" class="form-label small">Nomor Telepon</label>
                        <input type="text" class="form-control form-control-sm" id="phone_send" name="phone" value="{{ Auth::user()->phone }}" readonly>
                    </div>
                    <button type="submit" id="btnSendOtp" class="btn btn-primary btn-sm w-100">Kirim OTP</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sendOtpForm').addEventListener('submit', function() {
        var btn = document.getElementById('btnSendOtp');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
    });
</script>
@endsection
