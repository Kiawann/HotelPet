@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1>Daftar Reservasi Layanan</h1>

    <!-- Tampilkan pesan jika berhasil menambah reservasi -->
    @if(session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('reservasi_layanan.create') }}" class="btn btn-primary">Tambah Reservasi</a>

    <!-- Tabel Daftar Reservasi Layanan -->
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Pemilik</th>
                <th>Status</th>
                <th>Tanggal Reservasi</th>
                {{-- <th>Jumlah Layanan</th> --}}
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reservasiLayanan as $index => $reservasi)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $reservasi->pemilik->nama }}</td>
                    <td>{{ $reservasi->status }}</td>
                    <td>{{ $reservasi->tanggal_reservasi }}</td>
                    <td>
                        <a href="{{ route('reservasi_layanan.show', $reservasi->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ route('reservasi_layanan.edit', $reservasi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('reservasi_layanan.destroy', $reservasi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        <a href="{{ route('transaksi.create', ['reservasi_id' => $reservasi->id]) }}" class="btn btn-success btn-sm">Transaksi</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tampilkan paginasi jika ada -->
    {{ $reservasiLayanan->links() }}
</div>
@endsection
