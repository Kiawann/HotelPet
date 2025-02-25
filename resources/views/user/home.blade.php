<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pet Hotel</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}
    <style>
        /* body {
            padding-top: 80px;
        } */

        .carousel-inner img {
            height: 100%;
            object-fit: cover;
        }

        .card img {
            object-fit: cover;
            height: 200px;
        }

        .hero-section {
            background-color: #f8f9fa;
            padding: 80px 0;
        }

        .about-us-section {
            padding: 60px 0;
        }

        .about-us-section h2 {
            font-weight: 700;
            color: #0d6efd;
        }

        .icon-box {
            text-align: center;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .icon-box:hover {
            transform: translateY(-10px);
        }

        .icon-box i {
            font-size: 36px;
            color: #0d6efd;
        }

        .icon-box p {
            font-size: 18px;
            color: #333;
        }

        /* Section Styling */
        #services h2 {
            font-size: 2.5rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Card Styling */
        #services .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        #services .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        /* Icon Circle */
        .icon-container {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }

        /* Button Styling */
        #services .btn {
            font-size: 1rem;
            padding: 10px 20px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        #services .btn:hover {
            background-color: #007bff;
            color: #fff;
        }
    </style>

</head>

<body>

    @include('layouts.navbar')

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

    <!-- Services Section -->
    <section id="services" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold text-primary mb-5">Layanan Kami</h2>
            <div class="row justify-content-center">
                <!-- Pet Hotel Service -->
                <div class="col-md-5 mb-4">
                    <div class="card shadow-lg h-100 border-0 rounded-4">
                        <div class="card-body text-center py-5">
                            <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-4 mx-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-paw"></i>
                            </div>
                            <h5 class="card-title fw-bold text-primary">Pet Hotel</h5>
                            <p class="card-text text-muted">
                                Tempat yang aman dan nyaman untuk menitipkan hewan kesayangan Anda, dilengkapi fasilitas
                                premium.
                            </p>
                            <a href="layananPetHotel" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>

                {{-- <!-- Grooming Service -->
                <div class="col-md-5 mb-4">
                    <div class="card shadow-lg h-100 border-0 rounded-4">
                        <div class="card-body text-center py-5">
                            <div class="icon-container bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-4 mx-auto"
                                style="width: 80px; height: 80px;">
                                <i class="fas fa-scissors"></i>
                            </div>
                            <h5 class="card-title fw-bold text-primary">Grooming</h5>
                            <p class="card-text text-muted">
                                Perawatan profesional untuk menjaga kebersihan dan kesehatan hewan peliharaan Anda.
                            </p>
                            <a href="layananGrooming" class="btn btn-outline-primary">
                                <i class="fas fa-info-circle"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                </div> --}}
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
  

    <!-- Location Section -->
    {{-- <section id="location" class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-primary mb-4">Lokasi Kami</h2>
            <iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="400" frameborder="0"
                style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
        </div>
    </section> --}}

    <!-- WhatsApp Button -->
    {{-- <a href="https://wa.me/6282318077074" class="btn btn-success position-fixed"
        style="bottom: 20px; right: 20px; border-radius: 50%;">
        <i class="fab fa-whatsapp fa-2x"></i>
    </a> --}}

</body>

</html>
