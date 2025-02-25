@extends('layouts.perawat')

@section('title', 'Daftar Ruangan')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- <h1 class="text-primary">Daftar Ruangan</h1> --}}
        {{-- <a href="{{ route('room.create') }}" class="btn btn-primary">Tambah Kamar</a> --}}
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-striped table-hover">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Kategori Hotel</th>
                <th>Nama Ruangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $room->category_hotel->nama_kategori }}</td>
                    <td>{{ $room->nama_ruangan }}</td>
                    <td>
                        <span class="badge {{ $room->status == 'Tersedia' ? 'bg-success' : 'bg-danger' }}">
                            {{ $room->status }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $room->id }}">
                            Update Status
                        </button>
                    </td>
                </tr>

              <!-- Modal Update Status -->
<div class="modal fade" id="updateStatusModal{{ $room->id }}" tabindex="-1" aria-labelledby="updateStatusLabel{{ $room->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateStatusLabel{{ $room->id }}">Update Status Ruangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('perawat-room-update', $room->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Pilih Status</label>
                        <select class="form-select" name="status" required>
                            <option value="Tersedia" {{ $room->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Tidak Tersedia" {{ $room->status == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data kamar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
