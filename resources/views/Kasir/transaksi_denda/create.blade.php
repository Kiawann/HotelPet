@extends('layouts.kasir')

@section('content')
<div class="container">
    <h2>Bayar Denda</h2>
    <form id="formDenda" action="{{ route('transaksi-denda-store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="reservasi_id" class="form-label">Reservasi</label>
            <input type="hidden" name="reservasi_id" value="{{ $reservasi->id }}">
            <input type="text" class="form-control" value="Reservasi #{{ $reservasi->id }} - {{ $reservasi->dataPemilik->nama ?? 'Tidak Diketahui' }}" readonly>
        </div>

        <div class="mb-3">
            <label for="jumlah_denda" class="form-label">Jumlah Denda</label>
            <input type="number" class="form-control" name="jumlah_denda" id="jumlah_denda" value="{{ $reservasi->rincianReservasiHotel->sum('Denda') }}" required readonly>
        </div>

        <div class="mb-3">
            <label for="status_pembayaran" class="form-label">Metode Pembayaran</label>
            <select class="form-control" name="status_pembayaran" id="status_pembayaran" required>
                <option value="">-- Pilih Metode --</option>
                <option value="Cash">Cash</option>
                <option value="Transfer">Transfer</option>
            </select>
        </div>

        <div class="mb-3" id="dibayar_section" style="display: none;">
            <label for="Dibayar" class="form-label">Jumlah Dibayar</label>
            <input type="number" class="form-control" name="Dibayar" id="Dibayar" placeholder="Masukkan jumlah yang dibayar">
            <small id="dibayarError" class="text-danger" style="display: none;">Jumlah dibayar tidak boleh kurang dari jumlah denda.</small>
        </div>

        <div class="mb-3" id="bukti_section" style="display: none;">
            <label for="bukti_pembayaran" class="form-label">Bukti Transfer</label>
            <input type="file" class="form-control" name="bukti_pembayaran" id="bukti_pembayaran">
        </div>

        <button type="submit" class="btn btn-primary">Bayar Denda</button>
    </form>
</div>

<script>
    document.getElementById('status_pembayaran').addEventListener('change', function() {
        var metode = this.value;
        document.getElementById('dibayar_section').style.display = metode === 'Cash' ? 'block' : 'none';
        document.getElementById('bukti_section').style.display = metode === 'Transfer' ? 'block' : 'none';
    });

    document.getElementById('formDenda').addEventListener('submit', function(event) {
        var jumlahDenda = parseFloat(document.getElementById('jumlah_denda').value);
        var dibayarInput = document.getElementById('Dibayar');
        var dibayarError = document.getElementById('dibayarError');

        if (dibayarInput.style.display !== 'none') {
            var dibayar = parseFloat(dibayarInput.value);
            if (isNaN(dibayar) || dibayar < jumlahDenda) {
                event.preventDefault(); // Cegah form dikirim
                dibayarError.style.display = 'block';
            } else {
                dibayarError.style.display = 'none';
            }
        }
    });
</script>

@endsection
