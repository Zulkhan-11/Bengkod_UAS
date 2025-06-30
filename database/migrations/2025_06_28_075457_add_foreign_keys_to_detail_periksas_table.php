    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         */
        public function up(): void
        {
            Schema::table('detail_periksas', function (Blueprint $table) {
                // Tambahkan kolom periksa_id jika belum ada
                if (!Schema::hasColumn('detail_periksas', 'periksa_id')) {
                    $table->foreignId('periksa_id')->constrained('periksas')->onDelete('cascade');
                }
                
                // Tambahkan kolom obat_id jika belum ada
                if (!Schema::hasColumn('detail_periksas', 'obat_id')) {
                    $table->foreignId('obat_id')->constrained('obat')->onDelete('cascade');
                }

                // Tambahkan kolom jumlah jika belum ada
                if (!Schema::hasColumn('detail_periksas', 'jumlah')) {
                    $table->integer('jumlah');
                }
            });
        }

        /**
         * Reverse the migrations.
         */
        public function down(): void
        {
            Schema::table('detail_periksas', function (Blueprint $table) {
                // Kode untuk membatalkan perubahan jika perlu
                if (Schema::hasColumn('detail_periksas', 'periksa_id')) {
                    $table->dropForeign(['periksa_id']);
                    $table->dropColumn('periksa_id');
                }
                if (Schema::hasColumn('detail_periksas', 'obat_id')) {
                    $table->dropForeign(['obat_id']);
                    $table->dropColumn('obat_id');
                }
                if (Schema::hasColumn('detail_periksas', 'jumlah')) {
                    $table->dropColumn('jumlah');
                }
            });
        }
    };
    