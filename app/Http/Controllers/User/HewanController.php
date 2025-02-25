<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataHewan;
use App\Models\KategoriHewan;
use App\Models\DataPemilik;
use App\Models\RincianReservasiHotel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class HewanController extends Controller
{
    public function index()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login'); // Redirect to login if not authenticated
        }

        // Mendapatkan hewan dengan kategori, hanya yang dimiliki oleh pemilik yang login
        $hewans = DataHewan::with('kategoriHewan')
            ->whereHas('pemilik', function ($query) {
                $query->where('user_id', Auth::id()); // Menghubungkan dengan pemilik yang login
            })
            ->get();

        $kategoriHewan = KategoriHewan::all();

        return view('user.hewan.profil-hewan', compact('hewans', 'kategoriHewan'));
    }

    public function create()
    {
        $kategoriHewan = KategoriHewan::all();
        $hewans = DataHewan::all();
        return view('user.hewan.tambah-hewan', compact('kategoriHewan', 'hewans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_hewan' => 'required|string|max:255',
            'umur' => 'required|integer',
            'berat_badan' => 'required|numeric|min:0.1',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'required|string|max:255',
            'ras_hewan' => 'required|string|max:255',
            'kategori_hewan_id' => 'required|exists:kategori_hewan,id',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ], [
            'nama_hewan.required' => 'Nama Hewan wajib diisi',
            'umur.required' => 'Umur wajib diisi',
            'berat_badan.required' => 'Berat Badan wajib diisi',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
            'warna.required' => 'Warna wajib diisi',
            'ras_hewan.required' => 'Ras Hewan wajib diisi',
        ]);

        $dataHewan = new DataHewan($request->all());

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('hewan_foto', 'public');
            $dataHewan->foto = $fotoPath;
        }

        if (Auth::check()) {
            $pemilik = Auth::user()->dataPemilik;
            $dataHewan->data_pemilik_id = $pemilik->id;
        }

        $dataHewan->save();

        return redirect()->route('hewan.index')->with('success', 'Data Hewan berhasil ditambahkan');
    }


    public function edit($id)
    {
        $dataHewan = DataHewan::findOrFail($id);
        $kategoriHewan = KategoriHewan::all();
        return view('user.hewan.edit', compact('dataHewan', 'kategoriHewan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_hewan' => 'required|string|max:255',
            'umur' => 'required|integer',
            'berat_badan' => 'required|integer',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'required|string|max:255',
            'ras_hewan' => 'required|string|max:255',
            'id_kategori_hewan' => 'nullable|exists:kategori_hewan,id_kategori_hewan',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ]);

        $dataHewan = DataHewan::findOrFail($id);

        $dataHewan->update($request->all());

        if ($request->hasFile('foto')) {
            if ($dataHewan->foto) {
                Storage::delete($dataHewan->foto);
            }
            $dataHewan->foto = $request->file('foto')->store('hewan_foto');
        }

        $dataHewan->save();

        return redirect()->route('hewan.index')->with('success', 'Data Hewan berhasil diubah');
    }

    public function show($id)
    {
        $hewan = DataHewan::findOrFail($id);

        return view('hewan.show', compact('hewan'));
    }

    public function destroy($id)
    {
        $dataHewan = DataHewan::findOrFail($id);
        
        // Check if the pet has any reservations
        $hasReservation = RincianReservasiHotel::where('data_hewan_id', $id)->exists();
    
        if ($hasReservation) {
            return redirect()
                ->route('hewan.index')
                ->with('error', 'Data hewan tidak dapat dihapus karena memiliki riwayat reservasi hotel. Silakan batalkan semua reservasi terlebih dahulu.');
        }
    
        try {
            // Delete photo if exists
            if ($dataHewan->foto) {
                Storage::disk('public')->delete($dataHewan->foto);
            }
    
            $dataHewan->delete();
            return redirect()
                ->route('hewan.index')
                ->with('success', 'Data hewan berhasil dihapus');
                
        } catch (\Exception $e) {
            return redirect()
                ->route('hewan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data hewan.');
        }
    }
}
