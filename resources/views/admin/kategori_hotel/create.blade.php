<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Hotel</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Tambah Kategori Hotel</h1>

    <form method="POST" action="{{ isset($categoryHotel) ? route('category_hotel.update', $categoryHotel) : route('category_hotel.store') }}" enctype="multipart/form-data">
    @csrf
    @if(isset($categoryHotel))
        @method('PUT')
    @endif

    <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" value="{{ $categoryHotel->nama_kategori ?? '' }}" required>
    </div>
    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" required>{{ $categoryHotel->deskripsi ?? '' }}</textarea>
    </div>
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" class="form-control" id="harga" name="harga" value="{{ $categoryHotel->harga ?? '' }}" required>
    </div>
    {{-- <div class="mb-3">
        <label for="jumlah_ruangan" class="form-label">Jumlah Ruangan</label>
        <input type="number" class="form-control" id="jumlah_ruangan" name="jumlah_ruangan" value="{{ $categoryHotel->jumlah_ruangan ?? '' }}" required>
    </div> --}}
    <div class="mb-3">
        <label for="foto" class="form-label">Foto</label>
        <input type="file" class="form-control" id="foto" name="foto">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>


    <a href="{{ route('kategori_hewan.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori Hewan</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
