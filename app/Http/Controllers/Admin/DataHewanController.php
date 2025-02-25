<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DataHewan;
use App\Models\DataPemilik;
use App\Models\KategoriHewan;

class DataHewanController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = DataHewan::with(['kategoriHewan', 'pemilik']);
        
    //     // Filter berdasarkan kategori hewan
    //     if ($request->filled('kategori')) {
    //         $query->whereHas('kategoriHewan', function ($q) use ($request) {
    //             $q->where('id', $request->kategori);
    //         });
    //     }
        
    //     // Pencarian berdasarkan nama hewan atau pemilik
    //     if ($request->filled('search')) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('nama_hewan', 'like', '%' . $request->search . '%')
    //               ->orWhereHas('pemilik', function ($q) use ($request) {
    //                   $q->where('nama', 'like', '%' . $request->search . '%');
    //               });
    //         });
    //     }
        
    //     $dataHewan = $query->orderBy('nama_hewan')->get();
    //     $kategoriHewan = KategoriHewan::all();
        
    //     return view('admin.data_hewan.index', compact('dataHewan', 'kategoriHewan'));
    // }
    public function index(Request $request)
    {
        $query = DataHewan::with(['kategoriHewan', 'pemilik']);
        
        // Filter berdasarkan kategori hewan
        if ($request->filled('kategori')) {
            $query->whereHas('kategoriHewan', function ($q) use ($request) {
                $q->where('id', $request->kategori);
            });
        }
        
        // Pencarian berdasarkan nama hewan atau pemilik
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama_hewan', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pemilik', function ($q) use ($request) {
                      $q->where('nama', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $dataHewan = $query->orderBy('nama_hewan')->paginate(10);
        $kategoriHewan = KategoriHewan::all();
        
        return view('admin.data_hewan.index', compact('dataHewan', 'kategoriHewan'));
    }

    public function create()
    {
        $kategoriHewan = KategoriHewan::all();
        $pemilik = DataPemilik::all();
        return view('admin.data_hewan.create', compact('kategoriHewan', 'pemilik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_data_pemilik' => 'nullable|exists:data_pemilik,id_data_pemilik',
            'id_kategori_hewan' => 'nullable|exists:kategori_hewan,id_kategori_hewan',
            'nama_hewan' => 'required|string|max:255',
            'umur' => 'required|integer',
            'berat_badan' => 'required|integer',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'required|string|max:255',
            'ras_hewan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ], [
            'id_data_pemilik.required' => 'Pemilik wajib diisi',
            'id_kategori_hewan.required' => 'Kategori Hewan wajib diisi',
            'nama_hewan.required' => 'Nama Hewan wajib diisi',
            'umur.required' => 'Umur wajib diisi',
            'berat_badan.required' => 'Berat Badan wajib diisi',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
            'warna.required' => 'Warna wajib diisi',
            'ras_hewan.required' => 'Ras Hewan wajib diisi',
            'foto.required' => 'Foto wajib diisi',
        ]);

        $dataHewan = new DataHewan($request->all());

        if ($request->hasFile('foto')) {
            $dataHewan->foto = $request->file('foto')->store('hewan_foto');
        }

        $dataHewan->save();

        return redirect()->route('data_hewan.index')->with('success', 'Data Hewan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $dataHewan = DataHewan::findOrFail($id);
        $kategoriHewan = KategoriHewan::all();
        $pemilik = DataPemilik::all();
        return view('admin.data_hewan.edit', compact('dataHewan', 'kategoriHewan', 'pemilik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_data_pemilik' => 'nullable|exists:data_pemilik,id_data_pemilik',
            'id_kategori_hewan' => 'nullable|exists:kategori_hewan,id_kategori_hewan',
            'nama_hewan' => 'required|string|max:255',
            'umur' => 'required|integer',
            'berat_badan' => 'required|integer',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'warna' => 'required|string|max:255',
            'ras_hewan' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
        ], [
            'id_data_pemilik.required' => 'Pemilik wajib diisi',
            'id_kategori_hewan.required' => 'Kategori Hewan wajib diisi',
            'nama_hewan.required' => 'Nama Hewan wajib diisi',
            'umur.required' => 'Umur wajib diisi',
            'berat_badan.required' => 'Berat Badan wajib diisi',
            'jenis_kelamin.required' => 'Jenis Kelamin wajib diisi',
            'warna.required' => 'Warna wajib diisi',
            'ras_hewan.required' => 'Ras Hewan wajib diisi',
            'foto.required' => 'Foto wajib diisi',
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

        return redirect()->route('data_hewan.index')->with('success', 'Data Hewan berhasil diubah');
    }

    public function destroy($id)
    {
        $dataHewan = DataHewan::findOrFail($id);

        if ($dataHewan->foto) {
            Storage::delete($dataHewan->foto);
        }

        $dataHewan->delete();

        return redirect()->route('data_hewan.index')->with('success', 'Data Hewan berhasil dihapus');
    }
}
