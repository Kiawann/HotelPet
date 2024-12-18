@extends('layouts.app')

@section('title', 'Data Hewan')

@section('content')
    <h1>Data Hewan</h1>
    <a href="{{ route('data_hewan.create') }}" class="btn btn-primary">Tambah Data Hewan</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Nama Hewan</th>
                <th>Pemilik</th>
                <th>Kategori Hewan</th>
                <th>Umur (bulan)</th>
                <th>Berat Badan (kg)</th>
                <th>Jenis Kelamin</th>
                <th>Warna</th>
                <th>Ras Hewan</th>
                <th>Foto</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataHewan as $hewan)
            <tr>
                <td>{{ $hewan->nama_hewan }}</td>
                <td>{{ $hewan->pemilik->nama ?? 'Tidak ada pemilik' }}</td>
                <td>{{ $hewan->kategoriHewan->nama_kategori ?? 'Tidak ada kategori' }}</td>
                <td>{{ $hewan->umur }}</td>
                <td>{{ $hewan->berat_badan}}</td>
                <td>{{ $hewan->jenis_kelamin }}</td>
                <td>{{ $hewan->warna }}</td>
                <td>{{ $hewan->ras_hewan }}</td>
                <td>
                    @if ($hewan->foto)
                        <img src="{{ asset('storage/' . $hewan->foto) }}" alt="Foto" width="50">
                    @else
                        Tidak ada foto
                    @endif
                </td>
                <td>
                    <a href="{{ route('data_hewan.edit', $hewan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('data_hewan.destroy', $hewan->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection