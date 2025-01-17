@extends('layouts.kasir')

@section('title', 'Buat Transaksi')

@section('content')
    <div class="container mt-5">
        <h1>Buat Transaksi Baru</h1>

        <form action="{{ route('kasir-reservasi-hotel-transaksi.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
        
            <!-- Menampilkan data pemilik -->
            <div class="mb-3">
                <label for="data_pemilik" class="form-label">Data Pemilik</label>
                <input type="text" class="form-control" id="data_pemilik" value="{{ $dataPemilik->nama }}" readonly>
            </div>
        
            <!-- Input untuk reservasi_hotel_id (hidden) -->
            <input type="hidden" name="reservasi_hotel_id" value="{{ $reservasiHotel->id }}">
        
            <!-- Input untuk data_pemilik_id (hidden) -->
            <input type="hidden" name="data_pemilik_id" value="{{ $dataPemilik->id }}">
        
            <!-- Input untuk tanggal_pembayaran -->
            <div class="mb-3">
                <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
                <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" required>
            </div>

            <!-- Input untuk Subtotal -->
            <div class="mb-3">
                <label for="Subtotal" class="form-label">Subtotal</label>
                <input type="number" class="form-control" name="Subtotal" value="{{ $reservasiHotel->Total }}" required>
            </div>
        
            <!-- Input untuk status_pembayaran -->
            <div class="mb-3">
                <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
                <select class="form-control" id="status_pembayaran" name="status_pembayaran" required>
                    <option value="">Pilih Status Pembayaran</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}">{{ $status }}</option>
                    @endforeach
                </select>
            </div>
        
            <!-- Input untuk Dibayar -->
            <div class="mb-3" id="dibayar-container">
                <label for="Dibayar" class="form-label">Dibayar</label>
                <input type="number" class="form-control" id="Dibayar" name="Dibayar" required>
            </div>
        
            <!-- Input untuk Foto Transfer (disembunyikan secara default) -->
            <div class="mb-3" id="foto-transfer-container" style="display: none;">
                <label for="Foto_Transfer" class="form-label">Foto Transfer</label>
                <input type="file" class="form-control" id="Foto_Transfer" name="Foto_Transfer">
            </div>
        
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        // Fungsi untuk menampilkan atau menyembunyikan input berdasarkan status pembayaran
        document.getElementById('status_pembayaran').addEventListener('change', function() {
            const statusPembayaran = this.value;
            
            if (statusPembayaran === 'Transfer') {
                document.getElementById('dibayar-container').style.display = 'none';
                document.getElementById('foto-transfer-container').style.display = 'block';
            } else if (statusPembayaran === 'Cash') {
                document.getElementById('dibayar-container').style.display = 'block';
                document.getElementById('foto-transfer-container').style.display = 'none';
            } else {
                // Menangani kondisi ketika status pembayaran lainnya dipilih
                document.getElementById('dibayar-container').style.display = 'block';
                document.getElementById('foto-transfer-container').style.display = 'none';
            }
        });
    </script>
@endsection
