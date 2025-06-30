<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPeriksa extends Model
{
    use HasFactory;

    // Table name in the database
    protected $table = 'jadwal_periksas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_poli', // Ensure this is in fillable
        'dokter_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota', // If you have a 'kuota' column
        'status', // If you have a 'status' column for active/inactive schedules
    ];

    /**
     * Defines the relationship to the Poli model.
     * A schedule belongs to one Poly.
     */
    public function poli()
    {
        return $this->belongsTo(Poli::class, 'id_poli');
    }

    /**
     * Defines the relationship to the User model (Doctor).
     * A schedule is created by one Doctor.
     */
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }
    
    /**
     * Defines the relationship: One JadwalPeriksa has many PendaftaranPoli.
     * Used to retrieve all registrations for this specific schedule.
     */
    public function pendaftaranPoli()
    {
        return $this->hasMany(PendaftaranPoli::class, 'id_jadwal'); // 'id_jadwal' is the foreign key in 'pendaftaran_polis' table
    }
}
