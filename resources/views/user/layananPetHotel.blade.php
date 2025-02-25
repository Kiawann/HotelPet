<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Layanan Pet Hotel</title>
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
    <style>
        /* Body padding for fixed navbar */
        /* body {
            padding-top: 80px;
        } */

        .hero-section {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            padding: 100px 0;
            text-align: center;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-section p {
            font-size: 1.2rem;
            margin: 20px 0;
        }

        .hero-section .btn {
            font-size: 1rem;
            padding: 10px 20px;
            transition: all 0.3s ease-in-out;
        }

        .hero-section .btn:hover {
            transform: scale(1.1);
        }

        .pricing-card {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .pricing-card h3 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        .pricing-card p {
            font-size: 1.1rem;
            color: #555;
        }

        .pricing-card ul {
            padding: 0;
            list-style: none;
        }

        .pricing-card ul li {
            margin: 10px 0;
            font-size: 1rem;
        }

        .pricing-card .btn {
            font-size: 1rem;
            margin-top: 15px;
        }
    </style>
</head>

<body>

    @include('layouts.navbar')
    
    <!-- Hero Section -->
    <section class="hero-section">
        <h1>Selamat Datang di Layanan Pet Hotel</h1>
        <p>Penginapan terbaik untuk kenyamanan hewan peliharaan Anda</p>
        <a href="{{ route('booking.create') }}" class="btn btn-primary btn-lg">Pesan Sekarang</a>
    </section>

    <!-- Pricing -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="text-primary">Harga Paket</h2>
            <div class="row mt-4">
                @foreach ($categories as $category)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="pricing-card p-4 border rounded shadow-sm">
                            <!-- Display the category image if available -->
                            <img class="img-fluid mb-3"
                                src="{{ $category->foto ? asset('storage/' . $category->foto) : asset('assets/images/default-image.jpg') }}"
                                alt="{{ $category->nama_kategori }}" style="height: 200px; object-fit: cover;">

                            <h3 class="mb-3">{{ $category->nama_kategori }}</h3>
                            <p class="text-muted">Rp{{ number_format($category->harga, 0, ',', '.') }}/hari</p>
                            <ul class="list-unstyled mb-4">
                                <li>{{ $category->deskripsi }}</li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- WhatsApp Button -->
    {{-- <a href="https://wa.me/6282318077074" class="btn btn-success position-fixed"
        style="bottom: 20px; right: 20px; border-radius: 50%;">
        <i class="fab fa-whatsapp fa-2x"></i>
    </a> --}}

</body>

</html>
