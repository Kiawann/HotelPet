<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Hewan Baru</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .card-body {
            background-color: white;
            padding: 30px;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <div class="container"><br> 
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Tambah Hewan Baru</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('hewan.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="nama_hewan" class="form-label">Nama Hewan</label>
                                <input type="text" class="form-control" id="nama_hewan" name="nama_hewan" required>
                            </div>
                            <div class="mb-3">
                                <label for="kategori_hewan_id" class="form-label">Kategori Hewan</label>
                                <select class="form-select" id="kategori_hewan_id" name="kategori_hewan_id" required>
                                    <option value="">Pilih Kategori Hewan</option>
                                    @foreach ($kategoriHewan as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mb-3">
                                <label for="umur" class="form-label">Umur (Bulan)</label>
                                <input type="number" class="form-control" id="umur" name="umur" required
                                    min="1">
                            </div>

                            <div class="mb-3">
                                <label for="berat_badan" class="form-label">Berat Badan (Kg)</label>
                                <input type="number" class="form-control" id="berat_badan" name="berat_badan"
                                    required min="0.1" step="0.1">
                            </div>

                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Jantan">Jantan</option>
                                    <option value="Betina">Betina</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="warna" class="form-label">Warna</label>
                                <input type="text" class="form-control" id="warna" name="warna" required>
                            </div>

                            <div class="mb-3">
                                <label for="ras_hewan" class="form-label">Ras Hewan</label>
                                <input type="text" class="form-control" id="ras_hewan" name="ras_hewan" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto Hewan</label>
                                <input type="file" class="form-control" id="foto" name="foto"
                                    accept="image/*">
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary d-flex align-items-center justify-content-center"
                                   style="height: 40px; padding: 0 15px; flex: 1;">
                                    <i class="fas fa-arrow-left me-2"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" style="height: 40px; padding: 0 15px; flex: 1;">
                                    Tambah Hewan
                                </button>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
