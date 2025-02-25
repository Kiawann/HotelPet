<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Validate OTP</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png"
        href="{{ isset($setting) ? asset('storage/' . $setting->app_logo) : asset('assets/images/logo_gym.png') }}">

    <style>
        body {
            background-color: #ffffff;
            color: #000000;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        .container {
            padding: 2rem;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
            padding: 2rem;
            background-color: #f8f9fa;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-back {
            background-color: #e9ecef;
            border: 1px solid #007bff;
            color: #000000;
        }

        .btn-back:hover {
            background-color: #007bff;
            color: #ffffff;
        }

        .form-floating>label {
            color: #6c757d;
        }

        .form-floating>.form-control:focus~label {
            color: #000000;
        }

        .form-control {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #007bff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 123, 255, 0.2);
        }

        .form-control:focus {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #007bff;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.2);
        }

        .invalid-feedback {
            color: #dc3545;
        }

        .card-header {
            text-align: center;
            padding: 0;
        }

        .logo {
            width: 150px;
            height: auto;
        }

        .card-header h1 {
            font-weight: 600;
            margin: 0;
        }

        @media (max-width: 767.98px) {
            .container {
                padding: 2rem 1.5rem;
            }

            .card-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 575.98px) {
            .container {
                padding: 2rem 1rem;
            }

            .card-header h1 {
                font-size: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <main class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-sm-10">
                <div class="card">
                    <div class="card-header">
                        <p class="mb-0">Please enter the OTP code sent to
                            <strong>
                                @if (session()->has('phone'))
                                    {{ substr(session('phone'), 0, 2) . '*********' . substr(session('phone'), -2) }}
                                @else
                                    {{ 'Your phone number' }}
                                @endif
                            </strong>
                        </p>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('validate-otp-forget') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <input type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                    minlength="6" maxlength="6"
                                    class="form-control text-center @error('otp') is-invalid @enderror" id="otp"
                                    name="otp" placeholder="XXXXXX" required>
                                @error('otp')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button class="w-100 btn btn-primary" type="submit">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (Session::has('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ Session::get('error') }}',
                });
            @endif
        });
    </script>
</body>

</html>
