@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Laporan Denda Per Tahun</h2>
    
    <form method="GET" action="{{ route('laporan-denda') }}" class="mb-3">
        <label for="tahun">Pilih Tahun:</label>
        <select name="tahun" id="tahun" class="form-control w-25 d-inline-block" onchange="this.form.submit()">
            @for ($y = $tahun_terbesar; $y >= $tahun_terkecil; $y--)
                <option value="{{ $y }}" {{ request('tahun', date('Y')) == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>

    <a href="{{ route('laporan-denda-pdf', ['tahun' => request('tahun', date('Y'))]) }}" class="btn btn-primary mb-3">Cetak PDF</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($totals as $month => $total_denda)
                <tr>
                    <td>{{ date('F', mktime(0, 0, 0, $month, 1)) }}</td>
                    <td>{{ number_format($total_denda, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
