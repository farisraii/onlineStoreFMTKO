<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembeli;

class PembeliController extends Controller
{
    public function index()
    {
        $pembeli = Pembeli::all();
        return response()->json(['data' => $pembeli], 200);
    }

    public function show($id)
    {
        $pembeli = Pembeli::find($id);

        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        return response()->json(['data' => $pembeli], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $pembeli = new Pembeli([
            'nama' => $request->input('nama'),
        ]);

        $pembeli->save();

        return response()->json(['message' => 'Pembeli berhasil ditambahkan', 'data' => $pembeli], 201);
    }

    public function update(Request $request, $id)
    {
        $pembeli = Pembeli::find($id);

        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $pembeli->nama = $request->input('nama');
        $pembeli->save();

        return response()->json(['message' => 'Pembeli berhasil diperbarui', 'data' => $pembeli], 200);
    }

    public function destroy($id)
    {
        $pembeli = Pembeli::find($id);

        if (!$pembeli) {
            return response()->json(['message' => 'Pembeli tidak ditemukan'], 404);
        }

        $pembeli->delete();

        return response()->json(['message' => 'Pembeli berhasil dihapus'], 200);
    }
}

