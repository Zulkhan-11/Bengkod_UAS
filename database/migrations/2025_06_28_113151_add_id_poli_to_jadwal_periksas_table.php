<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema; // Perbaiki jika ada '->'

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jadwal_periksas', function (Blueprint $table) {
            // Tambahkan foreignId untuk id_poli setelah kolom 'id' atau 'dokter_id' jika lebih logis
            // Pastikan tabel 'polis' sudah ada saat migrasi ini dijalankan
            $table->foreignId('id_poli')
                  ->nullable() // Jadikan nullable jika Anda memiliki data lama tanpa id_poli
                  ->constrained('polis') // Merujuk ke tabel 'polis'
                  ->onDelete('set null') // Atau 'cascade' jika ingin menghapus jadwal jika poli dihapus
                  ->after('dokter_id'); // Atau setelah kolom lain yang relevan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_periksas', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropConstrainedForeignId('id_poli');
            // Hapus kolom
            // $table->dropColumn('id_poli'); // Ini opsional, dropConstrainedForeignId sudah menghapus kolomnya
        });
    }
};
