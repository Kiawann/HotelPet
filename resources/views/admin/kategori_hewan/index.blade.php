@extends('layouts.app')

@section('title', 'Data Kategori Hewan')

@section('content')
    <a href="{{ route('kategori_hewan.create') }}" class="btn btn-primary">Tambah Kategori</a>

    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; margin-top: 10px;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID Kategori Hewan</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoriHewan as $kategori)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kategori->id }}</td>
                        <td>{{ $kategori->nama_kategori }}</td>
                        <td>
                            <a href="{{ route('kategori_hewan.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kategori_hewan.destroy', $kategori->id) }}" method="POST" style="display:inline;">
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
