@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Semua Akun</h1>

    <!-- Dropdown Filter Role -->
    <div class="form-group mb-4">
        <label for="filter_role">Filter berdasarkan Role:</label>
        <select name="filter_role" id="filter_role" class="form-control w-25 d-inline">
            <option value="all" {{ $selectedRole === 'all' ? 'selected' : '' }}>Semua Role</option>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ $selectedRole === $role ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
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
            </tr>
        </thead>
        <tbody>
            @foreach($pemilik as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->id }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->jenis_kelamin }}</td>
                <td>{{ $p->phone }}</td>
                <td>
                    @if($p->foto)
                        <img src="{{ asset('storage/' . $p->foto) }}" alt="Foto Pemilik" width="50" height="50">
                    @else
                        No Photo
                    @endif
                </td>
                <td>{{ $p->user->role }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    // Tambahkan event listener untuk dropdown filter
    document.getElementById('filter_role').addEventListener('change', function() {
        // Redirect ke URL dengan parameter filter_role
        const selectedRole = this.value;
        const url = new URL(window.location.href);
        url.searchParams.set('filter_role', selectedRole);
        window.location.href = url.toString();
    });
</script>
@endsection
