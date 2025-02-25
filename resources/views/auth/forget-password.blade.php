<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login</title>
</head>
<body>

<div class="container" style="margin-top: 50px">
    <div class="card-body">
        @if($message = session('status'))
        <div class="alert alert-success my-2 text-success" role="alert">{{ $message }}</div>
        @endif
        <p class="text-center">Enter your phone number to receive OTP code</p>
        <form action="{{ route('send-otp-forgot') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input name="phone" type="text" oninput="this.value = this.value.replace(/[^0-9]/g, '');" minlength="10" maxlength="13" class="form-control text-center @error('phone') is-invalid @enderror" id="floatingInput" placeholder="08XXXXXXXXXX" value="{{ auth()->user()->phone ?? old('phone') }}" {{ isset(auth()->user()->phone) ? 'readonly' : 'required' }}>
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button class="w-100 btn btn-primary" type="submit">Send OTP</button>
        </form>
        {{-- <a href="{{ isset(auth()->user()->phone) ? url()->previous() : route('login') }}" class="btn btn-back w-100 mt-3">Back</a> --}}
    </div>

<div class="text-center mt-4">
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Kembali</a>
</div>

