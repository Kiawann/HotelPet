@extends('layouts.app')

@section('title', 'Daftar Kategori Hotel')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-primary">Daftar Kategori Hotel</h1>
        <a href="{{ route('category_hotel.create') }}" class="btn btn-primary">Tambah Kategori</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Jumlah Ruangan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $category->nama_kategori }}</td>
                    <td>{{ $category->deskripsi }}</td>
                    <td>Rp{{ number_format($category->harga, 0, ',', '.') }}</td>
                    <td>{{ $category->jumlah_ruangan }}</td>
                    <td>
                        @if($category->foto)
                            <img src="{{ asset('storage/' . $category->foto) }}" alt="Foto {{ $category->nama_kategori }}" style="width: 75px; height: 50px; object-fit: cover;">
                        @else
                            <span class="text-muted">Tidak Ada Foto</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('category_hotel.edit', $category) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('category_hotel.destroy', $category) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data kategori hotel.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
