@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('room.create') }}" class="btn btn-primary">Tambah Kamar</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div style="overflow-y: auto; max-height: 60vh; border: 1px solid #ddd; padding: 10px;">
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
                            <a href="{{ route('room.edit', $room) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('room.destroy', $room) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Tidak ada data kamar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-3">
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm">
                    <li class="page-item">
                        <a class="page-link" href="{{ $rooms->previousPageUrl() }}" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    @foreach ($rooms->getUrlRange(1, $rooms->lastPage()) as $page => $url)
                        <li class="page-item {{ ($rooms->currentPage() == $page) ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach
                    <li class="page-item">
                        <a class="page-link" href="{{ $rooms->nextPageUrl() }}" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

</div>
@endsection
