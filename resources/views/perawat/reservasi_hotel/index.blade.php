@extends('layouts.perawat')

@section('title', 'Daftar Yang Harus Buat Laporan Hewan')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Daftar Reservasi Hotel</h1>
        <div>
            <form id="bulkCheckinForm" method="POST" action="{{ route('reservasi-hotel.bulk-checkout') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Check out Terpilih</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

     <!-- Simplified Filter Form -->
     <div class="mb-3">
        <form action="" method="GET" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="status" class="col-form-label">Filter Status:</label>
            </div>
            <div class="col-auto">
                <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="check in" {{ request('status') == 'check in' ? 'selected' : '' }}>Check In</option>
                    <option value="check out" {{ request('status') == 'check out' ? 'selected' : '' }}>Check Out</option>
                </select>
            </div>
            <div class="col-auto">
                <label for="date_filter" class="col-form-label">Filter Tanggal:</label>
            </div>
            <div class="col-auto">
                <select name="date_filter" id="date_filter" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Tanggal</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="check_out_today" {{ request('date_filter') == 'check_out_today' ? 'selected' : '' }}>Check Out Hari Ini</option>
                </select>
                </select>
            </div>
            
        </form>
    </div>
    

    <table class="table table-bordered"> 
        <thead>
            <tr>
                <th>
                    
                </th>
                <th>No</th>
                <th>Pemilik</th>    
                <th>Tanggal Checkin</th>
                <th>Tanggal Checkout</th>
                <th>Status</th>
                <th>Aksi</th>
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
                <td>{{ $reservasi->status }}</td>
                <td>
                    <a href="{{ route('perawat-reservasi-hotel.show', $reservasi->id) }}" class="btn btn-info btn-sm">Show</a>
        
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
                        ]) }}" class="btn btn-secondary btn-sm">Lihat Laporan Hewan</a>
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
        
        </tbody>
    </table>
</div>

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

    // Update "Select All" checkbox state based on individual checkboxes
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
            alert('Pilih minimal satu reservasi untuk check in.');
            return;
        }

        if (confirm('Apakah Anda yakin ingin mengubah status reservasi terpilih menjadi check in?')) {
            form.submit();
        }
    });
</script>
@endpush
@endsection
