@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ isset($transaksi) ? 'Edit Transaksi' : 'Tambah Transaksi' }}</h1>
    <form action="{{ isset($transaksi) ? route('transaksi.update', $transaksi->id) : route('transaksi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if (isset($transaksi))
            @method('PUT')
        @endif

        <!-- Pemilik -->
        <div class="mb-3">
            <label for="data_pemilik_id" class="form-label">Pemilik</label>
            <select name="data_pemilik_id" id="data_pemilik_id" class="form-control">
                @foreach ($dataPemilik as $pemilik)
                    <option value="{{ $pemilik->id }}" {{ isset($transaksi) && $transaksi->data_pemilik_id == $pemilik->id ? 'selected' : '' }}>
                        {{ $pemilik->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Reservasi Hotel -->
        <div class="mb-3">
            <label for="reservasi_hotel_id" class="form-label">Reservasi Hotel</label>
            <select class="form-control" id="reservasi_hotel_id" name="reservasi_hotel_id" disabled>
                <option value="{{ $selectedReservasiHotelId }}" selected>
                    @foreach($reservasiHotel as $hotel)
                        @if ($hotel->id == $selectedReservasiHotelId)
                            {{ $hotel->dataPemilik->nama ?? 'Tidak Diketahui' }} - 
                            {{ $hotel->created_at ? $hotel->created_at->format('d-m-Y') : 'Tanggal Tidak Tersedia' }}
                        @endif
                    @endforeach
                </option>
            </select>
        </div>

        <!-- Tanggal Pembayaran -->
        <div class="mb-3">
            <label for="tanggal_pembayaran" class="form-label">Tanggal Pembayaran</label>
            <input type="date" name="tanggal_pembayaran" id="tanggal_pembayaran" class="form-control" value="{{ $transaksi->tanggal_pembayaran ?? old('tanggal_pembayaran') }}">
        </div>

        <!-- Subtotal -->
        <div class="mb-3">
            <label for="subtotal" class="form-label">Subtotal</label>
            <input type="text" class="form-control" id="subtotal" name="subtotal" value="{{ $subtotal }}" readonly>
        </div>

        <!-- Status Pembayaran -->
        <div class="mb-3">
            <label for="status_pembayaran" class="form-label">Status Pembayaran</label>
            <select name="status_pembayaran" id="status_pembayaran" class="form-control" onchange="togglePaymentInfo()">
                <option value="" disabled {{ !isset($transaksi) ? 'selected' : '' }}>Pilih Status Pembayaran</option>
                <option value="Transfer" {{ isset($transaksi) && $transaksi->status_pembayaran == 'Transfer' ? 'selected' : '' }}>Transfer</option>
                <option value="Cash" {{ isset($transaksi) && $transaksi->status_pembayaran == 'Cash' ? 'selected' : '' }}>Cash</option>
            </select>
        </div>

        <!-- Foto Transfer -->
        <div class="mb-3" id="bukti-transfer-group" style="display: none;">
            <label for="Foto_Transfer" class="form-label">Foto Transfer</label>
            <input type="file" name="Foto_Transfer" id="Foto_Transfer" class="form-control">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-success">{{ isset($transaksi) ? 'Update' : 'Simpan' }}</button>
    </form>
</div>

<script>
    function togglePaymentInfo() {
        const paymentMethod = document.getElementById('status_pembayaran').value;
        const buktiTransferGroup = document.getElementById('bukti-transfer-group');

        if (paymentMethod === 'Cash') {
            buktiTransferGroup.style.display = 'none';
        } else if (paymentMethod === 'Transfer') {
            buktiTransferGroup.style.display = 'block';
        } else {
            buktiTransferGroup.style.display = 'none';
        }
    }

    // Initialize on page load
    togglePaymentInfo();
</script>
@endsection
