<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Navbar Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            padding-bottom: 70px;
            overflow-x: hidden;
            width: 100%;
        }

        .navbar {
            height: 65px;
            background-color: #ffffff;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            padding: 0;
        }

        .navbar-nav {
            width: 100%;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .navbar-nav .nav-item {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .navbar-nav .nav-item .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8px 0;
            width: 100%;
            color: #6c757d;
        }

        .navbar-nav .nav-item i {
            font-size: 18px;
            margin-bottom: 4px;
        }

        .navbar-nav .nav-item span {
            font-size: 12px;
            line-height: 1;
        }

        /* Active state */
        .navbar-nav .nav-item .nav-link.active {
            color: #0d6efd;
            font-weight: 500;
        }

        /* Dropup menu */
        .dropdown-menu {
            margin-bottom: 8px;
            box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.15);
            border: none;
            border-radius: 12px;
            min-width: 150px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 14px;
        }

        .dropdown-divider {
            margin: 4px 0;
        }

        /* Container adjustments */
        .container-fluid {
            padding: 0 10px;
        }

        /* Button in dropdown */
        .dropdown-item.btn {
            text-align: left;
            width: 100%;
            background: none;
            border: none;
            padding: 8px 16px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand navbar-light fixed-bottom">
        <div class="container-fluid">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('booking') ? 'active' : '' }}" href="{{ url('/booking') }}">
                        <i class="fas fa-calendar-check"></i>
                        <span>Reservasi</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('layananPetHotel') ? 'active' : '' }}" href="{{ url('/layananPetHotel') }}">
                        <i class="fas fa-paw"></i>
                        <span>Layanan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ url('/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('hewan') ? 'active' : '' }}" href="{{ route('hewan.index') }}">
                        <i class="fas fa-dog"></i>
                        <span>Hewan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('profil') ? 'active' : '' }}" href="{{ url('/profil') }}">
                        <i class="fas fa-user"></i>
                        <span>Profil</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>