<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periksa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * ======================================================================
     * == INI ADALAH PERBAIKANNYA: Menambahkan 'no_antrian' ke array ini   ==
     * == agar nomor antrean bisa disimpan ke database.                   ==
     * ======================================================================
     */
    protected $fillable = [
        'pasien_id',
        'dokter_id',
        'jadwal_id',
        'tgl_periksa',
        'keluhan',
        'status',
        'catatan',
        'diagnosa',
        'total_harga_obat',
        'no_antrian', // Ditambahkan di sini
    ];

    /**
     * Mendefinisikan relasi ke model User (sebagai pasien).
     */
    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }

    /**
     * Mendefinisikan relasi ke model User (sebagai dokter).
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    /**
     * Mendefinisikan relasi ke model JadwalPeriksa.
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'jadwal_id');
    }

    /**
     * Mendefinisikan relasi ke model DetailPeriksa (untuk resep obat).
     */
    public function detail()
    {
        return $this->hasMany(DetailPeriksa::class, 'periksa_id');
    }
}
