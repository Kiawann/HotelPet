<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }

        .receipt {
            background-color: white;
            width: 250px;
            padding: 15px;
            border: 1px solid #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left;
            font-size: 12px;
        }

        .receipt h3, .receipt h4 {
            margin: 0;
            font-weight: bold;
            text-align: center;
        }

        .receipt p {
            margin: 4px 0;
        }

        .receipt hr {
            border: 1px dashed #000;
            margin: 8px 0;
        }

        .receipt .total {
            font-weight: bold;
            text-align: right;
        }

        .receipt button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px;
            font-size: 12px;
            cursor: pointer;
            margin-top: 12px;
            width: 100%;
        }

        .receipt button:hover {
            background-color: #218838;
        }

        @media print {
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: white;
            }

            .receipt button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <h3>Hotel Hewan</h3>
        <p style="text-align: center;">Alamat: Jl. Contoh No. 123, Bandung</p>
        <p style="text-align: center;">Telepon: 0812-3456-7890</p>
        <hr>

        <h4>STRUK TRANSAKSI</h4>
        <p><strong>Tanggal:</strong> {{ now()->format('d/m/Y H:i:s') }}</p>
        <hr>

        <p><strong>Nomor Reservasi:</strong> {{ $transaksi->reservasiHotel->id }}</p>
        <p><strong>Nama Pemilik:</strong> {{ $transaksi->reservasiHotel->dataPemilik->nama ?? 'Tidak ada data' }}</p>
        <p><strong>Tanggal Check-In:</strong> {{ $transaksi->reservasiHotel->tanggal_checkin }}</p>
        <p><strong>Tanggal Check-Out:</strong> {{ $transaksi->reservasiHotel->tanggal_checkout }}</p>
        <hr>

        <h4>Rincian Hewan</h4>
        @foreach ($transaksi->reservasiHotel->rincianReservasiHotel as $rincian)
            <p><strong>Nama Hewan:</strong> {{ $rincian->dataHewan->nama_hewan ?? 'Tidak ada data' }}</p>
            <p><strong>Room:</strong> {{ $rincian->room->nama_ruangan ?? 'Tidak ada data' }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($rincian->SubTotal, 0, ',', '.') }}</p>
            <hr>
        @endforeach

        <p class="total">Total: Rp {{ number_format($transaksi->reservasiHotel->Total, 0, ',', '.') }}</p>
        <p><strong>Di Bayar:</strong> Rp {{ number_format($transaksi->Dibayar, 0, ',', '.') }}</p>
        <p><strong>Kembalian:</strong> Rp {{ number_format($transaksi->Kembalian, 0, ',', '.') }}</p>
        <hr>

        <p style="text-align: center;">Terima kasih telah mempercayakan hotel kami!</p>
        <p style="text-align: center;">Semoga pelayanan kami memuaskan Anda!</p>
    </div>

    <script>
        // Fungsi untuk mencetak struk
        window.onload = function() {
            window.print(); // Cetak otomatis saat halaman dimuat
            setTimeout(function() {
                window.location.href = "{{ route('kasir-reservasi-hotel.show', ['kasir_reservasi_hotel' => $transaksi->reservasiHotel->id]) }}"; // Ganti dengan route yang sesuai
            }, 1000); // Setelah 1 detik, redirect ke halaman daftar reservasi
        };
    </script>
</body>
</html>
