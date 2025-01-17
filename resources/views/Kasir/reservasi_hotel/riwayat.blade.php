@extends('layouts.kasir')

@section('content')
<div class="container">
    <h1 class="mb-4">Riwayat Reservasi</h1>

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
    @endif
</div>
@endsection
