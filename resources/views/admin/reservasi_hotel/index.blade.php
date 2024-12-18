@extends('layouts.app')

@section('title', 'Daftar Reservasi Hotel')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Reservasi Hotel</h1>
        <a href="{{ route('reservasi_hotel.create') }}" class="btn btn-primary">Tambah Reservasi</a>
    </div>

    <div class="alert alert-success" id="successMessage" style="display: none;">
        Reservasi berhasil dibuat.
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('reservasi_hotel.create') }}" class="btn btn-primary mb-3">Buat Reservasi Baru</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Pemilik</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reservasiHotels as $reservasi)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reservasi->dataPemilik->nama ?? 'Tidak Diketahui' }}</td>
                    <td>{{ $reservasi->status }}</td>
                    <td>
                        <a href="{{ route('reservasi_hotel.edit', $reservasi->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('reservasi_hotel.show', $reservasi->id) }}" class="btn btn-info btn-sm">Show</a>
                        <form action="{{ route('reservasi_hotel.destroy', $reservasi->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                        <a href="{{ route('laporan_hewan.create', ['reservasi_id' => $reservasi->id]) }}" class="btn btn-success btn-sm">Buat Laporan</a>
                        <a href="{{ route('laporan_hewan.laporan', ['reservasiId' => $reservasi->id]) }}" class="btn btn-secondary btn-sm">Lihat Laporan Hewan</a>
                        
                        <!-- Tambahkan button transaksi -->
                        <a href="{{ route('transaksi.create', ['reservasi_hotel_id' => $reservasi->id]) }}" class="btn btn-primary btn-sm">Transaksi</a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
