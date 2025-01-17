@extends('layouts.kasir')

@section('title', 'Daftar Reservasi Hotel')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="status" class="form-label">Filter Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="di pesan" {{ request('status') == 'di pesan' ? 'selected' : '' }}>Di Pesan</option>
                    <option value="check in" {{ request('status') == 'check in' ? 'selected' : '' }}>Check In</option>
                    <option value="check out" {{ request('status') == 'check out' ? 'selected' : '' }}>Check Out</option>
                    <option value="cancel" {{ request('status') == 'cancel' ? 'selected' : '' }}>Cancel</option>
                    <option value="di bayar" {{ request('status') == 'di bayar' ? 'selected' : '' }}>Di Bayar</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="date_filter" class="form-label">Filter Tanggal</label>
                <select name="date_filter" id="date_filter" class="form-select">
                    <option value="all_dates" {{ request('date_filter') == 'all_dates' ? 'selected' : '' }}>Semua Tanggal</option>
                    <option value="today" {{ request('date_filter') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="yesterday" {{ request('date_filter') == 'yesterday' ? 'selected' : '' }}>Kemarin</option>
                </select>
            </div>
        </div>

        <!-- Form Pembatalan Massal -->
        <form action="{{ route('reservasi-hotel-bulk-cancel') }}" method="POST"
            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi yang dipilih?')">
            @csrf
            @method('PUT')
        
            <input type="hidden" name="status" value="{{ request('status') }}">
            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
        
            <button type="submit" class="btn btn-danger mb-3">Batalkan Reservasi Terpilih</button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
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
                                <input type="checkbox" name="selected_reservasi[]" value="{{ $reservasi->id }}"
                                    class="select-reservasi"
                                    {{ in_array($reservasi->status, ['cancel', 'check in', 'check out', 'done', 'di bayar']) ? 'disabled' : '' }}>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $reservasi->dataPemilik->nama ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $reservasi->tanggal_checkin ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $reservasi->tanggal_checkout ?? 'Tidak Diketahui' }}</td>
                            <td>{{ $reservasi->status }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('kasir-reservasi-hotel.show', $reservasi->id) }}?status={{ request('status') }}&date_filter={{ request('date_filter') }}" 
                                        class="btn btn-primary btn-sm">
                                        Rincian
                                    </a>


                                    <!-- Tombol Check-In -->
                                    @if ($reservasi->status == 'di bayar')
                                        <form action="{{ route('reservasi-hotel-checkin', $reservasi->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                            <button type="submit" class="btn btn-success btn-sm">Check In</button>
                                        </form>
                                    @endif

                                    <!-- Tombol Delete -->
                                    @if (in_array($reservasi->status, ['cancel']))
                                        <form action="{{ route('kasir-reservasi-hotel.destroy', $reservasi->id) }}" method="POST" 
                                            style="display:inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif
                                </div>
                                
                                    <!-- Tombol Info -->
                                    <button type="button" onclick="showReservationInfo({{ $reservasi->id }})" class="btn btn-info btn-sm">
                                        Ngambil Hewan
                                    </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </form>

        <!-- Modal Info Reservasi -->
        <div class="modal fade" id="reservationInfoModal" tabindex="-1" aria-labelledby="reservationInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reservationInfoModalLabel">Informasi Reservasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Nama Hewan</th>
                                        <th>Jenis Hewan</th>
                                        <th>Nomor Kamar</th>
                                        <th>Tipe Kamar</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="reservationDetailsBody">
                                    <!-- Data akan diisi oleh JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk checkbox "Pilih Semua"
        document.getElementById('selectAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('.select-reservasi');
            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
        });

        // Fungsi untuk memperbarui filter
        function updateFilters() {
            const status = document.getElementById('status').value;
            const dateFilter = document.getElementById('date_filter').value;

            let url = '{{ route('kasir-reservasi-hotel.index') }}?';
            const params = [];

            if (status) {
                params.push(`status=${status}`);
            }
            if (dateFilter) {
                params.push(`date_filter=${dateFilter}`);
            }

            window.location.href = url + params.join('&');
        }

        // Event listener untuk filter
        document.getElementById('status').addEventListener('change', updateFilters);
        document.getElementById('date_filter').addEventListener('change', updateFilters);

        // Inisialisasi filter saat halaman dimuat
        window.onload = function () {
            const params = new URLSearchParams(window.location.search);
            const status = params.get('status');
            const dateFilter = params.get('date_filter');

            if (status) document.getElementById('status').value = status;
            if (dateFilter) document.getElementById('date_filter').value = dateFilter;
        };
    </script>
@endsection