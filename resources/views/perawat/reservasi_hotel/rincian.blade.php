@extends('layouts.perawat')

@section('title', 'Rincian Reservasi Hotel')

@section('content')
<div class="container-fluid px-4">
    <!-- Card Utama -->
    <div class="card shadow-sm my-4">
        <div class="card-header py-3" style="background-color: #2c3e50;">
            <h3 class="card-title text-white m-0 text-center">Rincian Reservasi Hotel</h3>
        </div>
        <div class="card-body">
            <!-- Informasi Reservasi -->
            <div class="mb-4">
                <h4 class="border-bottom pb-2">Informasi Pemilik</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th class="bg-light" style="width: 200px;">Nama Pemilik</th>
                            <td>{{ $reservasiHotel->dataPemilik->nama ?? 'Tidak ada data pemilik' }}</td>
                        </tr>
                        <tr>
                            <th class="bg-light">Nomor Telepon</th>
                            <td>{{ $reservasiHotel->dataPemilik->nomor_telp ?? 'Tidak ada telepon' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Rincian Ruangan dan Hewan -->
            <div>
                <h4 class="border-bottom pb-2">Rincian Ruangan dan Hewan</h4>
                <form action="{{ route('reservasi_hotel.update_status', $reservasiHotel->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nama Ruangan</th>
                                    <th>Nama Hewan</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Ras</th>
                                    <th>Tanggal Check In</th>
                                    <th>Tanggal Check Out</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservasiHotel->rincianReservasiHotel->groupBy('room_id') as $roomId => $rincianGroup)
                                    @php
                                        $room = $rincianGroup->first()->room;
                                    @endphp
                                    <tr>
                                        <td class="align-middle">{{ $room->nama_ruangan ?? 'Room tidak tersedia' }}</td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">{{ $rincian->dataHewan->nama_hewan ?? 'Tidak ada hewan' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">{{ $rincian->dataHewan->jenis_kelamin ?? 'Tidak diketahui' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">{{ $rincian->dataHewan->ras_hewan ?? 'Tidak diketahui' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">{{ $rincian->reservasiHotel->tanggal_checkin ?? 'Tidak ada tanggal check-in' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">{{ $rincian->reservasiHotel->tanggal_checkout ?? 'Tidak ada tanggal check-out' }}</div>
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach($rincianGroup as $rincian)
                                                <div class="py-1">
                                                    <span class="badge {{ $rincian->status == 'check in' ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ $rincian->status ?? 'Tidak diketahui' }}
                                                    </span>
                                                </div>
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="text-center mb-4">
        <a href="{{ route('perawat-reservasi-hotel.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .card-header h3 {
            font-size: 1.25rem;
        }
        h4 {
            font-size: 1.1rem;
        }
        .table th, .table td {
            white-space: nowrap;
            min-width: 120px;
        }
        .container-fluid {
            padding: 1rem;
        }
        .py-1 {
            padding: 0.25rem 0;
        }
    }
    
    .table td.align-middle > div:not(:last-child) {
        border-bottom: 1px solid #dee2e6;
    }
    
    .badge {
        font-size: 0.875rem;
        padding: 0.35em 0.65em;
    }
</style>
@endsection