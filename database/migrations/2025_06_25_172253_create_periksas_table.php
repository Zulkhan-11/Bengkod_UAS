    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('periksas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('pasien_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
                
                // Menghubungkan ke jadwal periksa yang dipilih pasien
                $table->foreignId('jadwal_id')->nullable()->constrained('jadwal_periksas')->onDelete('set null');

                $table->date('tgl_periksa');
                $table->text('keluhan');
                $table->text('catatan')->nullable();
                $table->string('diagnosa')->nullable();
                $table->string('status')->default('menunggu');
                $table->integer('total_harga_obat')->default(0);
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('periksas');
        }
    };
    