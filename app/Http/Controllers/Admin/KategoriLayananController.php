<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KategoriLayanan;
use Illuminate\Support\Facades\Storage;

class KategoriLayananController extends Controller
{
    public function index()
    {
        $kategoriLayanan = KategoriLayanan::all();
        return view('admin.kategori_layanan.index', compact('kategoriLayanan'));
    }

    public function create()
    {
        return view('admin.kategori_layanan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'harga' => 'required|integer',
        ], [
            'nama_layanan.required' => 'Nama Layanan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'foto.required' => 'Foto wajib diisi',
            'harga.required' => 'Harga wajib diisi',
        ]);

        $data = $request->all();

        // Simpan foto jika ada
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('layanan');  // Menyimpan foto di storage
        }

        KategoriLayanan::create($data);
        return redirect()->route('kategori_layanan.index')->with('success', 'Kategori Layanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kategoriLayanan = KategoriLayanan::findOrFail($id);
        return view('admin.kategori_layanan.edit', compact('kategoriLayanan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'harga' => 'required|integer',
        ], [
            'nama_layanan.required' => 'Nama Layanan wajib diisi',
            'deskripsi.required' => 'Deskripsi wajib diisi',
            'foto.required' => 'Foto wajib diisi',
            'harga.required' => 'Harga wajib diisi',
        ]);

        $kategoriLayanan = KategoriLayanan::findOrFail($id);
        $data = $request->all();

        // Jika ada foto baru, hapus foto lama dan simpan foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari storage jika ada
            if ($kategoriLayanan->foto) {
                Storage::delete($kategoriLayanan->foto);
            }
            $data['foto'] = $request->file('foto')->store('layanan');  // Menyimpan foto baru
        }

        $kategoriLayanan->update($data);
        return redirect()->route('kategori_layanan.index')->with('success', 'Kategori Layanan berhasil diubah');
    }

    public function destroy($id)
    {
        $kategoriLayanan = KategoriLayanan::findOrFail($id);

        // Hapus foto terkait dari storage jika ada
        if ($kategoriLayanan->foto) {
            Storage::delete($kategoriLayanan->foto);
        }

        $kategoriLayanan->delete();
        return redirect()->route('kategori_layanan.index')->with('success', 'Kategori Layanan berhasil dihapus');
    }
}