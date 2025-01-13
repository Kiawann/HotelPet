@extends('layouts.perawat')

@section('content')
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <a href="{{ route('perawat-reservasi-hotel.index') }}" class="text-decoration-none">
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