@extends('layouts.app')

@section('title', 'Tambah Kategori Hotel')

@section('content')
<div class="container mt-4">
    {{-- <h1>{{ isset($categoryHotel) ? 'Edit' : 'Tambah' }} Kategori Hotel</h1> --}}
    
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
        
        <div class="mb-3">
            <label for="foto" class="form-label">Foto</label>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
        
        <button type="submit" class="btn btn-primary">{{ isset($categoryHotel) ? 'Update' : 'Submit' }}</button>
    </form>

    <a href="{{ route('category_hotel.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Kategori Hotel</a>
</div>
@endsection