<!-- resources/views/auth/data_pemilik.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Data Pemilik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Form Data Pemilik</h2>

        <!-- Pesan Error Global -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.store', ['step' => 2]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Form Data Pemilik -->
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Pemilik</label>
                <input 
                    type="text" 
                    class="form-control @error('nama') is-invalid @enderror" 
                    id="nama" 
                    name="nama" 
                    value="{{ old('nama') }}">
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                <select 
                    class="form-select @error('jenis_kelamin') is-invalid @enderror" 
                    id="jenis_kelamin" 
                    name="jenis_kelamin">
                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                </select>
                @error('jenis_kelamin')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nomor_telp" class="form-label">Nomor Telepon</label>
                <input 
                    type="text" 
                    class="form-control @error('nomor_telp') is-invalid @enderror" 
                    id="nomor_telp" 
                    name="nomor_telp" 
                    value="{{ old('nomor_telp') }}">
                @error('nomor_telp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto Pemilik</label>
                <input 
                    type="file" 
                    class="form-control @error('foto') is-invalid @enderror" 
                    id="foto" 
                    name="foto">
                @error('foto')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Selesai dan Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
