@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Transaksi</h1>
    <a href="{{ route('transaksi.create') }}" class="btn btn-primary mb-3">Tambah Transaksi</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Pemilik</th>
                <th>Subtotal</th>
                <th>Status Pembayaran</th>
                <th>Tanggal Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->dataPemilik->nama ?? '-' }}</td>
                <td>{{ $item->subtotal ?? '-' }}</td>
                <td>{{ $item->status_pembayaran }}</td>
                <td>{{ $item->tanggal_pembayaran }}</td>
                <td>
                    <a href="{{ route('transaksi.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('transaksi.destroy', $item->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
