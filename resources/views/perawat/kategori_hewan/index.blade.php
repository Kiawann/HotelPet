@extends('layouts.perawat')

@section('title', 'Data Kategori Hewan')

@section('content')
    {{-- <h1>Data Kategori Hewan</h1> --}}
    {{-- <a href="{{ route('kategori_hewan.create') }}" class="btn btn-primary">Tambah Kategori</a>
     --}}
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID Kategori Hewan</th>
                <th>Nama Kategori</th>
                {{-- <th>Aksi</th> --}}
            </tr>
        </thead>
        <tbody>
            @foreach ($kategoriHewan as $kategori)
                <tr>
                    <td>{{ $kategori->id }}</td>
                    <td>{{ $kategori->nama_kategori }}</td>
                    {{-- <td>
                        <a href="{{ route('kategori_hewan.edit', $kategori->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        
                        <form action="{{ route('kategori_hewan.destroy', $kategori->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>                            
                    </td> --}}
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
