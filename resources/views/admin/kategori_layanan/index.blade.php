<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori Layanan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .container {
            margin-top: 20px;
        }

        table th,
        table td {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Data Kategori Layanan</h1>
        <a href="{{ route('kategori_layanan.create') }}" class="btn btn-primary">Tambah Kategori</a>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID Kategori Layanan</th>
                    <th>Nama Layanan</th>
                    <th>Deskripsi</th>
                    <th>Foto</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kategoriLayanan as $layanan)
                    <tr>
                        <td>{{ $layanan->id }}</td>
                        <td>{{ $layanan->nama_layanan }}</td>
                        <td>{{ $layanan->deskripsi }}</td>
                        <td>
                            @if ($layanan->foto)
                                <img src="{{ asset('storage/' . $layanan->foto) }}" alt="Foto" width="100">
                            @else
                                <p>Tidak ada foto</p>
                            @endif
                        </td>
                        <td>{{ $layanan->harga }}</td>
                        <td>
                            <a href="{{ route('kategori_layanan.edit', $layanan->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('kategori_layanan.destroy', $layanan->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
