<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Reservasi Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1>Form Reservasi Layanan</h1>
    <form action="{{ route('reservasi_layanan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Input Pemilik -->
        <div class="mb-3">
            <label for="data_pemilik_id" class="form-label">Pemilik</label>
            <select name="data_pemilik_id" id="data_pemilik_id" class="form-control" required>
                <option value="">Pilih Pemilik</option>
                @foreach($pemilik as $pemilikItem)
                    <option value="{{ $pemilikItem->id }}">{{ $pemilikItem->nama }}</option>
                @endforeach
            </select>
            @error('data_pemilik_id')
                <div class="alert alert-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        <!-- Container untuk Dynamic Forms -->
        <div id="dynamic-form-container">
            <div class="dynamic-form-item border p-3 mb-3">
                <!-- Input Hewan -->
                <div class="mb-3">
                    <label for="data_hewan_id" class="form-label">Hewan</label>
                    <select name="data_hewan_id[]" class="form-control" required>
                        <option value="">Pilih Hewan</option>
                        @foreach($hewan as $hewanItem)
                            <option value="{{ $hewanItem->id }}">{{ $hewanItem->nama_hewan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Layanan -->
                <div class="mb-3">
                    <label for="kategori_layanan_id" class="form-label">Layanan</label>
                    <select name="kategori_layanan_id[]" class="form-control layanan-select" required>
                        <option value="">Pilih Layanan</option>
                        @foreach($layanan as $layananItem)
                            <option value="{{ $layananItem->id }}">{{ $layananItem->nama_layanan }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Tanggal Layanan -->
                <div class="mb-3">
                    <label for="tanggal_layanan_1" class="form-label">Tanggal Layanan</label>
                    <input type="date" name="tanggal_layanan[]" class="form-control" required>
                </div>
            </div>
        </div>

        <!-- Tombol Tambah Form -->
        <button type="button" id="add-form-btn" class="btn btn-secondary mb-3">Tambah Form</button>

        <!-- Tombol Submit -->
        <button type="submit" class="btn btn-primary">Simpan Reservasi</button>
    </form>
</div>

<script>
    document.getElementById('add-form-btn').addEventListener('click', function() {
        const container = document.getElementById('dynamic-form-container');
        const newForm = document.createElement('div');
        newForm.classList.add('dynamic-form-item', 'border', 'p-3', 'mb-3');
        
        // Clone form fields
        newForm.innerHTML = `
            <div class="mb-3">
                <label class="form-label">Hewan</label>
                <select name="data_hewan_id[]" class="form-control" required>
                    <option value="">Pilih Hewan</option>
                    @foreach($hewan as $hewanItem)
                        <option value="{{ $hewanItem->id }}">{{ $hewanItem->nama_hewan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Layanan</label>
                <select name="kategori_layanan_id[]" class="form-control" required>
                    <option value="">Pilih Layanan</option>
                    @foreach($layanan as $layananItem)
                        <option value="{{ $layananItem->id }}">{{ $layananItem->nama_layanan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal Layanan</label>
                <input type="date" name="tanggal_layanan[]" class="form-control" required>
            </div>
        `;

        // Append new form to container
        container.appendChild(newForm);
    });
</script>

</body>
</html>
