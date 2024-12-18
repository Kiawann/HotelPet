@extends('layouts.app')

@section('title', 'Edit Kategori Hewan')

@section('content')
    <h1>Edit Kategori Hewan</h1>

    <form action="{{ route('kategori_hewan.update', $kategoriHewan->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" name="nama_kategori" class="form-control"
                value="{{ old('nama_kategori', $kategoriHewan->nama_kategori) }}" >
            @error('nama_kategori')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
@endsection
