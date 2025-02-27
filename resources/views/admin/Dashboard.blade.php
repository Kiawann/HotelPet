@extends('layouts.app')

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

@section('content')
<div class="container mt-5" style="overflow-y: auto; max-height: 80vh;">
    <div class="row row-cols-1 row-cols-md-4 g-3 mt-4">
            @php
                $totalUsers = \App\Models\User::where('role', 'user')->count();
                $totalAdmins = \App\Models\User::where('role', 'admin')->count();
                $totalPerawat = \App\Models\User::where('role', 'perawat')->count();
                $totalKasir = \App\Models\User::where('role', 'kasir')->count();
                $totalPembayaran = \App\Models\ReservasiHotel::where('status', 'di bayar')->sum('total');
                $totalDenda = \App\Models\RincianReservasiHotel::sum('denda');

            @endphp
            

            <!-- Total User Card -->
            <div class="col">
                <a href="{{ route('data_pemilik.index', ['filter_role' => 'user']) }}" class="text-decoration-none text-reset">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-users fa-2x"></i>
                        <h6 class="card-title mt-2">Total User</h6>
                        <h4 class="mb-0">{{ $totalUsers }}</h4>
                    </div>
                </div>
            </a>
            </div>

            <!-- Total Admin Card -->
            <div class="col">
                <a href="{{ route('data_pemilik.index', ['filter_role' => 'admin']) }}" class="text-decoration-none text-reset">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-user-shield fa-2x"></i>
                        <h6 class="card-title mt-2">Total Admin</h6>
                        <h4 class="mb-0">{{ $totalAdmins }}</h4>
                    </div>
                </div>
            </a>
            </div>

            <!-- Total Perawat Card -->
            <div class="col">
                <a href="{{ route('data_pemilik.index', ['filter_role' => 'perawat']) }}" class="text-decoration-none text-reset">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-user-nurse fa-2x"></i>
                        <h6 class="card-title mt-2">Total Perawat</h6>
                        <h4 class="mb-0">{{ $totalPerawat }}</h4>
                    </div>
                </div>
            </a>
            </div>

            <!-- Total Kasir Card -->
            <div class="col">
                <a href="{{ route('data_pemilik.index', ['filter_role' => 'kasir']) }}" class="text-decoration-none text-reset">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-cash-register fa-2x"></i>
                        <h6 class="card-title mt-2">Total Kasir</h6>
                        <h4 class="mb-0">{{ $totalKasir }}</h4>
                    </div>
                </div>
                </a>
            </div>

            <div class="col mx-auto">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                        <h6 class="card-title mt-2">Total Seluruh Pembayaran</h6>
                        <h4 class="mb-0">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
            <div class="col mx-auto">
                <div class="card bg-primary text-white h-100 shadow-lg" style="height: 180px; border-radius: 12px;">
                    <div class="card-body d-flex flex-column justify-content-center text-center">
                        <i class="fas fa-exclamation-circle fa-2x"></i>
                        <h6 class="card-title mt-2">Total Seluruh Denda</h6>
                        <h4 class="mb-0">Rp {{ number_format($totalDenda, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    
    <!-- Total Pembayaran Card -->
   



        <!-- Chart for Animal Count by Category -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Jumlah Hewan Perkategori</h5>
                <canvas id="animalChart" style="max-height: 300px;"></canvas>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('animalChart').getContext('2d');
        var animalChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($categoryNames),
                datasets: [{
                    label: 'Jumlah Hewan',
                    data: @json($animalCounts),
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
    </script>
@endsection
