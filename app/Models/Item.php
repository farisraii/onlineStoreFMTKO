<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items';
    protected $fillable = ['nama_produk', 'harga', 'quantity', 'harga_asli', 'is_flash_sale']; // Tambahkan 'is_flash_sale' ke fillable

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}
