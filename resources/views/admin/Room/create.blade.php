@extends('layouts.app')

@section('title', isset($room) ? 'Edit Ruangan' : 'Tambah Ruangan')

@section('content')
<div class="container mt-5">
    <h1>{{ isset($room) ? 'Edit Ruangan' : 'Tambah Ruangan' }}</h1>

    <form method="POST" action="{{ isset($room) ? route('room.update', $room) : route('room.store') }}">
        @csrf
        @if(isset($room))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="category_hotel_id" class="form-label">Kategori Hotel</label>
            <select class="form-control" id="category_hotel_id" name="category_hotel_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ isset($room) && $room->category_hotel_id == $category->id ? 'selected' : '' }}>
                        {{ $category->nama_kategori }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
            <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" value="{{ $room->nama_ruangan ?? '' }}" required>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($room) ? 'Update' : 'Submit' }}</button>
    </form>

    <a href="{{ route('room.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Ruangan</a>
</div>
@endsection
