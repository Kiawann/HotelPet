<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Hewan</title>
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
        <h1>Tambah Data Hewan</h1>

        <form action="{{ route('data_hewan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Hewan</label>
                <input type="text" name="nama_hewan" class="form-control" value="{{ old('nama_hewan') }}">
                @error('nama_hewan')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Pemilik</label>
                <select name="data_pemilik_id" class="form-control">
                    <option value="">Pilih Pemilik</option>
                    @foreach ($pemilik as $p)
                        <option value="{{ $p->id }}" {{ old('data_pemilik_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
                @error('data_pemilik_id')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Kategori Hewan</label>
                <select name="kategori_hewan_id" class="form-control">
                    <option value="">Pilih Kategori Hewan</option>
                    @foreach ($kategoriHewan as $kategori)
                        <option value="{{ $kategori->id }}" {{ old('kategori_hewan_id') == $kategori->id ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                    @endforeach
                </select>
                @error('kategori_hewan_id')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Umur (bulan)</label>
                <input type="number" name="umur" class="form-control" value="{{ old('umur') }}">
                @error('umur')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Berat Badan (kg)</label>
                <input type="number" name="berat_badan" class="form-control" value="{{ old('berat_badan') }}">
                @error('berat_badan')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-control">
                    <option value="">Pilih Jenis Kelamin</option>
                    <option value="Jantan" {{ old('jenis_kelamin') == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                    <option value="Betina" {{ old('jenis_kelamin') == 'Betina' ? 'selected' : '' }}>Betina</option>
                </select>
                @error('jenis_kelamin')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Warna</label>
                <input type="text" name="warna" class="form-control" value="{{ old('warna') }}">
                @error('warna')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Ras Hewan</label>
                <input type="text" name="ras_hewan" class="form-control" value="{{ old('ras_hewan') }}">
                @error('ras_hewan')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Foto</label>
                <input type="file" name="foto" class="form-control">
                @error('foto')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
