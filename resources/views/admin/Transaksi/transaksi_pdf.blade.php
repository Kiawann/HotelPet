<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran Tahun {{ $tahun }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Pembayaran Tahun {{ $tahun }}</h2>
    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($totals as $month => $subtotal)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                    <td>{{ number_format($subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
