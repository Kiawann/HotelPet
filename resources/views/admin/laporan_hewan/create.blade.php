@extends('layouts.app')

@section('title', 'Form Laporan Hewan')

@section('content')
<div class="container mt-5">
    <h1>Buat Laporan Hewan</h1>

    <form action="{{ route('laporan_hewan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="reservasi_hotel_id" class="form-label">Reservasi ID</label>
            <input type="text" class="form-control" id="reservasi_hotel_id" name="reservasi_hotel_id" value="{{ $reservasiId }}" readonly>
        </div>

        <div class="form-group">
            <label for="data_hewan_id">Data Hewan</label>
            <select name="data_hewan_id" id="data_hewan_id" class="form-control">
                @foreach($dataHewans as $dataHewan)
                    <option value="{{ $dataHewan->id }}">{{ $dataHewan->nama_hewan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="room_id">Room</label>
            <select name="room_id" id="room_id" class="form-control">
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->nama_ruangan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="Makan">Makan</label>
            <input type="text" name="Makan" id="Makan" class="form-control">
        </div>

        <div class="form-group">
            <label for="Minum">Minum</label>
            <input type="text" name="Minum" id="Minum" class="form-control">
        </div>

        <div class="form-group">
            <label for="Bab">Bab</label>
            <input type="text" name="Bab" id="Bab" class="form-control">
        </div>

        <div class="form-group">
            <label for="Bak">Bak</label>
            <input type="text" name="Bak" id="Bak" class="form-control">
        </div>

        <div class="form-group">
            <label for="keterangan">Keterangan</label>
            <input type="text" name="keterangan" id="keterangan" class="form-control">
        </div>

        <div class="form-group">
            <label for="tanggal_laporan">Tanggal Laporan</label>
            <input type="date" name="tanggal_laporan" id="tanggal_laporan" class="form-control" required>
        </div>

        <!-- Input lain tetap -->
    <div class="form-group">
        <label for="foto">Foto</label>
        <input type="file" name="foto" id="foto" class="form-control">
    </div>

        <button type="submit" class="btn btn-primary">Simpan Laporan</button>
        <a href="{{ route('reservasi_hotel.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
