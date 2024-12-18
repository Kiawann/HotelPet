@extends('layouts.app')

@section('title', 'Edit Data Hewan')

@section('content')
    <h1>Edit Data Hewan</h1>

    <form action="{{ route('data_hewan.update', $dataHewan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Nama Hewan</label>
            <input type="text" name="nama_hewan" class="form-control" value="{{ old('nama_hewan', $dataHewan->nama_hewan) }}">
            @error('nama_hewan')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Pemilik</label>
            <select name="id_data_pemilik" class="form-control">
                <option value="">Pilih Pemilik</option>
                @foreach($pemilik as $p)
                    <option value="{{ $p->id_data_pemilik }}" {{ $p->id_data_pemilik == $dataHewan->id_data_pemilik ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @error('id_data_pemilik')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Kategori Hewan</label>
            <select name="id_kategori_hewan" class="form-control">
                <option value="">Pilih Kategori Hewan</option>
                @foreach($kategoriHewan as $kategori)
                    <option value="{{ $kategori->id_kategori_hewan }}" {{ $kategori->id_kategori_hewan == $dataHewan->id_kategori_hewan ? 'selected' : '' }}>{{ $kategori->nama_kategori }}</option>
                @endforeach
            </select>
            @error('id_kategori_hewan')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Umur</label>
            <input type="number" name="umur" class="form-control" value="{{ old('umur', $dataHewan->umur) }}">
            @error('umur')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Berat Badan</label>
            <input type="number" name="berat_badan" class="form-control" value="{{ old('berat_badan', $dataHewan->berat_badan) }}">
            @error('berat_badan')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Jenis Kelamin</label>
            <select name="jenis_kelamin" class="form-control">
                <option value="Jantan" {{ $dataHewan->jenis_kelamin == 'Jantan' ? 'selected' : '' }}>Jantan</option>
                <option value="Betina" {{ $dataHewan->jenis_kelamin == 'Betina' ? 'selected' : '' }}>Betina</option>
            </select>
            @error('jenis_kelamin')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Warna</label>
            <input type="text" name="warna" class="form-control" value="{{ old('warna', $dataHewan->warna) }}">
            @error('warna')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Ras Hewan</label>
            <input type="text" name="ras_hewan" class="form-control" value="{{ old('ras_hewan', $dataHewan->ras_hewan) }}">
            @error('ras_hewan')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label>Foto</label>
            <input type="file" name="foto" class="form-control">
            @if ($dataHewan->foto)
                <img src="{{ asset('storage/' . $dataHewan->foto) }}" alt="Foto" width="100" class="mt-2">
            @else
                <p>Tidak ada foto</p>
            @endif
            @error('foto')
            <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
@endsection
