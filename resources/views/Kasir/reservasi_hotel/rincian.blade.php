@extends('layouts.kasir')

@section('title', 'Rincian Reservasi Hotel')

@section('content')
<div class="container mt-5">

    <!-- Semua Rincian dalam Satu Card -->
    <div class="card my-4">
        <div class="card-header" style="background-color: #2c3e50; color: #fff;">
            <h3 class="card-title text-center">Rincian Reservasi Hotel</h3>
        </div>
        <div class="card-body">
            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif
    
            <!-- Informasi Reservasi -->
            <div class="mb-4">
                <h4>Informasi Reservasi</h4>
                <table class="table table-bordered table-striped">
                    <tr>
                        <th style="width: 200px;">Nama Pemilik</th>
                        <td>{{ $reservasiHotel->dataPemilik->nama ?? 'Tidak ada data pemilik' }}</td>
                    </tr>
                    <tr>
                        <th>Nomor Telepon</th>
                        <td>{{ $reservasiHotel->dataPemilik->nomor_telp ?? 'Tidak ada telepon' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Rincian Ruangan dan Hewan -->
            <div>
                <h4>Rincian Ruangan dan Hewan</h4>
                <form action="{{ route('reservasi_hotel.update_status', $reservasiHotel->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                            <tr>
                                
                                <th>Nama Ruangan</th>
                                <th>Harga per Malam</th>
                                <th>Nama Hewan</th>
                                <th>Jenis Kelamin</th>
                                <th>Ras</th>
                                <th>Tanggal Check In</th>
                                <th>Tanggal Check Out</th>
                                <th>Status</th>
                                <th>Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalHarga = 0; // Variabel untuk menghitung total harga
                            @endphp
                            @foreach($reservasiHotel->rincianReservasiHotel->groupBy('room_id') as $roomId => $rincianGroup)
                                @php
                                    $room = $rincianGroup->first()->room;
                                    $subtotal = $rincianGroup->sum('SubTotal');
                                    $totalHarga += $subtotal; // Tambahkan subtotal ke total harga
                                @endphp
                                <tr>
                                   
                                    <td>{{ $room->nama_ruangan ?? 'Room tidak tersedia' }}</td>
                                    <td>Rp {{ number_format($room->category_hotel->harga, 0, ',', '.') }}</td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->dataHewan->nama_hewan ?? 'Tidak ada hewan' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->dataHewan->jenis_kelamin ?? 'Tidak diketahui' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->dataHewan->ras_hewan ?? 'Tidak diketahui' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->reservasiHotel->tanggal_checkin ?? 'Tidak ada tanggal check-in' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->reservasiHotel->tanggal_checkout ?? 'Tidak ada tanggal check-out' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->status ?? 'Tidak diketahui' }}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($rincianGroup as $rincian)
                                            Rp {{ number_format($rincian->denda, 0, ',', '.') }}<br>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="text-right">
                        @if($reservasiHotel->transaksi) <!-- Cek apakah sudah ada transaksi -->
                            <a href="{{ route('transaksi-struk', ['reservasi_hotel_id' => $reservasiHotel->id]) }}" 
                               class="btn btn-info btn-sm d-inline-flex align-items-center">
                                <i class="fas fa-receipt me-2"></i> Lihat Struk
                            </a>
                        @else
                            <a href="{{ route('transaksi-create', $reservasiHotel->id) }}?status={{ request('status') }}&date_filter={{ request('date_filter') }}" 
                               class="btn btn-primary btn-sm d-inline-flex align-items-center">
                                <i class="fas fa-credit-card me-2"></i> Pembayaran
                            </a>
                        @endif
                    </div>
                    
                </form>
            </div>

            <!-- Menampilkan Total Harga -->
            <div class="mt-4 text-right">
                <h5>Total Harga: Rp {{ number_format($totalHarga, 0, ',', '.') }}</h5>
            </div>

        </div>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('kasir-reservasi-hotel.index', [
                'status' => request('status', ''),
                'date_filter' => request('date_filter', '')
            ]) }}" class="btn btn-primary btn-lg">Kembali</a>
    </div>
</div>

<script>
  //
</script>
@endsection
