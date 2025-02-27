{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Email</title>
</head>
<body>
    <h1>Hi, {{ $name }}!</h1>
    <p>Here is your verification code.</p>
    <p>Thanks for signing up with us.</p>
</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Email</title>
</head>
<body>
    <h1>Hi, {{ $name }}!</h1>
    <p>Here is your verification code.</p>

    <p>Click the link below to verify your email:</p>

    <a href="{{ route('email.verify.change', ['token' => $token]) }}">Verify Email</a>

    <p>Thanks for signing up with us.</p>
</body>
</html>
