<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RincianReservasiHotel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class DendaController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tahun terkecil dan terbesar dari data denda
        $tahun_terkecil = RincianReservasiHotel::min(\DB::raw('YEAR(created_at)')) ?? date('Y');
        $tahun_terbesar = RincianReservasiHotel::max(\DB::raw('YEAR(created_at)')) ?? date('Y');

        $tahun = $request->input('tahun', date('Y'));

        // Menghitung total denda per bulan
        $totals = [];
        for ($i = 1; $i <= 12; $i++) {
            $totals[$i] = RincianReservasiHotel::whereMonth('created_at', $i)
                ->whereYear('created_at', $tahun)
                ->sum('Denda');
        }

        return view('admin.denda.laporan', compact('totals', 'tahun_terkecil', 'tahun_terbesar'));
    }

    /**
     * Cetak laporan denda dalam format PDF.
     */

     public function cetakPdf(Request $request)
     {
         $tahun = $request->input('tahun', date('Y'));
     
         $totals = [];
         for ($i = 1; $i <= 12; $i++) {
             $totals[$i] = RincianReservasiHotel::whereMonth('created_at', $i)
                 ->whereYear('created_at', $tahun)
                 ->sum('Denda');
         }
     
         // CSS untuk memperbaiki tampilan
         $css = "
             <style>
                 body { font-family: Arial, sans-serif; text-align: center; }
                 h2 { margin-bottom: 20px; }
                 table { width: 100%; border-collapse: collapse; margin: 0 auto; }
                 th, td { border: 1px solid black; padding: 8px; text-align: center; }
                 th { background-color: #f2f2f2; font-weight: bold; }
                 td { font-size: 14px; }
             </style>
         ";
     
         // Struktur HTML dengan CSS
         $html = $css . "<h2>Laporan Denda Tahun $tahun</h2>";
         $html .= '<table>';
         $html .= '<tr><th>Bulan</th><th>Total Denda</th></tr>';
         
         foreach ($totals as $bulan => $total) {
             $html .= '<tr>';
             $html .= '<td>' . date('F', mktime(0, 0, 0, $bulan, 1)) . '</td>';
             $html .= '<td>Rp ' . number_format($total, 2, ',', '.') . '</td>';
             $html .= '</tr>';
         }
     
         $html .= '</table>';
     
         // Load PDF dari HTML
         $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');
     
         return $pdf->download("laporan-denda-$tahun.pdf");
     }
}
