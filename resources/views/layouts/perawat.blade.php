<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perawat')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <style>
        .navbar {
            height: 60px;
            background-color: #ffffff;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
        }
    
        .navbar-nav {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 5px 0;
        }
    
        .nav-item {
            flex: 1;
            text-align: center;
            padding: 5px 0;
        }
    
        .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 6px 0;
            color: #6c757d;
            transition: all 0.3s ease;
        }
    
        .nav-link i {
            font-size: 18px; /* Ukuran ikon lebih kecil */
            margin-bottom: 3px;
        }
    
        .nav-link span {
            font-size: 11px; /* Ukuran teks lebih kecil */
            font-weight: 500;
        }
    
        /* Warna untuk menu aktif */
        .nav-link.active {
            color: #0d6efd !important;
            font-weight: bold;
            background-color: #e6f0ff !important;
            border-radius: 8px;
            padding: 6px 10px;
        }
    
        /* Hover effect */
        .nav-link:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    
        /* Style tombol logout */
        .nav-item form button {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 18px;
            cursor: pointer;
        }
    
        .nav-item form span {
            font-size: 11px;
            color: #dc3545;
            font-weight: 500;
        }
    </style>
    
</head>
<body>
    <div class="container mt-4">
        <header class="mb-4">
            <h1 class="text-primary">@yield('title', 'Perawat')</h1>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Navbar Bawah -->
    <nav class="navbar navbar-expand navbar-light fixed-bottom bg-white shadow">
        <div class="container-fluid d-flex justify-content-around">
            <ul class="navbar-nav w-100 text-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perawat-dashboard') ? 'active' : '' }}" href="{{ route('perawat-dashboard') }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perawat-room.index') ? 'active' : '' }}" href="{{ route('perawat-room.index') }}">
                        <i class="fas fa-door-open"></i>
                        <span>Ruangan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('laporan_hewan.index') ? 'active' : '' }}" href="{{ route('laporan_hewan.index') }}">
                        <i class="fas fa-paw"></i>
                        <span>Laporan Hewan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perawat-reservasi-hotel.index') ? 'active' : '' }}" href="{{ route('perawat-reservasi-hotel.index') }}">
                        <i class="fas fa-file-alt"></i>
                        <span>Buat Laporan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('perawat-profil') ? 'active' : '' }}" href="{{ route('perawat-profil') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>

                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button class="nav-link">
                            <i class="fas fa-sign-out-alt text-danger"></i>
                            <span class="text-danger">Logout</span>
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
