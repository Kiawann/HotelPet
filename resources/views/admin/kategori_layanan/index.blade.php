@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Kategori Layanan</h1>
    <a href="{{ route('kategori_layanan.create') }}" class="btn btn-primary">Tambah Kategori</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID Kategori Layanan</th>
                <th>Nama Layanan</th>
                <th>Deskripsi</th>
                <th>Foto</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoriLayanan as $layanan)
                <tr>
                    <td>{{ $layanan->id }}</td>
                    <td>{{ $layanan->nama_layanan }}</td>
                    <td>{{ $layanan->deskripsi }}</td>
                    <td>
                        @if ($layanan->foto)
                            <img src="{{ asset('storage/' . $layanan->foto) }}" alt="Foto" width="100">
                        @else
                            <p>Tidak ada foto</p>
                        @endif
                    </td>
                    <td>{{ $layanan->harga }}</td>
                    <td>
                        <a href="{{ route('kategori_layanan.edit', $layanan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('kategori_layanan.destroy', $layanan->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
