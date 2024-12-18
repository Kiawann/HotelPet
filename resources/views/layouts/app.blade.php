<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">Admin </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard.index') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kategori_hewan.index') }}">Kategori Hewan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('data_hewan.index') }}">Data Hewan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('category_hotel.index') }}">Kategori Hotel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('kategori_layanan.index') }}">Kategori layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('room.index') }}">Ruangan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('laporan_hewan.index') }}">Laporan Hewan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reservasi_hotel.index') }}">Reservasi Hotel</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reservasi_layanan.index') }}">Reservasi Layanan</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-danger btn-sm nav-link text-white border-0">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <header class="mb-4">
            <h1 class="text-primary">@yield('title', 'Admin Panel')</h1>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
