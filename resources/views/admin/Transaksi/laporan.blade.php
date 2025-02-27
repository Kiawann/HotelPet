@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Laporan Seluruh Pembayaran Dalam Pertahun</h2>
    
    @php
        $tahun_terkecil = \App\Models\Transaksi::min(\DB::raw('YEAR(tanggal_pembayaran)')) ?? date('Y');
        $tahun_terbesar = \App\Models\Transaksi::max(\DB::raw('YEAR(tanggal_pembayaran)')) ?? date('Y');
    @endphp
    
    <form method="GET" action="{{ route('laporan-transaksi') }}" class="mb-3">
        <label for="tahun">Pilih Tahun:</label>
        <select name="tahun" id="tahun" class="form-control w-25 d-inline-block" onchange="this.form.submit()">
            @for ($y = $tahun_terbesar; $y >= $tahun_terkecil; $y--)
                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
    <a href="{{ route('laporan-transaksi-pdf', ['tahun' => request('tahun', date('Y'))]) }}" class="btn btn-primary mb-3">Cetak PDF</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tahun = request('tahun', date('Y'));
                $totals = [];
                for ($i = 1; $i <= 12; $i++) {
                    $totals[$i] = \App\Models\Transaksi::whereMonth('tanggal_pembayaran', $i)
                        ->whereYear('tanggal_pembayaran', $tahun)
                        ->sum('Subtotal');
                }
            @endphp
            
            @foreach ($totals as $month => $subtotal)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                    <td>{{ number_format($subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
