<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    /**
     * Menampilkan semua item.
     */
    public function index()
    {
        $items = Item::all();

        if ($items->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data Item yang tersedia'], 404);
        }

        return response()->json(['data' => $items], 200);
    }

    /**
     * Menampilkan detail item berdasarkan ID.
     */
    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        return response()->json(['data' => $item], 200);
    }

    /**
     * Menambahkan item baru ke dalam database.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_produk' => 'required|string|max:255',
                'harga' => 'required|integer',
                'quantity' => 'required|integer|min:0',
                'harga_asli' => 'required|integer',
                'is_flash_sale' => 'boolean',
            ]);

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
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menambahkan item'], 500);
        }
    }



    /**
     * Memperbarui item berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        try {
            $this->validateItem($request);

            $item->nama_produk = $request->input('nama_produk');
            $item->harga = $request->input('harga');
            $item->quantity = $request->input('quantity');
            $item->harga_asli = $request->input('harga_asli');
            $item->is_flash_sale = $request->input('is_flash_sale', false);
            $item->save();

            return response()->json(['message' => 'Item berhasil diperbarui'], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validasi gagal', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui item'], 500);
        }
    }

    /**
     * Menghapus item berdasarkan ID.
     */
    public function destroy($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item tidak ditemukan'], 404);
        }

        try {
            $item->delete();

            return response()->json(['message' => 'Item berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus item'], 500);
        }
    }

    /**
     * Validasi item berdasarkan aturan tertentu.
     */
    private function validateItem(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'quantity' => 'required|integer|min:0',
            'harga_asli' => 'required|integer',
            'is_flash_sale' => 'boolean',
        ]);

        if ($request->input('is_flash_sale') && $request->input('quantity') <= 0) {
            throw ValidationException::withMessages(['quantity' => 'Kuantitas harus lebih besar dari 0 selama flash sale']);
        }
    }
}
