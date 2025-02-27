@extends('layouts.kasir')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Daftar Transaksi Denda</div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>ID Reservasi</th>
                                    <th>Nama Pemilik</th>
                                    <th>Total Denda</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaksiDenda as $key => $denda)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $denda->reservasi->id ?? '-' }}</td>
                                        <td>{{ $denda->reservasi->dataPemilik->nama ?? '-' }}</td>
                                        <td>Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</td>
                                        <td>{{ $denda->created_at->format('d-m-Y') }}</td>
                                        <td>
                                            <a href="{{ route('kasir-transaksi-denda-show', $denda->id) }}"
                                                class="btn btn-info btn-sm">Detail</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <nav aria-label="Page navigation">
                                <ul class="pagination pagination-sm">
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transaksiDenda->previousPageUrl() }}"
                                            aria-label="Previous">
                                            <span aria-hidden="true">&laquo;</span>
                                        </a>
                                    </li>
                                    @foreach ($transaksiDenda->getUrlRange(1, $transaksiDenda->lastPage()) as $page => $url)
                                        <li
                                            class="page-item {{ $transaksiDenda->currentPage() == $page ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endforeach
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $transaksiDenda->nextPageUrl() }}"
                                            aria-label="Next">
                                            <span aria-hidden="true">&raquo;</span>
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
