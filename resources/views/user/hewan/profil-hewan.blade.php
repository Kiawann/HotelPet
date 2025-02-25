<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Hewan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* body {
            padding-top: 80px;
            background-color: #f0f2f5;
        } */

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 40px;
            font-weight: bold;
            color: #333;
        }

        .card-wrapper {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            justify-items: center;
            margin-top: 40px;
        }

        .card-item {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            width: 100%;
            max-width: 350px;
        }

        .card-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
        }

        .card-item h3 {
            margin-top: 15px;
            font-size: 24px;
            color: #333;
            font-weight: 600;
        }

        .card-item p {
            font-size: 14px;
            color: #666;
            margin: 8px 0;
        }

        .card-item .btn {
            padding: 8px 15px;
            font-size: 14px;
            border-radius: 25px;
            margin: 5px;
        }

        .no-data {
            text-align: center;
            color: #888;
            font-size: 16px;
            font-style: italic;
        }

        .add-button {
            display: block;
            text-align: center;
            margin-top: 40px;
        }

        .add-button a {
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .add-button a:hover {
            background-color: #218838;
        }

        /* Alert Styles */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 16px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-dismissible {
            position: relative;
            padding-right: 4rem;
        }

        .alert-dismissible .btn-close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 1.25rem 1rem;
        }

        /* Button Styles */
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-info {
            background-color: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background-color: #138496;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <!-- Main Content -->
    <div class="container"><br><br>
        <!-- Flash Messages -->
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

        <div class="header"><br><br>
            <h1><i class="fas fa-paw"></i> Data Hewan Peliharaan Saya</h1>
        </div>

        <div class="card-wrapper">
            @forelse($hewans as $hewan)
            <div class="card-item">
                @if ($hewan->foto)
                <img src="{{ asset('storage/' . $hewan->foto) }}" alt="Hewan Avatar">
                @else
                <img src="https://via.placeholder.com/350x350?text=No+Image" alt="No Image">
                @endif
                <h3>{{ $hewan->nama_hewan }}</h3>
                <p><strong>Kategori:</strong> {{ $hewan->kategoriHewan->nama_kategori }}</p>

                <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $hewan->id }}">
                    <i class="fas fa-info-circle"></i> Detail
                </button>

                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                    data-bs-target="#editModal{{ $hewan->id }}">
                    <i class="fas fa-edit"></i> Edit
                </button>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                    data-bs-target="#deleteModal{{ $hewan->id }}">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>

            <!-- Modal Detail -->
            <div class="modal fade" id="detailModal{{ $hewan->id }}" tabindex="-1"
                aria-labelledby="detailModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailModalLabel">
                                <i class="fas fa-info-circle"></i> Detail Hewan: {{ $hewan->nama_hewan }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Nama:</strong> {{ $hewan->nama_hewan }}</p>
                            <p><strong>Kategori:</strong> {{ $hewan->kategoriHewan->nama_kategori }}</p>
                            <p><strong>Umur:</strong> {{ $hewan->umur }} bulan</p>
                            <p><strong>Berat Badan:</strong> {{ $hewan->berat_badan }} kg</p>
                            <p><strong>Jenis Kelamin:</strong> {{ $hewan->jenis_kelamin }}</p>
                            <p><strong>Warna:</strong> {{ $hewan->warna }}</p>
                            <p><strong>Ras:</strong> {{ $hewan->ras_hewan }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal{{ $hewan->id }}" tabindex="-1"
                aria-labelledby="editModalLabel{{ $hewan->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel{{ $hewan->id }}">
                                <i class="fas fa-edit"></i> Edit Hewan: {{ $hewan->nama_hewan }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="{{ route('hewan.update', $hewan->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="nama_hewan{{ $hewan->id }}" class="form-label">Nama Hewan</label>
                                    <input type="text" class="form-control" id="nama_hewan{{ $hewan->id }}"
                                        name="nama_hewan" value="{{ $hewan->nama_hewan }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="kategori_hewan_id{{ $hewan->id }}" class="form-label">Kategori Hewan</label>
                                    <select class="form-select" id="kategori_hewan_id{{ $hewan->id }}"
                                        name="kategori_hewan_id" required>
                                        @foreach ($kategoriHewan as $kategori)
                                            <option value="{{ $kategori->id }}"
                                                {{ $hewan->kategori_hewan_id == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="umur{{ $hewan->id }}" class="form-label">Umur (Bulan)</label>
                                    <input type="number" class="form-control" id="umur{{ $hewan->id }}"
                                        name="umur" value="{{ $hewan->umur }}" required min="1">
                                </div>

                                <div class="mb-3">
                                    <label for="berat_badan{{ $hewan->id }}" class="form-label">Berat Badan (Kg)</label>
                                    <input type="number" class="form-control" id="berat_badan{{ $hewan->id }}"
                                        name="berat_badan" value="{{ $hewan->berat_badan }}" required
                                        min="0.1" step="0.1">
                                </div>

                                <div class="mb-3">
                                    <label for="jenis_kelamin{{ $hewan->id }}" class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" id="jenis_kelamin{{ $hewan->id }}"
                                        name="jenis_kelamin" required>
                                        <option value="Jantan" {{ $hewan->jenis_kelamin == 'Jantan' ? 'selected' : '' }}>
                                            Jantan
                                        </option>
                                        <option value="Betina" {{ $hewan->jenis_kelamin == 'Betina' ? 'selected' : '' }}>
                                            Betina
                                        </option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="warna{{ $hewan->id }}" class="form-label">Warna</label>
                                    <input type="text" class="form-control" id="warna{{ $hewan->id }}"
                                        name="warna" value="{{ $hewan->warna }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="ras_hewan{{ $hewan->id }}" class="form-label">Ras Hewan</label>
                                    <input type="text" class="form-control" id="ras_hewan{{ $hewan->id }}"
                                        name="ras_hewan" value="{{ $hewan->ras_hewan }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="foto{{ $hewan->id }}" class="form-label">Foto Hewan</label>
                                    <input type="file" class="form-control" id="foto{{ $hewan->id }}"
                                        name="foto" accept="image/*">
                                </div>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

         
            <div class="modal fade" id="deleteModal{{ $hewan->id }}" tabindex="-1"
                aria-labelledby="deleteModalLabel{{ $hewan->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel{{ $hewan->id }}">
                                Konfirmasi Hapus
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @php
                                $hasReservation = \App\Models\RincianReservasiHotel::where('data_hewan_id', $hewan->id)->exists();
                            @endphp
                            
                            @if($hasReservation)
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Data hewan ini tidak dapat dihapus karena memiliki riwayat reservasi hotel.
                                </div>
                            @else
                                <p>Apakah Anda yakin ingin menghapus data hewan {{ $hewan->nama_hewan }}?</p>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            @if(!$hasReservation)
                                <form action="{{ route('hewan.destroy', $hewan->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="no-data">Tidak ada data hewan yang ditemukan.</p>
            @endforelse
        </div>

        <div class="add-button">
            <a href="{{ route('hewan.create') }}"><i class="fas fa-plus-circle"></i> Tambah Hewan</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3