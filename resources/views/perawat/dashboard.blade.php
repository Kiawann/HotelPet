@extends('layouts.perawat')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Card untuk Hewan yang Perlu Dibuat Laporan Hari Ini (Biru) -->
            <div class="col">
                <a href="{{ route('perawat-reservasi-hotel.index', ['status' => 'check in', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Hewan yang Perlu Dibuat Laporan Hari Ini</h6>
                            <div>
                                <h4 class="mb-0">{{ $needsReport }}</h4>
                                <small>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</small>
                            </div>
                            <small>Hewan yang belum memiliki laporan</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card untuk Reservasi Check-in yang Harus Check-out Hari Ini (Biru) -->
            <div class="col">
                <a href="{{ route('perawat-reservasi-hotel.index', ['status' => 'check in', 'date_filter' => 'check_out_today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Reservasi Check-in yang Harus Check-out Hari Ini</h6>
                            <div>
                                <h4 class="mb-0">{{ $checkoutToday }}</h4>
                                <small>{{ \Carbon\Carbon::now()->format('d-m-Y') }}</small>
                            </div>
                            <small>Jumlah reservasi check-out hari ini</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
