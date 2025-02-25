<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    @include('layouts.navbar')

    <div class="container mt-5">
        <h1>Tambah Transaksi</h1><br><br>

        <form action="{{ route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Tambahkan ini untuk debugging -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tampilkan pesan error/success -->
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="mb-3">
                <label for="data_pemilik_id" class="form-label">Pemilik</label>
                <!-- Ini yang salah, kita perlu mengirim ID, bukan nama -->
                <input type="text" name="data_pemilik_id" class="form-control" 
                       value="{{ $reservasiHotel->dataPemilik->id }}" readonly>
                <!-- Bisa tambahkan ini untuk menampilkan nama -->
                <input type="text" class="form-control" 
                       value="{{ $reservasiHotel->dataPemilik->nama }}" readonly disabled>
            </div>
            <div class="mb-3">
                <label for="reservasi_hotel_id" class="form-label">ID Reservasi Hotel</label>
                <input type="text" name="reservasi_hotel_id" class="form-control"
                    value="{{ $reservasiHotel->id ?? '' }}" readonly>
            </div>

            <div class="mb-3">
                <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                <input type="date" id="tanggal_pembayaran" name="tanggal_pembayaran" class="form-control"
                    value="{{ date('Y-m-d') }}" readonly>
            </div>

            <div class="mb-3">
                <label for="subtotal" class="form-label">Subtotal</label>
                <input type="hidden" name="Subtotal" value="{{ $totalHarga }}">
                <input type="text" class="form-control" value="{{ number_format($totalHarga, 0, ',', '.') }}"
                    readonly>
            </div>

          <!-- Input untuk status_pembayaran -->
          <div class="mb-3">
            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
            <input type="text" class="form-control" id="status_pembayaran" name="status_pembayaran" value="Transfer" readonly>
    </div>

            <div class="mb-3">
                <label for="Foto_Transfer" class="form-label">Foto Transfer</label>
                <input type="file" name="Foto_Transfer" class="form-control" accept="image/*">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('booking.index') }}" class="btn btn-secondary">Kembali</a>
        </form>

    </div>
</body>

</html>
