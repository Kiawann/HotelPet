@extends('layouts.perawat')

@section('title', 'Daftar Yang Harus Buat Laporan Hewan')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <h1 class="h3 mb-0">Daftar Reservasi Hotel</h1>
                <div>
                    <form id="bulkCheckinForm" method="POST" action="{{ route('reservasi-hotel.bulk-checkout') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Check out Terpilih</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form action="" method="GET" class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-3">
                <div class="col">
                    <label for="status" class="form-label">Filter Status:</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Status</option>
                        <option value="check in" {{ request('status') == 'check in' ? 'selected' : '' }}>Check In</option>
                        <option value="check out" {{ request('status') == 'check out' ? 'selected' : '' }}>Check Out</option>
                    </select>
                </div>
                <div class="col">
                    <label for="date_filter" class="form-label">Filter Tanggal:</label>
                    <select name="date_filter" id="date_filter" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Tanggal</option>
                        <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                        @if (request('status') == 'check in')
                            <option value="check_out_today" {{ request('date_filter') == 'check_out_today' ? 'selected' : '' }}>
                                Check Out Hari Ini
                            </option>
                        @endif
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th style="width: 50px">No</th>
                            <th>Pemilik</th>    
                            <th>Tanggal Checkin</th>
                            <th>Tanggal Checkout</th>
                            <th>Status</th>
                            <th style="width: 200px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservasiHotels as $reservasi)
                        <tr>
                            <td>
                                <input type="checkbox" 
                                       name="selected_ids[]" 
                                       value="{{ $reservasi->id }}" 
                                       class="form-check-input checkbox_ids"
                                       form="bulkCheckinForm"
                                       {{ $reservasi->status === 'check out' ? 'disabled' : '' }}>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $reservasi->dataPemilik->nama ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $reservasi->tanggal_checkin ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $reservasi->tanggal_checkout ?? 'Tidak Diketahui' }}</td>
                            <td>
                                <span class="badge {{ $reservasi->status === 'check in' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $reservasi->status }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('perawat-reservasi-hotel.show', $reservasi->id) }}" 
                                       class="btn btn-info btn-sm">
                                        Show
                                    </a>
                    
                                    @php
                                        $hariIni = \Carbon\Carbon::today()->format('Y-m-d');
                                        $jumlahHewan = $reservasi->rincianReservasiHotel()->count();
                                        $jumlahLaporanHariIni = $reservasi->laporanHewan()
                                            ->whereDate('tanggal_laporan', $hariIni)
                                            ->select('rincian_reservasi_hotel_id')
                                            ->distinct()
                                            ->count();
                                        $semuaHewanSudahLaporan = ($jumlahHewan > 0 && $jumlahHewan == $jumlahLaporanHariIni);
                                    @endphp 
                    
                                    @if ($reservasi->status === 'check in')
                                        @if (!$semuaHewanSudahLaporan)
                                            <a href="{{ route('laporan_hewan.create', [
                                                'reservasi_id' => $reservasi->id,
                                                'status' => request('status'),
                                                'date_filter' => request('date_filter')
                                            ]) }}" class="btn btn-success btn-sm">Buat Laporan</a>
                                        @else
                                            <a href="{{ route('laporan_hewan.laporan', [
                                                'reservasiId' => $reservasi->id,
                                                'status' => request('status'),
                                                'date_filter' => request('date_filter')
                                            ]) }}" class="btn btn-secondary btn-sm">Lihat Laporan</a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <a class="page-link" href="{{ $reservasiHotels->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            @foreach ($reservasiHotels->getUrlRange(1, $reservasiHotels->lastPage()) as $page => $url)
                                <li class="page-item {{ $reservasiHotels->currentPage() == $page ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                </li>
                            @endforeach
                            <li class="page-item">
                                <a class="page-link" href="{{ $reservasiHotels->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        .container-fluid {
            padding: 1rem;
        }
        h1 {
            font-size: 1.5rem;
        }
    }
</style>

@push('scripts')
<script>
    // Select All functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.checkbox_ids:not([disabled])');

    selectAllCheckbox.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });

    // Update "Select All" checkbox state
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
        });
    });

    // Form submission handling
    const form = document.getElementById('bulkCheckinForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const selectedCheckboxes = document.querySelectorAll('input[name="selected_ids[]"]:checked');
        if (selectedCheckboxes.length === 0) {
            alert('Pilih minimal satu reservasi untuk check out.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin mengubah status reservasi terpilih menjadi check out?')) {
            form.submit();
        }
    });
</script>
@endpush
@endsection