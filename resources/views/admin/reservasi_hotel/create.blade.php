@extends('layouts.app')

@section('title', 'Tambah Reservasi Hotel')

@section('content')
<div class="container mt-5">
    <h1>Tambah Reservasi Hotel</h1>

    <div class="alert alert-danger" id="errorMessages" style="display: none;">
        <!-- Pesan error akan muncul di sini -->
    </div>

    <form action="{{ route('reservasi_hotel.store') }}" method="POST">
        @csrf

        <!-- Input Pemilik -->
        <div class="mb-3">
            <label for="data_pemilik_id" class="form-label">Nama Pemilik</label>
            <select name="data_pemilik_id" id="data_pemilik_id" class="form-control">
                <option value="">Pilih Pemilik</option>
                @foreach($dataPemilik as $pemilik)
                    <option value="{{ $pemilik->id }}">{{ $pemilik->nama }}</option>
                @endforeach
            </select>
        </div>

        <!-- Container Formulir Hewan, Ruangan, dan Tanggal -->
        <div id="reservationForms">
            <div class="reservation-form">
                <div class="mb-3">
                    <label for="data_hewan_id" class="form-label">Hewan</label>
                    <select name="id_data_hewan[]" class="form-control">
                        <option value="">Pilih Hewan</option>
                        @foreach($dataHewan as $hewan)
                            <option value="{{ $hewan->id }}">{{ $hewan->nama_hewan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="room_id" class="form-label">Room</label>
                    <select name="id_room[]" class="form-control">
                        <option value="">Pilih Room</option>
                        @foreach($rooms as $room)
                            @if($room->status === 'Tersedia')
                                <option value="{{ $room->id }}">
                                    {{ $room->nama_ruangan }} - Rp{{ number_format($room->category_hotel->harga, 0, ',', '.') }}
                                </option>   
                            @endif
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="tanggal_checkout" class="form-label">Tanggal Check-Out</label>
                    <input type="date" name="tanggal_checkout[]" class="form-control">
                </div>

                <hr>
            </div>
        </div>

        <!-- Tombol Tambah Form -->
        <button type="button" class="btn btn-primary mb-3" id="addForm">Tambah Form</button>   

        <!-- Tombol Simpan -->
        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('reservasi_hotel.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    document.getElementById('addForm').addEventListener('click', function() {
        // Pilih elemen formulir pertama untuk diduplikasi
        const formContainer = document.getElementById('reservationForms');
        const formTemplate = document.querySelector('.reservation-form');
        
        // Kloning elemen formulir
        const newForm = formTemplate.cloneNode(true);

        // Hapus nilai input pada form baru
        const inputs = newForm.querySelectorAll('input, select');
        inputs.forEach(input => {
            if (input.type !== 'hidden') {
                input.value = '';
            }
        });

        // Tambahkan formulir baru ke container
        formContainer.appendChild(newForm);
    });
</script>
@endsection
