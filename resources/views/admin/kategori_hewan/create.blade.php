<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Hewan</title>
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
    <h1>Tambah Kategori Hewan</h1>

    <form action="{{ route('kategori_hewan.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Kategori Hewan</label>
            <input type="text" name="nama_kategori" class="form-control" >
            @error('nama_kategori')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <a href="{{ route('kategori_hewan.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori Hewan</a>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
