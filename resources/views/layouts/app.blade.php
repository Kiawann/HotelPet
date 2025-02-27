<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Admin Panel')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    /* Layout dasar */
    body {
      display: flex;
      height: 100vh;
      overflow: hidden;
      background-color: #f8f9fa;
      color: #0d6efd;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Sidebar */
    .sidebar {
      width: 250px;
      height: 100vh;
      background: linear-gradient(135deg, #0d6efd, #0056b3);
      padding-top: 20px;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
      transition: all 0.3s ease;
      box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #fff;
      font-size: 16px;
      font-weight: 500;
      text-decoration: none;
      border-radius: 5px;
      margin: 5px 15px;
      transition: background 0.3s, color 0.3s;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #fff;
      color: #0d6efd;
    }

    /* Konten utama */
    .content {
      margin-left: 260px;
      padding: 20px;
      width: calc(100% - 260px);
      transition: all 0.3s ease;
    }

    .sidebar.closed {
      left: -250px;
    }

    .content.full-width {
      margin-left: 0;
      width: 100%;
    }

    /* Header */
    header {
      background-color: #fff;
      border-radius: 8px;
      padding: 10px 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    /* Tombol Toggle */
    .toggle-btn {
      background: linear-gradient(135deg, #0d6efd, #0056b3);
      border: none;
      color: #fff;
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 5px;
      transition: background 0.3s, transform 0.3s;
    }

    .toggle-btn:hover {
      background: #0056b3;
      transform: scale(1.05);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .sidebar {
        width: 200px;
        left: -200px;
      }

      .sidebar.closed {
        left: -200px;
      }

      .content {
        margin-left: 0;
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="sidebar open" id="sidebar">
    <!-- Logo -->
    <div class="text-center mb-3">
      <img src="{{ asset('assets/images/pet_hotel__2_-removebg-preview.png') }}" alt="Logo" class="img-fluid"
        style="max-width: 150px;">
    </div>

    <a href="{{ route('dashboard.index') }}"
      class="{{ request()->routeIs('dashboard.index') ? 'active' : '' }}">Dashboard</a>
    <a href="{{ route('kategori_hewan.index') }}"
      class="{{ request()->routeIs('kategori_hewan.index') ? 'active' : '' }}">Kategori Hewan</a>
    <a href="{{ route('data_hewan.index') }}"
      class="{{ request()->routeIs('data_hewan.index') ? 'active' : '' }}">Data Hewan</a>
    <a href="{{ route('data_pemilik.index') }}"
      class="{{ request()->routeIs('data_pemilik.index') ? 'active' : '' }}">Data Semua Akun</a>
    <a href="{{ route('category_hotel.index') }}"
      class="{{ request()->routeIs('category_hotel.index') ? 'active' : '' }}">Kategori Hotel</a>
    <a href="{{ route('room.index') }}"
      class="{{ request()->routeIs('room.index') ? 'active' : '' }}">Ruangan</a>
    <a href="{{ route('laporan-transaksi') }}"
    class="{{ request()->routeIs('laporan-transaksi') ? 'active' : '' }}">Laporan Transaksi</a>
     <a href="{{ route('laporan-denda') }}"
    class="{{ request()->routeIs('laporan-denda') ? 'active' : '' }}">Laporan Denda</a>
    
  </div>

  <div class="content" id="content">
    <header class="mb-4 d-flex justify-content-between align-items-center">
      <!-- Bagian kiri: Tombol Toggle + Judul -->
      <div class="d-flex align-items-center">
        <button class="toggle-btn me-3" onclick="toggleSidebar()">
          <i class="bi bi-list"></i>
        </button>
        <h1 class="text-primary m-0">@yield('title', 'Admin Panel')</h1>
      </div>

      <!-- Bagian kanan: Dropdown User -->
      <div class="dropdown">
        <a class="nav-link dropdown-toggle d-flex align-items-center text-primary fw-semibold" href="#"
          role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          @if (Auth::user()->foto)
          <img src="{{ Auth::user()->foto }}" alt="Profile" class="rounded-circle border border-2" width="40"
            height="40">
          @else
          <i class="bi bi-person-circle fs-3"></i> <!-- Ikon default -->
          @endif
          <span class="ms-2">{{ Auth::user()->name }}</span>
        </a>

        <ul class="dropdown-menu dropdown-menu-end animate__animated animate__fadeIn" aria-labelledby="userDropdown">
          <li class="px-3 py-2 text-center">
            @if (Auth::user()->foto)
            <img src="{{ Auth::user()->foto }}" alt="Profile" class="rounded-circle border border-2" width="60"
              height="60">
            @else
            <i class="bi bi-person-circle fs-1 text-secondary"></i>
            @endif
            <p class="mb-1 mt-2 fw-semibold">{{ Auth::user()->name }}</p>
            <small class="text-muted">{{ Auth::user()->email }}</small>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item text-danger d-flex align-items-center">
                <i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout
              </button>
            </form>
          </li>
        </ul>
      </div>
    </header>

    <main>
      @yield('content')
    </main>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const content = document.getElementById('content');
      sidebar.classList.toggle('closed');
      content.classList.toggle('full-width');
    }
  </script>
</body>

</html>
