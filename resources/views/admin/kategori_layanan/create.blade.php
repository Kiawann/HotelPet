@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Kategori Layanan</h1>

    <form action="{{ route('kategori_layanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Nama Layanan</label>
            <input type="text" name="nama_layanan" class="form-control" value="{{ old('nama_layanan') }}">
            @error('nama_layanan')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <label>Deskripsi</label>
            <input type="text" name="deskripsi" class="form-control" value="{{ old('deskripsi') }}">
            @error('deskripsi')
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
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" value="{{ old('harga') }}">
            @error('harga')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
