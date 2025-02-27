@extends('layouts.kasir')
@section('title', 'Riwayat Reservasi Hotel')

@section('content')
<div class="container">

    @if($reservasi->isEmpty())
        <div class="alert alert-info">Belum ada riwayat reservasi.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Pemilik</th>
                    <th>Check-in</th>
                    <th>Check-out</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservasi as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->dataPemilik->nama ?? 'Tidak tersedia' }}</td>
                        <td>{{ $data->tanggal_checkin }}</td>
                        <td>{{ $data->tanggal_checkout }}</td>
                        <td>Rp{{ number_format($data->Total, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($data->status) }}</td>
                        <td>
                            <a href="{{ route('riwayat.reservasi-detail', $data->id) }}" class="btn btn-info btn-sm">
                                Lihat Rincian
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    <li class="page-item">
                        <a class="page-link" href="{{ $reservasi->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($reservasi->getUrlRange(1, $reservasi->lastPage()) as $page => $url)
                        <li class="page-item {{ $reservasi->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="{{ $reservasi->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    @endif
</div>
@endsection
