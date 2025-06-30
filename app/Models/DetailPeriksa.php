<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeriksa extends Model
{
    use HasFactory;
    protected $fillable = [
        'periksa_id',
        'obat_id',
        'jumlah',
    ];

    /**
     * Untuk Mendefinisikan relasi ke model Obat.
     */
    public function obat()
    {
        // Pastikan foreign key di sini juga benar
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    /**
     * Untuk Mendefinisikan relasi ke model Periksa.
     */
    public function periksa()
    {
        return $this->belongsTo(Periksa::class, 'periksa_id');
    }
}
