<?php

use Illuminate\Database\Migrations\Migration; // Perbaiki jika ada '->'
use Illuminate\Database\Schema\Blueprint;   // Perbaiki jika ada '->'
use Illuminate\Support\Facades\Schema;     // Perbaiki jika ada '->'

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_polis', function (Blueprint $table) {
            $table->id(); // Primary key auto-increment

            // Foreign key untuk pasien (User)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Foreign key untuk poli (opsional, bisa juga hanya via jadwal->poli)
            $table->foreignId('id_poli')->constrained('polis')->onDelete('cascade'); 
            
            // Foreign key untuk jadwal periksa
            $table->foreignId('id_jadwal')->constrained('jadwal_periksas')->onDelete('cascade');
            
            $table->text('keluhan'); // Kolom untuk keluhan pasien
            $table->integer('no_antrian'); // Nomor antrian
            $table->string('status')->default('menunggu'); // Status pendaftaran (menunggu, diterima, selesai, ditolak)
            $table->date('tanggal_pendaftaran'); // Tanggal pendaftaran

            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_polis');
    }
};
