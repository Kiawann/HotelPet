@extends('layouts.app')

@section('title', 'Tambah Kategori Hewan')

@section('content')
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
@endsection
