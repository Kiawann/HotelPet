@extends('layouts.perawat')

@section('title', 'Form Laporan Hewan')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Buat Laporan Hewan</h1>

    <form action="{{ route('laporan_hewan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="filter_status" value="{{ request('status') }}">
    <input type="hidden" name="filter_date" value="{{ request('date_filter') }}">
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="card-title mb-4">Detail Hewan & Reservasi</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="reservasi_hotel_id" class="form-label">Reservasi ID</label>
                    <input type="text" class="form-control" id="reservasi_hotel_id" name="reservasi_hotel_id" value="{{ $reservasiId }}" readonly>
                </div>
                <div class="col-md-6">
                    <label for="data_hewan_id" class="form-label">Data Hewan</label>
                    <select name="data_hewan_id" id="data_hewan_id" class="form-control">
                        <option value="">Pilih Hewan</option>
                        @foreach($dataHewans as $rincian)
                            <option value="{{ $rincian->dataHewan->id }}" 
                                    data-room-id="{{ $rincian->room->id }}" 
                                    data-room-name="{{ $rincian->room->nama_ruangan }}">
                                {{ $rincian->dataHewan->nama_hewan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="room_id_display" class="form-label">Room</label>
                        <!-- Input untuk menampilkan nama room -->
                        <input type="text" id="room_id_display" class="form-control" readonly>
                        <!-- Input hidden untuk menyimpan ID room -->
                        <input type="hidden" name="room_id" id="room_id">
                    </div>
                </div>
        <div class="card shadow-sm p-4 mb-4">
            <h4 class="card-title mb-4">Keterangan Laporan</h4>

            <div class="mb-3">
                <label for="Makan" class="form-label">Makan</label>
                <input type="text" name="Makan" id="Makan" class="form-control">
            </div>

            <div class="mb-3">
                <label for="Minum" class="form-label">Minum</label>
                <input type="text" name="Minum" id="Minum" class="form-control">
            </div>

            <div class="mb-3">
                <label for="Bab" class="form-label">Bab</label>
                <input type="text" name="Bab" id="Bab" class="form-control">
            </div>

            <div class="mb-3">
                <label for="Bak" class="form-label">Bak</label>
                <input type="text" name="Bak" id="Bak" class="form-control">
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label">Keterangan</label>
                <input type="text" name="keterangan" id="keterangan" class="form-control">
            </div>

            <div class="mb-3">
                <label for="tanggal_laporan" class="form-label">Tanggal Laporan</label>
                <input type="date" name="tanggal_laporan" id="tanggal_laporan" class="form-control" required>
            </div>
        </div>

        <div class="card shadow-sm p-4 mb-4">
            <h4 class="card-title mb-4">Upload Foto & Video</h4>

            <div class="mb-3">
                <label for="foto" class="form-label">Foto</label>
                <div id="foto-container">
                    <div class="input-group mb-2">
                        <input type="file" name="foto[]" class="form-control" accept="image/*">
                        <button type="button" class="btn btn-success" id="add-foto">Tambah Foto</button>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="video" class="form-label">Video</label>
                <div id="video-container">
                    <div class="input-group mb-2">
                        <input type="file" name="video[]" class="form-control" accept="video/*">
                        <button type="button" class="btn btn-success" id="add-video">Tambah Video</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-primary">Simpan Laporan</button>
            <a href="{{ route('perawat-reservasi-hotel.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
    document.getElementById('data_hewan_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const roomId = selectedOption.getAttribute('data-room');
        document.getElementById('room_id').value = roomId || ''; // Menampilkan ID kamar yang dipilih
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Set tanggal laporan ke tanggal hari ini
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('tanggal_laporan').value = today;

        // Tambah input untuk foto
        document.getElementById('add-foto').addEventListener('click', function () {
            const container = document.getElementById('foto-container');
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = ` 
                <input type="file" name="foto[]" class="form-control" accept="image/*">
                <button type="button" class="btn btn-danger remove-foto">Hapus</button>
            `;
            container.appendChild(inputGroup);
        });

        // Tambah input untuk video
        document.getElementById('add-video').addEventListener('click', function () {
            const container = document.getElementById('video-container');
            const inputGroup = document.createElement('div');
            inputGroup.className = 'input-group mb-2';
            inputGroup.innerHTML = ` 
                <input type="file" name="video[]" class="form-control" accept="video/*">
                <button type="button" class="btn btn-danger remove-video">Hapus</button>
            `;
            container.appendChild(inputGroup);
        });

        // Hapus input untuk foto
        document.getElementById('foto-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-foto')) {
                e.target.parentElement.remove();
            }
        });

        // Hapus input untuk video
        document.getElementById('video-container').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-video')) {
                e.target.parentElement.remove();
            }
        });

           // Script untuk mengupdate inputan room berdasarkan pilihan data hewan
    document.getElementById('data_hewan_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const roomId = selectedOption.getAttribute('data-room-id') || '';
        const roomName = selectedOption.getAttribute('data-room-name') || '';

        // Mengisi input tersembunyi dengan ID room
        document.getElementById('room_id').value = roomId;
        // Mengisi input yang terlihat dengan nama ruangan
        document.getElementById('room_id_display').value = roomName;
    });

    });
</script>
@endsection
