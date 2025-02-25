<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriHewan;


class KategoriHewanController extends Controller
{
    public function index()
    {
        // Mengambil data kategori hewan dan mengurutkan berdasarkan nama_kategori dari A-Z
        $kategoriHewan = KategoriHewan::orderBy('nama_kategori', 'asc')->get();
        
        return view('admin.kategori_hewan.index', compact('kategoriHewan'));
    }

    public function create()
    {
        return view('admin.kategori_hewan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
        ]);

        KategoriHewan::create($request->all());
        return redirect()->route('kategori_hewan.index')->with('success', 'Kategori Hewan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriHewan = KategoriHewan::findOrFail($id);
        return view('admin.kategori_hewan.edit', compact('kategoriHewan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
        ]);

        $kategoriHewan = KategoriHewan::findOrFail($id);
        $kategoriHewan->update($request->all());
        return redirect()->route('kategori_hewan.index')->with('success', 'Kategori Hewan berhasil diubah');
    }

    public function destroy($id)
    {
        $kategoriHewan = KategoriHewan::findOrFail($id);
        $kategoriHewan->delete();
        return redirect()->route('kategori_hewan.index')->with('success', 'Kategori Hewan berhasil dihapus');
    }
}

