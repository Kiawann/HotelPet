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
                    <option value="all_dates" {{ request('date_filter') == 'all_dates' ? 'selected' : '' }}>Semua Tanggal
                    </option>
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
                        </form>
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
                                        <form action="{{ route('reservasi-hotel-checkin', $reservasi->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                            <button type="submit" class="btn btn-success btn-sm">Check In</button>
                                        </form>
                                    @endif
                                    <!-- Tombol Delete -->
                                    @if (in_array($reservasi->status, ['cancel']))
                                        <form action="{{ route('kasir-reservasi-hotel.destroy', $reservasi->id) }}"
                                            method="POST" style="display:inline;"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                        </form>
                                    @endif

                                </div>

                                @if($reservasi->status == 'check out')
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#infoReservasiModal{{ $reservasi->id }}">
                                    Pengambilan Hewan
                                </button>
                            @endif
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
                            <li class="page-item {{ ($reservasiHotels->currentPage() == $page) ? 'active' : '' }}">
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
        </form>
        <!-- Modal Section -->
        @foreach ($reservasiHotels as $reservasi)
            <div class="modal fade" id="infoReservasiModal{{ $reservasi->id }}" tabindex="-1"
                aria-labelledby="infoReservasiModalLabel{{ $reservasi->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="infoReservasiModalLabel{{ $reservasi->id }}">
                                Data Hewan
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('update-status-rincian-reservasi', $reservasi->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <input type="hidden" name="status" value="{{ request('status') }}">
                                <input type="hidden" name="date_filter" value="{{ request('date_filter') }}">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                               Update Status
                                            </th>
                                            <th>Hewan</th>
                                            <th>Room</th>
                                            <th>Tanggal Check-In</th>
                                            <th>Tanggal Check-Out</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reservasi->rincianReservasiHotel as $rincian)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="rincian_ids[]"
                                                        class="rincian-checkbox reservasi-{{ $reservasi->id }}"
                                                        value="{{ $rincian->id }}"
                                                        {{ $rincian->status == 'sudah di ambil' ? 'disabled' : '' }}>
                                                </td>
                                                <td>{{ $rincian->dataHewan->nama_hewan }} </td>
                                                <td>{{ $rincian->room->nama_ruangan }} </td>
                                                <td>{{ $reservasi->tanggal_checkin }}</td>
                                                <td>{{ $reservasi->tanggal_checkout }}</td>
                                                <td>{{ $rincian->status }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
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
            window.onload = function() {
                const params = new URLSearchParams(window.location.search);
                const status = params.get('status');
                const dateFilter = params.get('date_filter');
                if (status) document.getElementById('status').value = status;
                if (dateFilter) document.getElementById('date_filter').value = dateFilter;
            };

            // Update "Select All" status when individual checkbox is changed
 document.querySelectorAll('.rincian-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            const reservasiId = checkbox.classList[1].split('-')[1];
            const checkboxes = document.querySelectorAll(`.reservasi-${reservasiId}`);
            const selectAllCheckbox = document.querySelector(`.select-all[data-reservasi-id="${reservasiId}"]`);

            const allChecked = Array.from(checkboxes).every(cb => cb.checked || cb.disabled);
            selectAllCheckbox.checked = allChecked;
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
    // Ambil semua checkbox dengan class rincian-checkbox
    const checkboxes = document.querySelectorAll('.rincian-checkbox');
    
    // Untuk setiap reservasi, ambil tombol update statusnya
    document.querySelectorAll('.modal').forEach(modal => {
        const updateButton = modal.querySelector('button[type="submit"]');
        const modalCheckboxes = modal.querySelectorAll('.rincian-checkbox');
        
        // Nonaktifkan tombol saat pertama kali
        updateButton.disabled = true;
        
        // Tambahkan event listener untuk setiap checkbox dalam modal
        modalCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Periksa apakah ada checkbox yang dicentang
                const isAnyChecked = Array.from(modalCheckboxes).some(cb => cb.checked);
                
                // Aktifkan/nonaktifkan tombol berdasarkan status checkbox
                updateButton.disabled = !isAnyChecked;
            });
        });
    });
});
        </script>
    @endsection
