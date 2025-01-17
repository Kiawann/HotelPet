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
            width: 300px;
            padding: 20px;
            border: 1px solid #000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-size: 14px;
        }

        .receipt h3 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }

        .receipt p {
            margin: 5px 0;
        }

        .receipt hr {
            border: 1px dashed #000;
            margin: 10px 0;
        }

        .receipt .rincian-hewan {
            text-align: left;
            margin-top: 10px;
        }

        .receipt .rincian-hewan p {
            margin: 5px 0;
        }

        .receipt .total {
            font-weight: bold;
        }

        .receipt button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            font-size: 14px;
            cursor: pointer;
            margin-top: 15px;
            width: 100%;
        }

        .receipt button:hover {
            background-color: #218838;
        }

        /* Aturan untuk mode cetak */
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
        <p>Alamat: Jl. Contoh No. 123, Bandung</p>
        <p>Telepon: 0812-3456-7890</p>
        <hr>

        <h4>STRUK TRANSAKSI</h4>
        <p>Tanggal: {{ now()->format('d/m/Y H:i:s') }}</p>
        <hr>

        <p><strong>Nomor Reservasi:</strong> {{ $transaksi->reservasiHotel->id }}</p>
        <p><strong>Nama Pemilik:</strong> {{ $transaksi->reservasiHotel->dataPemilik->nama ?? 'Tidak ada data' }}</p>
        <p><strong>Tanggal Check-In:</strong> {{ $transaksi->reservasiHotel->tanggal_checkin }}</p>
        <p><strong>Tanggal Check-Out:</strong> {{ $transaksi->reservasiHotel->tanggal_checkout }}</p>
        <hr>

        <h4>Rincian Hewan</h4>
        <div class="rincian-hewan">
            @foreach ($transaksi->reservasiHotel->rincianReservasiHotel as $rincian)
            <p><strong>Nama Hewan:</strong> {{ $rincian->dataHewan->nama_hewan ?? 'Tidak ada data' }}</p>
            <p><strong>Room:</strong> {{ $rincian->room->nama_ruangan ?? 'Tidak ada data' }}</p>
            <p><strong>Subtotal:</strong> Rp {{ number_format($rincian->SubTotal, 0, ',', '.') }}</p>
            <hr>
            @endforeach
        </div>

        <p class="total">Total: Rp {{ number_format($transaksi->reservasiHotel->Total, 0, ',', '.') }}</p>
        <p><strong>Di Bayar:</strong> {{ $transaksi->Dibayar }}</p>
        <p><strong>Kembalian:</strong> {{ $transaksi->Kembalian }}</p>
        <hr>

        <p>Terima kasih telah mempercayakan hotel kami!</p>
        <p>Semoga pelayanan kami memuaskan Anda!</p>

        <button onclick="window.print()">Cetak Struk</button>
    </div>
</body>
</html>
