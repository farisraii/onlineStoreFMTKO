<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $fillable = ['pembeli_id', 'total_harga']; // Tambahkan 'total_harga' ke fillable

    public function pembeli()
    {
        return $this->belongsTo(Pembeli::class, 'pembeli_id');
    }

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class, 'pesanan_id');
    }
}
