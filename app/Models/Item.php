<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'items'; // Nama tabel dalam basis data
    protected $fillable = ['nama_produk', 'harga', 'quantity', 'harga_asli', 'is_flash_sale'];

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class);
    }
}
