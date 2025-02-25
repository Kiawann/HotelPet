@extends('layouts.kasir')

@section('content')
    <div class="container mt-4">
        <!-- Reservation Counter Cards -->
        <div class="row row-cols-1 row-cols-md-4 g-3 mt-4">
            <!-- Pending Reservations Card -->
            <div class="col">
                <a href="{{ route('kasir-reservasi-hotel.index', ['status' => 'di pesan', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm" style="height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Di Pesan Hari Ini</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-check fa-lg me-2"></i>
                                <div>
                                    <h4 class="mb-0">{{ $todayReservations }}</h4>
                                    <small>{{ date('d-m-Y') }}</small>
                                </div>
                            </div>
                            <small>Menunggu Pembayaran</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Check-ins Card -->
            <div class="col">
                <a href="{{ route('kasir-reservasi-hotel.index', ['status' => 'di bayar', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm" style="height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Di Bayar Hari Ini</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users fa-lg me-2"></i>
                                <div>
                                    <h4 class="mb-0">{{ $todayDibayar }}</h4>
                                    <small>{{ date('d-m-Y') }}</small>
                                </div>
                            </div>
                            <small>Menunggu Status Menjadi Check-in</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Today's Check-ins Card -->
            <div class="col">
                <a href="{{ route('kasir-reservasi-hotel.index', ['status' => 'check in', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm" style="height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Check-in Hari Ini</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-door-open fa-lg me-2"></i>
                                <div>
                                    <h4 class="mb-0">{{ $todayCheckins }}</h4>
                                    <small>{{ date('d-m-Y') }}</small>
                                </div>
                            </div>
                            <small>Menunggu Check-Out</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Today's Check-outs Card -->
            <div class="col">
                <a href="{{ route('kasir-reservasi-hotel.index', ['status' => 'check out', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm" style="height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Check-out Hari Ini</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-door-closed fa-lg me-2"></i>
                                <div>
                                    <h4 class="mb-0">{{ $todayCheckouts }}</h4>
                                    <small>{{ date('d-m-Y') }}</small>
                                </div>
                            </div>
                            <small>Menunggu Hewan Di Ambil</small>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Cancel Reservations Card -->
            <div class="col">
                <a href="{{ route('kasir-reservasi-hotel.index', ['status' => 'cancel', 'date_filter' => 'today']) }}"
                    class="text-decoration-none">
                    <div class="card bg-primary text-white h-100 shadow-sm" style="height: 150px;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h6 class="card-title">Cancel Hari Ini</h6>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ban fa-lg me-2"></i>
                                <div>
                                    <h4 class="mb-0">{{ $todayCancel }}</h4>
                                    <small>{{ date('d-m-Y') }}</small>
                                </div>
                            </div>
                            <small>Reservasi Yang Harus Di Hapus</small>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
