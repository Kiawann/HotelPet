@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card">
            <!-- Notifikasi Sukses -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-header">
                <h3>Detail Reservasi Layanan</h3>
            </div>
            <div class="card-body">
                <!-- Informasi Pemilik -->
                <h5>Informasi Pemilik</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama Pemilik</th>
                        <td>{{ $reservasi->pemilik->nama }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon</th>
                        <td>{{ $reservasi->pemilik->nomor_telp }}</td>
                    </tr>
                </table>

                <!-- Informasi Hewan -->
                <h5 class="mt-4">Informasi Hewan</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Hewan</th>
                            <th>Jenis Kelamin</th>
                            <th>Ras</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservasi->rincian->groupBy('data_hewan_id') as $hewanGroup)
                            @php
                                $hewan = $hewanGroup->first();
                            @endphp
                            <tr>
                                <td>{{ $hewan->hewan->nama_hewan }}</td>
                                <td>{{ $hewan->hewan->jenis_kelamin }}</td>
                                <td>{{ $hewan->hewan->ras_hewan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Informasi Layanan Grooming -->
                <h5 class="mt-4">Layanan Lainnya</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama Layanan</th>
                            <th>Tanggal Layanan</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservasi->rincian as $rincian)
                            @if ($rincian->layanan->nama_layanan !== 'Cat In (A)')
                                <tr>
                                    <td>{{ $rincian->layanan->nama_layanan }}</td>
                                    <td>{{ \Carbon\Carbon::parse($rincian->tanggal_layanan)->format('d-m-Y') }}</td>
                                    <td>Rp {{ number_format($rincian->layanan->harga, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <!-- Status Reservasi -->
                <h5 class="mt-4">Status Reservasi</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Status</th>
                        <td>{{ $reservasi->status }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Reservasi</th>
                        <td>{{ \Carbon\Carbon::parse($reservasi->tanggal_reservasi)->format('d-m-Y') }}</td>
                    </tr>
                </table>

                <!-- Subtotal -->
                <h5 class="mt-4">Subtotal</h5>
                <table class="table table-bordered">
                    <tr>
                        <th>Total Harga Layanan</th>
                        <td>Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    </tr>
                </table>

                <!-- Tombol Kembali -->
                <div class="mt-4">
                    <a href="{{ route('reservasi_layanan.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
