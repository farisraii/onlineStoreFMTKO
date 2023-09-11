<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    use HasFactory;

    protected $table = 'item_pesanan';
    protected $fillable = ['pesanan_id', 'item_id', 'quantity', 'harga_asli', 'diskon']; // Tambahkan 'harga_asli' dan 'diskon' ke fillable

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
