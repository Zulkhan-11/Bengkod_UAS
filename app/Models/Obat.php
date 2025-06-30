<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * =========================================================================
     * == BARIS INI ADALAH KUNCI UTAMA UNTUK MENYELESAIKAN ERROR ANDA         ==
     * == Ini memberitahu Laravel bahwa Model 'Obat' menggunakan tabel 'obat' ==
     * =========================================================================
     * @var string
     */
    protected $table = 'obat';

    /**
     * The attributes that are mass assignable.
     * Kolom-kolom yang boleh diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_obat',
        'kemasan', // atau 'keterangan' sesuai struktur tabel Anda
        'harga',
    ];
}
