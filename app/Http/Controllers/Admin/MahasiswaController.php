<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    public function index() {
        $mahasiswas = Mahasiswa::latest()->get();
        return view('admin.mahasiswa.index', compact('mahasiswas'));
    }

    public function store(Request $request) {
        $request->validate([
            'nama'        => 'required|string|max:100',
            'nim'         => 'required|string|unique:mahasiswas,nim',
            'jurusan'     => 'required|string',
            'no_telepon'  => 'required|string',
        ]);

        Mahasiswa::create($request->all());

        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil ditambahkan!']);
    }

    public function update(Request $request, $id) {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $request->validate([
            'nama'        => 'required|string|max:100',
            'nim'         => 'required|string|unique:mahasiswas,nim,'.$id,
            'jurusan'     => 'required|string',
            'no_telepon'  => 'required|string',
        ]);

        $mahasiswa->update($request->all());
        return response()->json(['success' => true, 'message' => 'Data berhasil diupdate!']);
    }

    public function destroy($id) {
        Mahasiswa::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Mahasiswa berhasil dihapus!']);
    }
}