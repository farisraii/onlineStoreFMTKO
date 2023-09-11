<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemPesanan;
use App\Models\Pesanan;
use App\Models\Item;

class ItemPesananController extends Controller
{
    public function index()
    {
        $items = ItemPesanan::all();
        if ($items->isEmpty()) {
            return response()->json(['message' => 'Tidak ada data Item Pesanan yang tersedia'], 200);
        }

        return response()->json(['data' => $items], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanan,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $pesanan = Pesanan::find($request->input('pesanan_id'));
        $item = Item::find($request->input('item_id'));

        if (!$pesanan || !$item) {
            return response()->json(['message' => 'Pesanan atau item tidak ditemukan'], 404);
        }

        if ($item->quantity < $request->input('quantity')) {
            return response()->json(['message' => 'Kuantitas item tidak mencukupi'], 422);
        }

        $itemPesanan = new ItemPesanan([
            'pesanan_id' => $pesanan->id,
            'item_id' => $item->id,
            'quantity' => $request->input('quantity'),
        ]);
        $itemPesanan->save();

        $item->quantity -= $request->input('quantity');
        $item->save();

        return response()->json(['message' => 'Item Pesanan berhasil dibuat', 'data' => $itemPesanan], 201);
    }

    public function show($id)
    {
        $item = ItemPesanan::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item Pesanan tidak ditemukan'], 404);
        }

        return response()->json(['data' => $item], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanan,id',
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = ItemPesanan::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item Pesanan tidak ditemukan'], 404);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item->quantity = $request->input('quantity');
        $item->save();

        return response()->json(['message' => 'Item Pesanan berhasil diperbarui', 'data' => $item], 200);
    }

    public function destroy($id)
    {
        $item = ItemPesanan::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item Pesanan tidak ditemukan'], 404);
        }

        $item->item->quantity += $item->quantity;
        $item->item->save();

        $item->delete();

        return response()->json(['message' => 'Item Pesanan berhasil dihapus'], 200);
    }
}
