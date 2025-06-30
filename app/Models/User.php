<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'alamat', 'no_hp', 'nik', 'email', 'role',
        'password', 'poli_id', 'spesialis', 'no_rm',
    ];

    /**
     * Mendefinisikan relasi bahwa seorang User (Dokter) ditugaskan ke satu Poli.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'poli_id');
    }

    /**
     * Mendefinisikan relasi bahwa seorang User (Dokter) memiliki banyak Jadwal Periksa.
     */
    public function jadwals()
    {
        return $this->hasMany(JadwalPeriksa::class, 'dokter_id');
    }

    // ... sisa kode di dalam model Anda ...

    protected $hidden = [ 'password', 'remember_token', ];
    protected function casts(): array { return [ 'email_verified_at' => 'datetime', 'password' => 'hashed', ]; }
}
