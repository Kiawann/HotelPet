@extends('layouts.kasir')

@section('title', 'Buat Transaksi')

@section('content')
    <div class="container mt-5">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

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
                <input type="date" class="form-control" id="tanggal_pembayaran" name="tanggal_pembayaran" 
                    value="{{ old('tanggal_pembayaran') }}" required>
            </div>

            <!-- Input untuk Subtotal -->
            <div class="mb-3">
                <label for="Subtotal" class="form-label">Subtotal</label>
                <input type="number" class="form-control" name="Subtotal" 
                    value="{{ old('Subtotal', $reservasiHotel->Total) }}" required>
            </div>

            <!-- Input untuk status_pembayaran -->
           <!-- Input untuk status_pembayaran -->
           <div class="mb-3">
            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
            <input type="text" class="form-control" id="status_pembayaran" name="status_pembayaran" value="Cash" readonly>
        </div>

            <div class="mb-3" id="dibayar-container">
                <label for="Dibayar" class="form-label">Dibayar</label>
                <input type="number" class="form-control @error('Dibayar') is-invalid border-danger @enderror" 
                    id="Dibayar" name="Dibayar" 
                    value="{{ old('Dibayar') }}" required>

                @error('Dibayar')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <!-- Input untuk Foto Transfer -->
            <div class="mb-3" id="foto-transfer-container" style="display: none;">
                <label for="Foto_Transfer" class="form-label">Foto Transfer</label>
                <input type="file" class="form-control" id="Foto_Transfer" name="Foto_Transfer">
            </div>

            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const statusPembayaran = document.getElementById('status_pembayaran');
            const dibayarContainer = document.getElementById('dibayar-container');
            const fotoTransferContainer = document.getElementById('foto-transfer-container');

            function toggleFields() {
                if (statusPembayaran.value === 'Transfer') {
                    dibayarContainer.style.display = 'none';
                    fotoTransferContainer.style.display = 'block';
                } else {
                    dibayarContainer.style.display = 'block';
                    fotoTransferContainer.style.display = 'none';
                }
            }

            statusPembayaran.addEventListener('change', toggleFields);

            // Pastikan kondisi tetap terjaga saat reload jika terjadi error
            toggleFields();

            @error('Dibayar')
                document.getElementById("Dibayar").focus();
            @enderror
        });
    </script>
@endsection