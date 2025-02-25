<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DataPemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class DataPemilikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedRole = $request->get('filter_role', 'all');
        $search = $request->get('search');
    
        $pemilik = DataPemilik::when($selectedRole !== 'all', function ($query) use ($selectedRole) {
            $query->whereHas('user', function ($q) use ($selectedRole) {
                $q->where('role', $selectedRole);
            });
        })
        ->when($search, function ($query) use ($search) {
            $query->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
        })
        ->with('user')
        ->paginate(10);
    
        // Fetch users and roles
        $users = \App\Models\User::all();
        $roles = \App\Models\User::pluck('role')->unique();
    
        // Check if the request is AJAX
        if ($request->ajax()) {
            return response()->json(view('admin.data_pemilik.table', compact('pemilik', 'users', 'roles'))->render());
        }
    
        return view('admin.data_pemilik.index', compact('pemilik', 'users', 'roles', 'selectedRole', 'search'));
    }
    
    
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Periksa apakah pengguna sudah memiliki data pemilik
        if (Auth::user()->dataPemilik) {
            return redirect('/dashboard')->with('status', 'Data pemilik sudah ada.');
        }

        return view('auth.data-pemilik-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required|in:L,P',
            'nomor_telp' => 'required',
        ]);

        // Menyimpan data pemilik ke database
        DataPemilik::create([
            'user_id' => Auth::id(),  // Menyimpan ID user yang sedang login
            'nama' => $request->nama,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_telp' => $request->nomor_telp,
            'foto' => $request->foto ? $request->foto->store('photos') : null,  // opsional jika ada foto
        ]);

        return redirect('/dashboard');
    }
    
    public function changeRole($id, Request $request)
{
    $user = User::find($id);

    if (!$user) {
        return abort(404, 'User not found');
    }

    // Simpan role baru
    $newRole = $request->role;
    $user->role = $newRole;
    $user->save();

    // Pesan dinamis berdasarkan role yang dipilih
    return redirect()->back()->with('success', 'Role berhasil diubah menjadi ' . ucfirst($newRole));
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     $pemilik = DataPemilik::findOrFail($id);
        
    //     // Hapus foto jika ada
    //     if ($pemilik->foto) {
    //         Storage::delete($pemilik->foto);
    //     }
    
    //     $pemilik->delete();
    
    //     return redirect()->route('data_pemilik.index')->with('success', 'Data pemilik berhasil dihapus.');
    // }
    public function destroy(string $id)
    {
        // Ambil user berdasarkan ID
        $user = User::findOrFail($id);
    
        // Ambil data pemilik terkait
        $pemilik = $user->dataPemilik;
    
        // Hapus foto jika ada
        if ($pemilik && $pemilik->foto) {
            Storage::delete($pemilik->foto);
        }
    
        // Hapus user terkait
        $user->delete();
    
        // Hapus data pemilik jika ada
        if ($pemilik) {
            $pemilik->delete();
        }
    
        return redirect()->route('data_pemilik.index')->with('success', 'Data pemilik dan pengguna berhasil dihapus.');
    }
    
    

}
