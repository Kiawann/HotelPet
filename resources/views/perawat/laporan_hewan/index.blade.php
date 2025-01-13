@extends('layouts.perawat')

@section('content')
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <h1 class="mb-4">Seluruh Daftar Laporan Hewan</h1>

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
                            <th>Video</th>
                            <th>Tanggal Laporan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($laporanHewan as $laporan)
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

                                <td>{{ $laporan->tanggal_laporan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </div>
    </div>
@endsection
