<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Perbaiki jika ada '->'

class PendaftaranPoli extends Model
{
    use HasFactory;

    // Nama tabel di database yang akan digunakan oleh model ini
    protected $table = 'pendaftaran_polis';

    // Kolom-kolom yang dapat diisi secara massal (mass assignable)
    protected $fillable = [
        'user_id',             // ID pasien yang mendaftar
        'id_poli',             // ID poli yang didaftar
        'id_jadwal',           // ID jadwal periksa yang dipilih
        'keluhan',
        'no_antrian',          // Nomor antrian yang digenerate otomatis
        'status',              // Status pendaftaran (contoh: 'menunggu', 'diterima', 'selesai', 'ditolak')
        'tanggal_pendaftaran', // Tanggal pendaftaran (misal: untuk filtering berdasarkan tanggal)
        // Tambahkan kolom lain di sini jika Anda memilikinya di tabel database
    ];

    /**
     * Relasi: Satu pendaftaran dimiliki oleh satu User (pasien).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi: Satu pendaftaran terkait dengan satu JadwalPeriksa.
     */
    public function jadwal()
    {
        return $this->belongsTo(JadwalPeriksa::class, 'id_jadwal');
    }

    /**
     * Relasi: Satu pendaftaran terkait dengan satu Poli.
     * Meskipun poli bisa diakses via jadwal->poli, relasi langsung ini bisa membantu.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }
}
