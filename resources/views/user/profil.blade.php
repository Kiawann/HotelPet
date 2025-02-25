<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Profil Pengguna</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
  <!-- Tambahkan jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    /* body {
      padding-top: 80px;
    } */

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
  </style>
</head>

<body>

  @include('layouts.navbar')

  <!-- Notification Section -->
  <div class="container mt-3"><br>
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
  <section class="profile-header text-center"><br><br>
    <div class="container">
      <img src="{{ asset('storage/' . Auth::user()->dataPemilik->foto) }}" alt="User Avatar" class="img-fluid profile-picture">
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
              @if(isset($dataPemilik->jenis_kelamin))
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
      {{-- <div class="col-12 col-md-3 mb-3">
                <button type="button" class="btn btn-outline-secondary btn-icon w-100" data-bs-toggle="modal"
                    data-bs-target="#changeEmailModal">
                    <i class="bi bi-envelope"></i> Ubah Email
                </button>
            </div> --}}
      <div class="col-12 col-md-3 mb-3">
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button type="submit" class="btn btn-outline-danger btn-icon w-100">
            <i class="bi bi-box-arrow-right"></i> Logout
          </button>
        </form>
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
          <form action="{{ route('profil.update', $dataPemilik->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
              <label for="name" class="form-label">Username</label>
              <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}">
            </div>

            <div class="mb-3">
              <label for="phone" class="form-label">No. Telepon</label>
              <div class="input-group">
                <input type="text" class="form-control" id="phone" name="phone" value="{{ Auth::user()->phone }}"
                  readonly>
                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#changePhoneModal">
                  Change Phone
                </button>
              </div>
            </div>

            <div class="mb-3">
              <label for="nama" class="form-label">Nama Lengkap</label>
              <input type="text" class="form-control" id="nama" name="nama" value="{{ $dataPemilik->nama }}">
            </div>

            <div class="mb-3">
              <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
              <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                <option value="L" {{ $dataPemilik->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ $dataPemilik->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
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
  <div class="modal fade" id="changePhoneModal" tabindex="-1" aria-labelledby="changePhoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePhoneModalLabel">Ubah Nomor Telepon</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Tambahkan id "changePhoneForm" ke form ini -->
          <form id="changePhoneForm" action="{{ route('change-phone') }}" method="POST">
            @csrf
            <div class="form-group">
              <label for="new_phone" class="form-label">Masukkan Nomor Baru</label>
              <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" maxlength="13"
                class="form-control text-center" id="new_phone" name="phone" placeholder="08XXXXXXXXXX" required>
              @error('phone')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div class="mt-3 text-end">
              <button type="submit" class="btn btn-primary" id="changePhoneBtn">Kirim OTP</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Send OTP -->
  <div class="modal fade" id="sendOtpModal" tabindex="-1" aria-labelledby="sendOtpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="sendOtpForm">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="sendOtpModalLabel">Kirim OTP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="phone_sendOtp" class="form-label">Nomor Telepon</label>
              <!-- Gunakan id phone_sendOtp agar tidak duplikat -->
              <input type="text" class="form-control" id="phone_sendOtp" name="phone" value="{{ Auth::user()->phone }}"
                readonly>
            </div>
            <p>OTP akan dikirim ke nomor di atas.</p>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="sendOtpBtn">Kirim OTP</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Verifikasi OTP -->
  <div class="modal fade" id="verifyOtpModal" tabindex="-1" aria-labelledby="verifyOtpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="verifyOtpForm">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="verifyOtpModalLabel">Verifikasi OTP</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="otp" class="form-label">Masukkan OTP</label>
              <input type="text" class="form-control" id="otp" name="otp" placeholder="Masukkan 6 digit OTP" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary" id="verifyOtpBtn">Verifikasi OTP</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Change Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="{{ route('profil.changePassword') }}" method="POST">
          @csrf
          <div class="modal-body">
            <div class="mb-3">
              <label for="current_password" class="form-label">Password Saat Ini</label>
              <div class="input-group">
                <input type="password" class="form-control" id="current_password" name="current_password" required>
                <button type="button" class="btn btn-outline-secondary" id="toggleCurrentPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label for="new_password" class="form-label">Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="new_password" name="new_password" required>
                <button type="button" class="btn btn-outline-secondary" id="toggleNewPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
            <div class="mb-3">
              <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
              <div class="input-group">
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Ubah Password</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  
  <script>
    // Fungsi toggle show/hide password untuk semua field
    function togglePassword(toggleBtnId, passwordFieldId) {
      const btn = document.getElementById(toggleBtnId);
      if (btn) {
        btn.addEventListener('click', function () {
          const passwordField = document.getElementById(passwordFieldId);
          const icon = this.querySelector('i');
          if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
          } else {
            passwordField.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
          }
        });
      }
    }

    // Terapkan fungsi toggle ke masing-masing field
    togglePassword('toggleCurrentPassword', 'current_password');
    togglePassword('toggleNewPassword', 'new_password');
    togglePassword('toggleConfirmPassword', 'new_password_confirmation');

    $(document).ready(function () {
      // Proses Kirim OTP untuk modal Change Phone dengan loading
      $('#changePhoneForm').on('submit', function (e) {
        e.preventDefault();
        let phone = $('#new_phone').val();
        let btn = $('#changePhoneBtn');
        btn.prop('disabled', true);
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $.ajax({
          url: '/change-phone',
          type: 'POST',
          data: {
            phone: phone,
            _token: $('meta[name="csrf-token"]').attr('content')
          },
          success: function (response) {
            alert(response.message);
            $('#otpModal').modal('show'); // Munculkan modal OTP (jika ada)
            $('#userPhone').text(response.phone); // Tampilkan nomor yang dikirimi OTP (jika ada)
          },
          error: function (xhr) {
            alert(xhr.responseJSON.message);
          },
          complete: function () {
            btn.prop('disabled', false);
            btn.html(originalText);
          }
        });
      });

      // Proses Kirim OTP untuk modal Send OTP dengan loading
      $('#sendOtpForm').on('submit', function (e) {
        e.preventDefault();
        let phone = $('#phone_sendOtp').val();
        let btn = $('#sendOtpBtn');
        btn.prop('disabled', true);
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $.ajax({
          url: '{{ route("send-otp-change-password") }}', // pastikan route ini mengarah ke controller yang memanggil API Japati
          type: 'POST',
          data: {
            phone: phone,
            _token: $('input[name="_token"]').val()
          },
          success: function (response) {
            alert(response.message); // Tampilkan pesan sukses
            // Tutup modal send OTP dan buka modal verifikasi OTP
            $('#sendOtpModal').modal('hide');
            $('#verifyOtpModal').modal('show');
          },
          error: function (xhr) {
            alert(xhr.responseJSON.message);
          },
          complete: function () {
            btn.prop('disabled', false);
            btn.html(originalText);
          }
        });
      });

      // Proses Verifikasi OTP untuk modal Verifikasi OTP dengan loading
      $('#verifyOtpForm').on('submit', function (e) {
        e.preventDefault();
        let otp = $('#otp').val();
        let btn = $('#verifyOtpBtn');
        btn.prop('disabled', true);
        let originalText = btn.html();
        btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
        $.ajax({
          url: '{{ route("validate-otp-change-password-user") }}', // pastikan route ini mengarah ke controller validasi OTP
          type: 'POST',
          data: {
            otp: otp,
            _token: $('input[name="_token"]').val()
          },
          success: function (response) {
            alert(response.message); // Tampilkan pesan sukses
            // Jika OTP valid, tutup modal verifikasi OTP dan buka modal ubah password
            $('#verifyOtpModal').modal('hide');
            $('#changePasswordModal').modal('show');
          },
          error: function (xhr) {
            alert(xhr.responseJSON.message);
          },
          complete: function () {
            btn.prop('disabled', false);
            btn.html(originalText);
          }
        });
      });
    });
  </script>
  

</body>

</html>
