<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function index()
    {
        $pesanan = Pesanan::all();

        if ($pesanan->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data pesanan'], 200);
        }

        return response()->json($pesanan, 200);
    }

    public function show(Request $request)
    {
        $id = $request->query('id');
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        return response()->json(['message' => 'Data pesanan berhasil diambil', 'data' => $pesanan], 200);
    }

    public function store(Request $request)
    {
        // Validasi input dan pembeli
        $request->validate([
            'pembeli_id' => 'required|exists:pembeli,id',
            'items' => 'required|array',
        ]);

        $itemsData = $request->input('items');
        $totalHarga = 0;

        // Validasi bahwa pesanan memiliki setidaknya satu item pesanan
        if (count($itemsData) === 0) {
            return response()->json(['message' => 'Pesanan harus memiliki setidaknya satu item pesanan'], 422);
        }

        // Gunakan transaksi database
        return DB::transaction(function () use ($request, $itemsData, &$totalHarga) {
            // Buat pesanan
            $pesanan = new Pesanan(['pembeli_id' => $request->input('pembeli_id')]);
            $pesanan->save();

            foreach ($itemsData as $itemData) {
                $item = Item::find($itemData['item_id']);
                if (!$item) {
                    throw new \Exception('Item tidak ditemukan');
                }

                // Validasi kuantitas item yang mencukupi
                if ($item->quantity < $itemData['quantity']) {
                    throw new \Exception('Kuantitas item tidak mencukupi');
                }

                $totalHarga += $item->harga * $itemData['quantity'];

                // Kurangi kuantitas item
                $item->quantity -= $itemData['quantity'];
                $item->save();

                // Simpan item pesanan
                $itemPesanan = new ItemPesanan([
                    'pesanan_id' => $pesanan->id,
                    'item_id' => $itemData['item_id'],
                    'quantity' => $itemData['quantity'],
                ]);
                $itemPesanan->save();
            }

            // Simpan total harga pesanan
            $pesanan->total_harga = $totalHarga;
            $pesanan->save();

            // Validasi tambahan untuk flash sale
            if ($pesanan->items->contains('is_flash_sale', true) && $pesanan->total_harga <= 0) {
                throw new \Exception('Total harga harus lebih besar dari 0 selama flash sale');
            }
        });
    }

    public function update(Request $request, $id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        $request->validate([
            'pembeli_id' => 'required|exists:pembeli,id',
        ]);

        $pesanan->pembeli_id = $request->input('pembeli_id');
        $pesanan->save();

        return response()->json(['message' => 'Pesanan berhasil diperbarui', 'data' => $pesanan], 200);
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::find($id);

        if (!$pesanan) {
            return response()->json(['message' => 'Pesanan tidak ditemukan'], 404);
        }

        ItemPesanan::where('pesanan_id', $id)->delete();

        $pesanan->delete();

        return response()->json(['message' => 'Pesanan berhasil dihapus'], 200);
    }
}
