<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Hotel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Sticky Navbar */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: #ffffff;
            border-bottom: 2px solid #0d6efd;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
            height: 80px;
            /* Mengurangi tinggi navbar */

        }

        /* Supaya konten tidak tertutup navbar */
        body {
            padding-top: 80px;
            /* Sesuaikan dengan tinggi navbar */
        }


        .navbar-brand {
            font-size: 26px;
            font-weight: bold;
            color: #0d6efd;
        }

        .auth-buttons .btn {
            font-size: 16px;
            margin-left: 10px;
        }

        /* Carousel */
        .carousel img {
            object-fit: cover;
            height: 500px;
            border-radius: 10px;
        }

        /* Services */
        #services {
            padding: 80px 0;
            background: #f8f9fa;
        }

        .card {
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        /* About Us */
        #about {
            padding: 80px 0;
            background: white;
        }

        @media (max-width: 768px) {
            .carousel img {
                height: 300px;
            }
        }

        .logo {
            height: 90px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand" href="#">
                <img src="assets/images/pet_hotel__2_-removebg-preview.png" alt="Pet Hotel" class="logo">
            </a>
            <div class="d-flex auth-buttons">
                <a href="/login" class="btn btn-link text-primary">Login</a>
                <a href="/register" class="btn btn-link text-primary fw-bold">Register</a>
            </div>
        </div>
    </nav>



    <!-- Carousel -->
    <div id="carouselExampleFade" class="carousel slide carousel-fade">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="assets/images/2.png" class="d-block w-100" alt="Third slide">
            </div>
            <div class="carousel-item">
                <img src="assets/images/pet hotel (1).png" class="d-block w-100" alt="Third slide">
            </div>
            <div class="carousel-item">
                <img src="assets/images/3.png" class="d-block w-100" alt="Third slide">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Fasilitas -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-primary">Fasilitas Kami</h2>
            <div class="row mt-4">
                <!-- Fasilitas 1: Kamar Nyaman -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="fasilitas-card p-4 border rounded shadow-sm">
                        <img class="img-fluid mb-3" src="assets/images/pt5.jpg" alt="Kamar Nyaman"
                            style="height: 200px; object-fit: cover;">
                        <h3 class="mb-3">Kamar Nyaman</h3>
                        <p class="text-muted">Kamar luas, bersih, dan ber-AC</p>
                        <ul class="list-unstyled mb-4">
                            <li>Kasur empuk</li>
                            <li>Ventilasi udara baik</li>
                            <li>Pembersihan rutin</li>
                        </ul>
                    </div>
                </div>

                <!-- Fasilitas 2: Taman Bermain -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="fasilitas-card p-4 border rounded shadow-sm">
                        <img class="img-fluid mb-3" src="assets/images/pp.jpg" alt="Taman Bermain"
                            style="height: 200px; object-fit: cover;">
                            <h3 class="mb-3">Area Bermain Indoor</h3>
                            <p class="text-muted">Area bermain Indoor yang luas dan aman</p>
                        <ul class="list-unstyled mb-4">
                            <li>Tempat berlari bebas</li>
                            <li>Mainan interaktif</li>
                            <li>Keamanan terjamin</li>
                        </ul>
                    </div>
                </div>

                <!-- Fasilitas 3: Area Makan -->
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="fasilitas-card p-4 border rounded shadow-sm">
                        <img class="img-fluid mb-3" src="assets/images/ssss.jpg" alt="Taman Bermain"
                            style="height: 200px; object-fit: cover;">
                        <h3 class="mb-3">Area Bermain Outdoor</h3>
                        <p class="text-muted">Area bermain Outdoor yang luas dan aman</p>
                        <ul class="list-unstyled mb-4">
                            <li>Tempat berlari bebas</li>
                            <li>Mainan interaktif</li>
                            <li>Keamanan terjamin</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- About Us Section -->
    <section id="about" class="about-us-section bg-white">
        <div class="container">
            <h2 class="text-center text-primary mb-4">Tentang Kami</h2>
            <div class="row align-items-center">
                <div class="col-md-6">
                    <img src="{{ asset('assets/images/pet hotel (2).png') }}" class="img-fluid rounded shadow"
                        alt="Tentang Kami">
                </div>
                <div class="col-md-6">
                    <p class="text-muted">Pet Hotel adalah tempat terbaik untuk merawat hewan peliharaan Anda. Kami
                        menyediakan layanan penitipan dengan fasilitas modern dan aman.</p>
                    <h4 class="text-primary">Visi Kami</h4>
                    <p class="text-muted">Menjadi tempat terbaik untuk perawatan hewan peliharaan yang memberikan
                        kenyamanan dan pelayanan berkualitas tinggi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Us Section -->
    <section id="contact" class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="fw-bold text-primary mb-4">Hubungi Kami</h2>
            <p class="text-muted">Silakan hubungi kami untuk informasi lebih lanjut.</p>
            <div class="row justify-content-center">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="fas fa-phone fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Telepon</h5>
                        <p class="text-muted">+62 812 3456 7890</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Email</h5>
                        <p class="text-muted">info@pethotel.com</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm p-4">
                        <i class="fas fa-map-marker-alt fa-3x text-primary mb-3"></i>
                        <h5 class="fw-bold">Alamat</h5>
                        <p class="text-muted">Jl. Pet Hotel No. 123, Jakarta</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- <!-- Sponsor Section -->
    <section id="sponsor" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold text-primary mb-4">Sponsor Kami</h2>
            <div class="row justify-content-center">
                <div class="col-md-3 mb-3">
                    <img src="assets/images/sponsor1.png" class="img-fluid" alt="Sponsor 1">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="assets/images/sponsor2.png" class="img-fluid" alt="Sponsor 2">
                </div>
                <div class="col-md-3 mb-3">
                    <img src="assets/images/sponsor3.png" class="img-fluid" alt="Sponsor 3">
                </div>
            </div>
        </div>
    </section> --}}

    <!-- Footer -->
    <footer class="text-center text-white bg-primary py-3">
        <p class="mb-0">© 2025 Pet Hotel. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
