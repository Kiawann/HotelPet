@extends('layouts.perawat')

@section('title', 'Rincian Reservasi Hotel')

@section('content')
<div class="container mt-5">

    <!-- Semua Rincian dalam Satu Card -->
    <div class="card my-4">
        <div class="card-header" style="background-color: #2c3e50; color: #fff;">
            <h3 class="card-title text-center">Rincian Reservasi Hotel</h3>
        </div>
        <div class="card-body">

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
                                {{-- <th>
                                    <!-- Checkbox Select All -->
                                    <input type="checkbox" id="select-all">
                                </th> --}}
                                <th>Nama Ruangan</th>
                                {{-- <th>Harga per Malam</th> --}}
                                <th>Nama Hewan</th>
                                <th>Jenis Kelamin</th>
                                <th>Ras</th>
                                <th>Tanggal Check In</th>
                                <th>Tanggal Check Out</th>
                                <th>Status</th>
                                {{-- <th>Denda</th> --}}
                                {{-- <th></th> --}}
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
                                    {{-- <td>
                                        <!-- Checkbox untuk memilih setiap item -->
                                        <input type="checkbox" name="rincian_ids[]" class="member-checkbox" value="{{ $rincianGroup->first()->reservasi_hotel_id }}" 
                                        {{ $rincianGroup->first()->status == 'sudah diambil' ? 'checked' : '' }}>
                                    </td> --}}
                                    <td>{{ $room->nama_ruangan ?? 'Room tidak tersedia' }}</td>
                                    {{-- <td>Rp {{ number_format($room->category_hotel->harga, 0, ',', '.') }}</td> --}}
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
                                    <!-- Menampilkan tanggal checkin dan checkout -->
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
                                    {{-- <td>
                                        @foreach($rincianGroup as $rincian)
                                            {{ $rincian->denda ?? 'Tidak diketahui' }}<br>
                                        @endforeach
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Tombol Submit untuk update status -->
                    {{-- <div class="text-right">
                        <button type="submit" class="btn btn-success">Update Status ke Sudah Diambil</button>
                    </div> --}}
                </form>
            </div>

            <!-- Menampilkan Total Harga -->
            {{-- <div class="mt-4 text-right">
                <h5>Total Harga: Rp {{ number_format($reservasiHotel->Total, 0, ',', '.') }}</h5>
            </div> --}}

        </div>
    </div>

    <!-- Button Kembali -->
    <div class="text-center mt-4">
        <a href="{{ route('perawat-reservasi-hotel.index') }}" class="btn btn-primary btn-lg">Kembali</a>
    </div>
</div>
@endsection
