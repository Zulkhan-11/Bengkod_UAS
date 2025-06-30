<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory; // Ditambahkan: Trait HasFactory untuk factory model

    // Nama tabel di database, jika berbeda dari konvensi (plural dari nama model, yaitu 'polis')
    protected $table = 'polis'; 

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = ['nama_poli', 'deskripsi']; // 'deskripsi' dipertahankan sesuai kode Anda

    /**
     * Mendefinisikan relasi: Satu Poli memiliki banyak JadwalPeriksa.
     * Digunakan untuk mengambil jadwal-jadwal periksa yang terkait dengan poli ini.
     */
    public function jadwalPeriksas()
    {
        return $this->hasMany(JadwalPeriksa::class, 'id_poli'); // 'id_poli' adalah foreign key di tabel jadwal_periksas
    }

    /**
     * Mendefinisikan relasi: Satu Poli memiliki banyak Dokter (User).
     * Digunakan untuk mengambil daftar dokter yang bertugas di poli ini.
     * Asumsi: Ada kolom 'poli_id' di tabel 'users' untuk menandakan poli tempat dokter bertugas.
     */
    public function dokters()
    {
        return $this->hasMany(User::class, 'poli_id'); // 'poli_id' adalah foreign key di tabel users
    }
}
