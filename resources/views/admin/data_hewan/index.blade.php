@extends('layouts.app')

@section('title', 'Data Hewan')

@section('content')
    <a href="{{ route('data_hewan.create') }}" class="btn btn-primary">Tambah Data Hewan</a>
    <div class="mt-3">
        <select id="filterKategori" class="form-control w-25 d-inline">
            <option value="">Semua Kategori</option>
            @foreach($kategoriHewan as $kategori)
                <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
            @endforeach
        </select>
        <input type="text" id="searchHewan" class="form-control w-25 d-inline" placeholder="Cari hewan atau pemilik...">
    </div>

    <!-- Tambahkan div pembungkus agar tabel bisa di-scroll -->
    <div class="table-responsive" style="max-height: 400px; overflow-y: auto; margin-top: 10px;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Hewan</th>
                    <th>Pemilik</th>
                    <th>Kategori Hewan</th>
                    <th>Umur (bulan)</th>
                    <th>Berat Badan (kg)</th>
                    <th>Jenis Kelamin</th>
                    <th>Warna</th>
                    <th>Ras Hewan</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                @foreach($dataHewan as $hewan)
                <tr data-kategori="{{ $hewan->kategoriHewan->id ?? '' }}">
                    <td>{{ $hewan->nama_hewan }}</td>
                    <td>{{ $hewan->pemilik->nama ?? 'Tidak ada pemilik' }}</td>
                    <td>{{ $hewan->kategoriHewan->nama_kategori ?? 'Tidak ada kategori' }}</td>
                    <td>{{ $hewan->umur }}</td>
                    <td>{{ $hewan->berat_badan }}</td>
                    <td>{{ $hewan->jenis_kelamin }}</td>
                    <td>{{ $hewan->warna }}</td>
                    <td>{{ $hewan->ras_hewan }}</td>
                    <td>
                        @if ($hewan->foto)
                            <img src="{{ asset('storage/' . $hewan->foto) }}" alt="Foto" width="50">
                        @else
                            Tidak ada foto
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('data_hewan.edit', $hewan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    <li class="page-item">
                        <a class="page-link" href="{{ $dataHewan->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($dataHewan->getUrlRange(1, $dataHewan->lastPage()) as $page => $url)
                        <li class="page-item {{ ($dataHewan->currentPage() == $page) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="{{ $dataHewan->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const searchInput = document.getElementById('searchHewan');
            const filterSelect = document.getElementById('filterKategori');
            const rows = document.querySelectorAll('#tableBody tr');

            function filterTable() {
                const searchText = searchInput.value.toLowerCase();
                const selectedKategori = filterSelect.value;

                rows.forEach(row => {
                    const namaHewan = row.children[0].textContent.toLowerCase();
                    const pemilik = row.children[1].textContent.toLowerCase();
                    const kategori = row.getAttribute('data-kategori');

                    const matchesSearch = namaHewan.includes(searchText) || pemilik.includes(searchText);
                    const matchesKategori = selectedKategori === '' || kategori === selectedKategori;

                    row.style.display = (matchesSearch && matchesKategori) ? '' : 'none';
                });
            }

            searchInput.addEventListener('input', filterTable);
            filterSelect.addEventListener('change', filterTable);
        });
    </script>
@endsection
