<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::all();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data Item yang tersedia'], 404);
        }

        return response()->json(['data' => $items], 200);
    }

    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json(['data' => $item], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|integer',
            'quantity' => 'required|integer|min:0',
            'harga_asli' => 'required|integer',
            'is_flash_sale' => 'boolean',
        ]);

        // Validasi tambahan untuk flash sale
        if ($request->input('is_flash_sale') && $request->input('quantity') <= 0) {
            return response()->json(['message' => 'Kuantitas harus lebih besar dari 0 selama flash sale'], 422);
        }

        // Gunakan transaksi database
        return DB::transaction(function () use ($request) {
            $item = new Item([
                'nama_produk' => $request->input('nama_produk'),
                'harga' => $request->input('harga'),
                'quantity' => $request->input('quantity'),
                'harga_asli' => $request->input('harga_asli'),
                'is_flash_sale' => $request->input('is_flash_sale', false),
            ]);

            $item->save();

            return response()->json(['message' => 'Item berhasil ditambahkan'], 201);
        });
    }

    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'harga_asli' => 'required|integer',
            'is_flash_sale' => 'boolean',
        ]);

        $item->nama_produk = $request->input('nama_produk');
        $item->harga = $request->input('harga');
        $item->quantity = $request->input('quantity');
        $item->harga_asli = $request->input('harga_asli');
        $item->is_flash_sale = $request->input('is_flash_sale', false);
        $item->save();

        return response()->json(['message' => 'Item berhasil diperbarui'], 200);
    }

    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        $item->delete();

        return response()->json(['message' => 'Item berhasil dihapus'], 200);
    }
}
