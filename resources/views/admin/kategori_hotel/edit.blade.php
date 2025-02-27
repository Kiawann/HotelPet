@extends('layouts.app')

@section('title', 'Edit Kategori Hotel')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Edit Kategori Hotel</div>

                <div class="card-body" style="max-height: 80vh; overflow-y: auto;">
                    <form action="{{ route('category_hotel.update', $categoryHotel->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $categoryHotel->nama_kategori) }}" required>
                                    @error('nama_kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="harga" class="form-label">Harga</label>
                                    <input type="number" class="form-control @error('harga') is-invalid @enderror" id="harga" name="harga" value="{{ old('harga', $categoryHotel->harga) }}" required>
                                    @error('harga')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="foto" class="form-label">Foto</label>
                                    <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto">
                                    @if ($categoryHotel->foto)
                                        <img src="{{ asset('storage/' . $categoryHotel->foto) }}" class="mt-2 img-thumbnail" width="100" alt="Foto Kategori">
                                    @endif
                                    @error('foto')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" required>{{ old('deskripsi', $categoryHotel->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="jumlah_ruangan" class="form-label">Jumlah Ruangan</label>
                                    <input type="number" class="form-control @error('jumlah_ruangan') is-invalid @enderror" id="jumlah_ruangan" name="jumlah_ruangan" value="{{ old('jumlah_ruangan', $categoryHotel->jumlah_ruangan) }}" required>
                                    @error('jumlah_ruangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('category_hotel.index') }}" class="btn btn-secondary">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
