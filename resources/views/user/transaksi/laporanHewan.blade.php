<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hewan</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            margin: 20px;
        }

        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .card-header {
            background-color: #ff5733;
            color: white;
            font-weight: bold;
            text-align: center;
            border-radius: 15px 15px 0 0;
            padding: 15px;
        }

        .laporan-content {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .laporan-content div {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .laporan-content div:last-child {
            border-bottom: none;
        }

        .laporan-media img, .laporan-media video {
            border-radius: 10px;
            width: 100px;
            height: 100px;
            object-fit: cover;
        }

        .empty-message {
            color: #888;
            font-style: italic;
            text-align: center;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            border-radius: 30px;
            padding: 12px 35px;
            text-align: center;
            transition: background-color 0.3s ease;
            border: none;
            display: inline-block;
            text-decoration: none;
            font-size: 14px;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-paw"></i> Laporan Hewan - Reservasi ID: {{ $reservasi->id }}</h4>
            </div>
            <div class="card-body">
                @forelse ($laporanHewan as $laporan)
                    <div class="card">
                        <div class="laporan-content">
                            <div><strong>Tanggal Laporan:</strong> {{ \Carbon\Carbon::parse($laporan->tanggal_laporan)->format('d-m-Y') }}</div>
                            <div><strong>Makan:</strong> {{ $laporan->Makan }}</div>
                            <div><strong>Minum:</strong> {{ $laporan->Minum }}</div>
                            <div><strong>BAB:</strong> {{ $laporan->Bab }}</div>
                            <div><strong>BAK:</strong> {{ $laporan->Bak }}</div>
                            <div><strong>Keterangan:</strong> {{ $laporan->keterangan }}</div>
                            <div class="laporan-media">
                                @if ($laporan->foto)
                                    @foreach(json_decode($laporan->foto) as $foto)
                                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto Hewan">
                                    @endforeach
                                @else
                                    <span class="empty-message">Tidak ada foto</span>
                                @endif
                            </div>
                            <div class="laporan-media">
                                @if ($laporan->video)
                                    @foreach(json_decode($laporan->video) as $video)
                                        <video width="150" controls>
                                            <source src="{{ asset('storage/' . $video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    @endforeach
                                @else
                                    <span class="empty-message">Tidak ada video</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="empty-message">Belum ada laporan hewan.</p>
                @endforelse
            </div>
        </div>
        <div class="button-group" style="display: flex; flex-direction: column; align-items: center; margin-top: 30px;">
            <div style="display: flex; justify-content: center; width: 100%; flex-wrap: wrap;">
                <a href="{{ url()->previous() }}" class="btn btn-secondary d-flex align-items-center justify-content-center"
                    style="min-width: 150px; height: 40px; padding: 0 15px;">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</body>

</html>