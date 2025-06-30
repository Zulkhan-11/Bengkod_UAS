<?php

namespace App\Http\Controllers\Pasien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Periksa;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Untuk Menampilkan daftar semua riwayat pemeriksaan pasien.
     */
    public function index()
    {
        // Untuk Mengambil data dari tabel 'periksa' untuk pasien yang login
        $riwayatPemeriksaan = Periksa::where('pasien_id', Auth::id())
                                      ->with('dokter', 'jadwal.poli')
                                      ->latest()
                                      ->get();
        
        // Untuk Mengirim data ke view 'pasien.riwayat.index'
        return view('pasien.riwayat.index', compact('riwayatPemeriksaan'));
    }

    /**
     * Untuk Menampilkan detail satu riwayat pemeriksaan.
     */
    public function show(Periksa $periksa)
    {
        // Untuk Memastikan riwayat yang akan dilihat adalah milik pasien yang sedang login.
        if ($periksa->pasien_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK. ANDA TIDAK BERHAK MELIHAT RIWAYAT INI.');
        }

        // Jika akses diizinkan, muat relasi yang diperlukan untuk halaman detail
        $periksa->load('pasien', 'dokter', 'jadwal.poli', 'detail.obat');
        
        // Untuk Mengirim data ke view 'pasien.riwayat.show'
        return view('pasien.riwayat.show', compact('periksa'));
    }
}
