<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
       html,
body {
    height: auto;
    overflow: auto;
    margin: 0; /* Hapus margin default */
    padding: 0; /* Hapus padding default */
}

.profile-picture {
    max-width: 150px;
    max-height: 150px;
    border-radius: 50%;
}

.btn-icon {
    padding: 0.5rem 1rem;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-icon i {
    margin-right: 8px;
}

.profile-card {
    background: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 10px; /* Atur jarak atas profile card */
}

.container {
    padding: 0; /* Hapus padding */
    margin-bottom: 60px; /* Sesuaikan dengan tinggi navbar */
}

.profile-header {
    margin-top: 10px; /* Atur jarak yang diinginkan */
    text-align: center; /* Tengah-kan isi dalam header */
}

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

/* Aturan tambahan untuk mengurangi jarak */
.mt-3 {
    margin-top: 1rem; /* Mengurangi margin top di alert */
}

.mb-3 {
    margin-bottom: 0.5rem; /* Mengurangi margin bottom di elemen */
}
    </style>
</head>

<body>

    @include('layouts.perawat')

    <!-- Notification Section -->
    <div class="container mt-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <!-- Profil Section -->
    <section class="profile-header text-center">
        <div class="container">
            <img src="{{ asset('storage/' . Auth::user()->dataPemilik->foto) }}" alt="User Avatar"
                class="img-fluid profile-picture">
            <h2>{{ Auth::user()->name }}</h2>
            <p class="text-muted">{{ Auth::user()->email }}</p>
        </div>
    </section>

    <!-- Profile Card Section -->
    <section class="container my-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="profile-card">
                    <h4 class="text-primary mb-4">Informasi Profil</h4>
                    <div class="mb-3">
                        <strong>Nama Lengkap:</strong>
                        <p>{{ $dataPemilik->nama ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="mb-3">
                        <strong>Jenis Kelamin:</strong>
                        <p>
                            @if (isset($dataPemilik->jenis_kelamin))
                                {{ $dataPemilik->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            @else
                                Belum diisi
                            @endif
                        </p>
                    </div>
                    <div class="mb-3">
                        <strong>No. Telepon:</strong>
                        <p>{{ Auth::user()->phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Button Section for Edit, Change Password, Change Email -->
    <div class="container mt-4 text-center">
        <div class="row justify-content-center">
            <div class="col-12 col-md-3 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-icon w-100" data-bs-toggle="modal"
                    data-bs-target="#editProfilModal">
                    <i class="bi bi-pencil-square"></i> Edit Profil
                </button>
            </div>
            <div class="col-12 col-md-3 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-icon w-100" data-bs-toggle="modal"
                    data-bs-target="#sendOtpModal">
                    <i class="bi bi-lock"></i> Ubah Password
                </button>
            </div>
        </div>
    </div>
    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfilModal" tabindex="-1" aria-labelledby="editProfilModalLabel" role="dialog"
        aria-modal="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfilModalLabel">Edit Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('perawat-profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">

                        <div class="mb-3">
                            <label for="name" class="form-label">Username</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ Auth::user()->name }}">
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="{{ Auth::user()->phone }}" readonly>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#changePhoneModal">
                                    Change Phone
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama" name="nama"
                                value="{{ $dataPemilik->nama }}">
                        </div>

                        <div class="mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="L" {{ $dataPemilik->jenis_kelamin == 'L' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="P" {{ $dataPemilik->jenis_kelamin == 'P' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" class="form-control" id="foto" name="foto">
                        </div>

                        <button type="submit" class="btn btn-primary">Update Profil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <!-- Modal Change Phone -->
    <div class="modal fade" id="changePhoneModal" tabindex="-1" aria-labelledby="changePhoneModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePhoneModalLabel">Ubah Nomor Telepon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('perawat-change-phone') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="new_phone" class="form-label">Masukkan Nomor Baru</label>
                            <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                maxlength="13" class="form-control text-center" id="new_phone" name="phone"
                                placeholder="08XXXXXXXXXX" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-primary">Kirim OTP</button>
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
                <form id="sendOtpForm" action="{{ route('send-otp-change-password-perawat') }}" method="POST">
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
   

</body>
<script>
    document.getElementById('sendOtpForm').addEventListener('submit', function() {
        var btn = document.getElementById('btnSendOtp');
        btn.disabled = true;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`;
    });
</script>
</html>
