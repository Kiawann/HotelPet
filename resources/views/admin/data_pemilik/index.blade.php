@extends('layouts.app')

@section('title', 'Data Semua Akun')

@section('content')
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Dropdown Filter Role -->
        <div class="form-group mb-4">
            <label for="filter_role">Filter berdasarkan Role:</label>
            <select name="filter_role" id="filter_role" class="form-control w-25 d-inline">
                <option value="all" {{ isset($selectedRole) && $selectedRole === 'all' ? 'selected' : '' }}>Semua Role</option>
                @foreach ($roles as $role)
                    <option value="{{ $role }}" {{ isset($selectedRole) && $selectedRole === $role ? 'selected' : '' }}>
                        {{ ucfirst($role) }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Form Pencarian -->
        <div class="form-group mb-4 d-flex">
            <label for="search" class="me-2">Cari Pemilik:</label>
            <input type="text" id="search" class="form-control w-50" placeholder="Cari berdasarkan nama atau nomor telepon">
        </div>

        <div class="mb-3">
            <a href="{{ route('admin-user-create') }}" class="btn btn-primary">
                Tambah perawat atau kasir
            </a>
        </div>

        <!-- Tabel Data Pemilik -->
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama Pemilik</th>
                    <th>Jenis Kelamin</th>
                    <th>Nomor Telepon</th>
                    <th>Foto</th>
                    <th>Role User</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="pemilikTableBody">
                @foreach ($pemilik as $p)
                    @php
                        $user = $users->where('id', optional($p->user)->id)->first();
                        $role = optional($p->user)->role ?? '';
                    @endphp
                    <tr data-role="{{ $role }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>{{ $p->jenis_kelamin }}</td>
                        <td>{{ $p->phone }}</td>
                        <td>
                            @if ($p->foto)
                                <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto Pemilik" width="50" height="50">
                            @else
                                No Photo
                            @endif
                        </td>
                        <td>{{ $role }}</td>
                        <td>
                            {{-- <form action="{{ route('data_pemilik.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mb-2 w-100">Hapus</button>
                            </form> --}}

                            @if ($role == 'kasir' || $role == 'perawat')
                                <button type="button" class="btn btn-warning btn-sm w-100" data-bs-toggle="modal" data-bs-target="#changeRoleModal-{{ $p->id }}">
                                    Change Role
                                </button>

                                <!-- Modal Change Role -->
                                <div class="modal fade" id="changeRoleModal-{{ $p->id }}" tabindex="-1" aria-labelledby="changeRoleModalLabel-{{ $p->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="changeRoleModalLabel-{{ $p->id }}">
                                                    Change Role for {{ $p->nama }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <form action="{{ route('change-role', $user->id) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-body">
                                                    <label for="role-{{ $p->id }}">Choose New Role:</label>
                                                    <select name="role" id="role-{{ $p->id }}" class="form-control">
                                                        <option value="kasir" {{ $role == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                                        <option value="perawat" {{ $role == 'perawat' ? 'selected' : '' }}>Perawat</option>
                                                    </select>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
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
                        <a class="page-link" href="{{ $pemilik->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($pemilik->getUrlRange(1, $pemilik->lastPage()) as $page => $url)
                        <li class="page-item {{ $pemilik->currentPage() == $page ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="{{ $pemilik->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Tambahkan JavaScript untuk Bootstrap -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Script untuk filter dan live search -->
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById('search');
                const filterSelect = document.getElementById('filter_role');
                const rows = document.querySelectorAll('#pemilikTableBody tr');

                function filterTable() {
                    const searchText = searchInput.value.toLowerCase();
                    const selectedRole = filterSelect.value; // 'all' atau role spesifik

                    rows.forEach(row => {
                        const namaPemilik = row.children[2].textContent.toLowerCase();
                        const nomorTelepon = row.children[4].textContent.toLowerCase();
                        const rowRole = row.getAttribute('data-role').toLowerCase();

                        const matchesSearch = namaPemilik.includes(searchText) || nomorTelepon.includes(searchText);
                        const matchesRole = selectedRole === 'all' || rowRole === selectedRole.toLowerCase();

                        row.style.display = (matchesSearch && matchesRole) ? '' : 'none';
                    });
                }

                searchInput.addEventListener('input', filterTable);
                filterSelect.addEventListener('change', filterTable);
            });
        </script>
    </div>
@endsection
