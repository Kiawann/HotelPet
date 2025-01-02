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
    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>
        @if ($errors->has('email'))
            <span>{{ $errors->first('email') }}</span>
        @endif
        <button type="submit">Kirim Link Reset</button>
    </form>
</div>

<div class="text-center mt-4">
    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Kembali</a>
</div>
