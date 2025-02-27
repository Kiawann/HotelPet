@extends('layouts.kasir')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Transaksi Denda</div>

                <div class="card-body">
                    <table class="table">
                        <tr>
                            <th>ID Reservasi</th>
                            <td>{{ $denda->reservasi->id ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Nama Pemilik</th>
                            <td>{{ $denda->reservasi->dataPemilik->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Total Denda</th>
                            <td>Rp {{ number_format($denda->jumlah_denda, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <th>Status Pembayaran</th>
                            <td>{{ $denda->status_pembayaran }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Pembayaran</th>
                            <td>{{ $denda->tanggal_pembayaran ? \Carbon\Carbon::parse($denda->tanggal_pembayaran)->format('d-m-Y') : '-' }}</td>
                        </tr>
                        @if($denda->status_pembayaran === 'Cash')
                        <tr>
                            <th>Dibayar</th>
                            <td>Rp {{ number_format($denda->Dibayar, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr>
                            <th>Bukti Pembayaran</th>
                            <td>
                                @if($denda->bukti_pembayaran)
                                    <img src="{{ asset('storage/' . $denda->bukti_pembayaran) }}" alt="Bukti Pembayaran" width="200">
                                @else
                                    Tidak ada bukti pembayaran
                                @endif
                            </td>
                        </tr>
                    @endif
                    
                    </table>
                    <a href="{{ route('transaksi-denda-index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
