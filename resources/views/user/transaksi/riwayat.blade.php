<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Reservasi Hotel</title>
    <style>
        .page-header {
            margin-bottom: 20px;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .table thead th {
            background-color: #343a40;
            color: white;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-sm {
            padding: 5px 10px;
        }

        .empty-message {
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            color: #6c757d;
            margin-top: 50px;
        }

        .empty-icon {
            font-size: 50px;
            color: #adb5bd;
        }
    </style>
</head>

<body>

    @include('layouts.navbar')

    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($reservasiHotels->isEmpty())
            <div class="empty-message">
                <i class="fas fa-paw"></i>
                <p>Belum ada riwayat reservasi.</p>
                <a href="{{ route('layananPetHotel.index') }}" class="btn btn-primary">Reservasi Sekarang</a>
            </div>
        @else
            <div class="row">
                @foreach ($reservasiHotels as $reservasi)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm border-0 rounded">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Reservasi ID: {{ $reservasi->id }}</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Status:</strong>
                                    <span class="badge 
                                        {{ $reservasi->status == 'check in' ? 'bg-success' : 
                                            ($reservasi->status == 'di pesan' ? 'bg-warning' : 
                                            ($reservasi->status == 'dibayar' ? 'bg-primary' : 
                                            ($reservasi->status == 'cancel' ? 'bg-danger' : 'bg-secondary'))) }}">

                                        {{ ucfirst($reservasi->status) }}
                                    </span>
                                </p>

                                <p><strong>Total Biaya:</strong> Rp{{ number_format($reservasi->Total, 0, ',', '.') }}</p>
                                <p><strong>Tanggal Check-in:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkin)->format('d-m-Y') }}</p>
                                <p><strong>Tanggal Check-out:</strong> {{ \Carbon\Carbon::parse($reservasi->tanggal_checkout)->format('d-m-Y') }}</p>
                            </div>
                            <div class="card-footer text-center">
                                <a href="{{ route('booking.show', $reservasi->id) }}"
                                    class="btn btn-info btn-sm mb-2 w-100">Rincian</a>

                                @if ($reservasi->laporanHewan()->exists() && !in_array($reservasi->status, ['di pesan', 'di bayar', 'cancel']))
                                    <a href="{{ route('user.laporan_hewan.laporan', ['reservasiId' => $reservasi->id]) }}"
                                        class="btn btn-secondary btn-sm mb-2 w-100">Lihat Laporan Hewan</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</body>

</html>
