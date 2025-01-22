@extends('layouts.perawat')

@section('title', 'Daftar Laporan Hewan')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Daftar Laporan Hewan</h1>
    <a href="{{ route('perawat-reservasi-hotel.index', [
    'status' => request('status'),
    'date_filter' => request('date_filter')
]) }}" class="btn btn-primary mb-3">Kembali</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $currentDate = null;
    @endphp

    @foreach ($laporanHewan->groupBy('tanggal_laporan') as $tanggal => $laporanGroup)
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <strong>Tanggal: {{ \Carbon\Carbon::parse($tanggal)->format('d M Y') }}</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-bordered mb-0">
                    <thead class="table-dark">   
                        <tr>
                            <th>#</th>
                            <th>Reservasi  </th>
                            <th>Hewan</th>
                            <th>Room</th>
                            <th>Makan</th>
                            <th>Bab</th>
                            <th>Bak</th>
                            <th>Keterangan</th>
                            <th>Foto</th>
                            <th>Video</th>
                            {{-- <th>Aksi</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanGroup as $laporan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $laporan->reservasiHotel->id ?? '-' }}</td>
                                <td>{{ $laporan->dataHewan->nama_hewan ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $laporan->room->nama_ruangan ?? 'Tidak Diketahui' }}</td>
                                <td>{{ $laporan->Makan }}</td>
                                <td>{{ $laporan->Bab }}</td>
                                <td>{{ $laporan->Bak }}</td>
                                <td>{{ $laporan->keterangan }}</td>

                                <td>
                                    @if ($laporan->foto)
                                        @foreach(json_decode($laporan->foto) as $foto)
                                            <img src="{{ asset('storage/' . $foto) }}" alt="Foto Hewan" class="img-fluid mb-2" style="max-width: 150px;">
                                        @endforeach
                                    @else
                                        <span>Tidak ada foto</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($laporan->video)
                                        @foreach(json_decode($laporan->video) as $video)
                                            <video width="150" controls>
                                                <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endforeach
                                    @else
                                        <span>Tidak ada video</span>
                                    @endif
                                </td>

                                {{-- <td>
                                    <a href="{{ route('laporan_hewan.edit', $laporan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                </td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
</div>
@endsection
