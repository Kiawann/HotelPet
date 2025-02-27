@extends('layouts.kasir')

@section('content')
<div class="container scrollable-container">
    <h1 class="mb-4">Detail Riwayat Reservasi</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Informasi Reservasi</h5>
            <p><strong>Nama Pemilik:</strong> {{ $reservasi->dataPemilik->nama ?? 'Tidak tersedia' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($reservasi->status) }}</p>
            <p><strong>Tanggal Check-in:</strong> {{ $reservasi->tanggal_checkin }}</p>
            <p><strong>Tanggal Check-out:</strong> {{ $reservasi->tanggal_checkout }}</p>
            <p><strong>Total:</strong> Rp{{ number_format($reservasi->Total, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="mt-4">
        <h5>Rincian Reservasi</h5>
        @if($reservasi->rincianReservasiHotel->isEmpty())
            <div class="alert alert-info">Tidak ada rincian reservasi.</div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Hewan</th>
                            <th>Kamar</th>
                            <th>SubTotal</th>
                            <th>Denda</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservasi->rincianReservasiHotel as $key => $rincian)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $rincian->dataHewan->nama_hewan ?? 'Tidak tersedia' }}</td>
                                <td>{{ $rincian->room->nama_ruangan ?? 'Tidak tersedia' }}</td>
                                <td>Rp{{ number_format($rincian->SubTotal, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($rincian->denda, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($rincian->status) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <div class="mt-4">
        <h5>Detail Transaksi</h5>
        @if($reservasi->transaksi)
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        @if($reservasi->transaksi->status_pembayaran === 'Transfer')
                            <tr>
                                <th>Subtotal</th>
                                <td>Rp{{ number_format($reservasi->transaksi->Subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    <span class="badge {{ $reservasi->transaksi->status_pembayaran === 'lunas' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($reservasi->transaksi->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Bukti Transfer</th>
                                <td>
                                    @if($reservasi->transaksi->Foto_Transfer)
                                        <a href="{{ asset('storage/' . $reservasi->transaksi->Foto_Transfer) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $reservasi->transaksi->Foto_Transfer) }}" alt="Bukti Transfer" style="max-height: 100px;">
                                        </a>
                                    @else
                                        <span class="text-muted">Belum tersedia</span>
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <th>ID Transaksi</th>
                                <td>{{ $reservasi->transaksi->id }}</td>
                            </tr>
                            <tr>
                                <th>Nama Pemilik</th>
                                <td>{{ $reservasi->transaksi->dataPemilik->nama ?? 'Tidak tersedia' }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Pembayaran</th>
                                <td>{{ $reservasi->transaksi->tanggal_pembayaran ?? 'Belum dibayar' }}</td>
                            </tr>
                            <tr>
                                <th>Subtotal</th>
                                <td>Rp{{ number_format($reservasi->transaksi->Subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Dibayar</th>
                                <td>Rp{{ number_format($reservasi->transaksi->Dibayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Kembalian</th>
                                <td>Rp{{ number_format($reservasi->transaksi->Kembalian, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Status Pembayaran</th>
                                <td>
                                    <span class="badge {{ $reservasi->transaksi->status_pembayaran === 'lunas' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($reservasi->transaksi->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info">Tidak ada data transaksi untuk reservasi ini.</div>
        @endif
    
        <div class="mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

<style>
    .scrollable-container {
        max-height: 80vh; /* Batasi tinggi agar tidak melebihi viewport */
        overflow-y: auto; /* Aktifkan scroll jika konten terlalu panjang */
        padding-right: 10px;
    }
</style>
@endsection
