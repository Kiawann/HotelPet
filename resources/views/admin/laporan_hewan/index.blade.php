@extends('layouts.app')

@section('content')
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <h1 class="mb-4">Daftar Laporan Hewan</h1>
                <a href="{{ route('laporan_hewan.create') }}" class="btn btn-primary mb-3">Tambah Laporan</a>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <table class="table table-striped table-bordered">
                    <thead class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <tr>
                            <th>#</th>
                            <th>Id Reservasi</th>
                            <th>Hewan</th>
                            <th>Room</th>
                            <th>Makan</th>
                            <th>Bab</th>
                            <th>Bak</th>
                            <th>Keterangan</th>
                            <th>Foto</th>
                            <th>Tanggal Laporan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanHewan as $laporan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $laporan->reservasiHotel->id ?? '-' }}</td>
                                <td>{{ $laporan->dataHewan->nama_hewan ?? '-' }}</td>
                                <td>{{ $laporan->room->nama_ruangan ?? '-' }}</td>
                                <td>{{ $laporan->Makan }}</td>
                                <td>{{ $laporan->Bab }}</td>
                                <td>{{ $laporan->Bak }}</td>
                                <td>{{ $laporan->keterangan }}</td>
                                <td>
                                    <!-- Menampilkan foto jika ada -->
                                    @if ($laporan->foto)
                                    <img src="{{ asset('storage/' . $laporan->foto) }}" alt="Foto Hewan" class="img-fluid">
        
                                    @else
                                        <span>Tidak ada foto</span>
                                    @endif
                                </td>
                                <td>{{ $laporan->tanggal_laporan }}</td>
                                <td>
                                    <a href="{{ route('laporan_hewan.show', $laporan->id) }}"
                                        class="btn btn-info btn-sm">Detail</a>
                                    <a href="{{ route('laporan_hewan.edit', $laporan->id) }}"
                                        class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('laporan_hewan.destroy', $laporan->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Hapus laporan ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
