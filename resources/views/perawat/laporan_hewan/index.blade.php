@extends('layouts.perawat')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="h3 mb-4">Seluruh Daftar Laporan Hewan</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Wrapper untuk scrolling horizontal -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <!-- Tambahkan style untuk mengatur lebar minimum kolom -->
                    <style>
                        .table th,
                        .table td {
                            min-width: 120px;
                            /* Lebar minimum setiap kolom */
                            white-space: nowrap;
                            /* Mencegah text wrapping */
                        }

                        .table th:first-child,
                        .table td:first-child {
                            min-width: 50px;
                            /* Kolom nomor lebih kecil */
                        }

                        .table .img-fluid {
                            max-width: 150px;
                            height: auto;
                        }

                        /* Mengatur tampilan pada mobile */
                        @media (max-width: 768px) {
                            .table-responsive {
                                overflow-x: auto;
                                -webkit-overflow-scrolling: touch;
                            }

                            .container-fluid {
                                padding: 10px;
                            }

                            h1 {
                                font-size: 1.5rem;
                            }
                        }
                    </style>

                    <table class="table table-striped table-bordered" id="dataTable">
                        <thead>
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
                                            @foreach (json_decode($laporan->foto) as $foto)
                                                <img src="{{ asset('storage/' . $foto) }}" alt="Foto Hewan"
                                                    class="img-fluid mb-2">
                                            @endforeach
                                        @else
                                            <span>Tidak ada foto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($laporan->video)
                                            @foreach (json_decode($laporan->video) as $video)
                                                <video width="150" controls class="img-fluid">
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
                <div class="mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="{{ $laporanHewan->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($laporanHewan->getUrlRange(1, $laporanHewan->lastPage()) as $page => $url)
                                <li class="page-item {{ $laporanHewan->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item">
                                <a class="page-link" href="{{ $laporanHewan->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                
            </div>
        </div>
    </div>
@endsection
