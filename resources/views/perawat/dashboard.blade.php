@extends('layouts.perawat')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <!-- Card sebelumnya -->
            <div class="col-md-6">
                <a href="{{ route('perawat-reservasi-hotel.index', ['status' => 'check in', 'date_filter' => 'today']) }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Hewan yang Perlu Di Buat Laporan Hari Ini</h5>
                            <div class="d-flex align-items-center mt-3">
                                <h1 class="display-4 mb-0 me-3">{{ $needsReport }}</h1>
                                <span class="text-muted">Hewan</span>
                            </div>
                            <p class="card-text mt-2">
                                Hewan yang belum memiliki laporan untuk tanggal {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </a>
                
            </div>

            <!-- Card baru untuk checkout hari ini -->
            <div class="col-md-6">
                <a href="{{ route('perawat-reservasi-hotel.index',['status' => 'check in', 'date_filter' => 'check_out_today']) }}" class="text-decoration-none">
                    <div class="card shadow">
                        <div class="card-body">
                            <h5 class="card-title">Reservasi Check In Yang Harus Check Out Hari Ini</h5>
                            <div class="d-flex align-items-center mt-3">
                                <h1 class="display-4 mb-0 me-3">{{ $checkoutToday }}</h1>
                                <span class="text-muted">Reservasi</span>
                            </div>
                            <p class="card-text mt-2">
                                Jumlah reservasi yang harus check out pada tanggal {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Logout Button -->
        <div class="mt-4">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Logout</button>
            </form>
        </div>
    </div>
@endsection