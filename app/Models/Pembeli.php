<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembeli extends Model
{
    use HasFactory;

    protected $table = 'pembeli'; // Nama tabel dalam basis data

    protected $fillable = [
        'nama',
        'alamat',
        'nomor_telepon',
        'email',
        // Kolom-kolom lain yang perlu diisi
    ];

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
