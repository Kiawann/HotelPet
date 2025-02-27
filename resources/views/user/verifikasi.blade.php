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
    
    <main class="container" style="margin-top: 100px;">
        <div class="row justify-content-center">
            <div class="col-10">
                <div class="card">
                    <div class="card-header">
                        <p class="mb-0 text-center">Please enter the OTP code sent to
                            <strong>{{ substr(session('phone'), 0, 2) . '*********' . substr(session('phone'), -2) }}</strong>
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('validate-otp') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    minlength="6" maxlength="6"
                                    class="form-control text-center @error('otp') is-invalid @enderror" id="otp"
                                    name="otp" placeholder="XXXXXX" required>
                                @error('otp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- WhatsApp Button -->
    {{-- <a href="https://wa.me/6282318077074" class="btn btn-success position-fixed"
        style="bottom: 20px; right: 20px; border-radius: 50%;">
        <i class="fab fa-whatsapp fa-2x"></i>
    </a> --}}

</body>

</html>
