<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Periksa;
use App\Models\Resep;
use App\Models\JadwalOperasi;
use App\Models\Poli;
use App\Models\JadwalPeriksa;
use App\Models\User;
use Carbon\Carbon;

class PasienController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Untuk Menampilkan halaman daftar poli dan riwayat pendaftaran pasien.
     */
    public function daftarPoli()
    {
        $pasienId = Auth::id(); 
        $daftar_poli = Poli::all();

        // Untuk Mengubah relasi yang diambil agar data Poli dari Dokter ikut termuat
        $riwayat_daftar = Periksa::with(['dokter.poli', 'jadwal'])
                                    ->where('pasien_id', $pasienId)
                                    ->latest()
                                    ->get();

        return view('pasien.poli_daftar', compact('daftar_poli', 'riwayat_daftar'));
    }

    /**
     * Untuk Menyimpan pendaftaran poli yang baru dan membuat nomor antrean otomatis.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_jadwal' => 'required|exists:jadwal_periksas,id',
            'keluhan' => 'required|string|max:500',
        ]);

        $jadwal = JadwalPeriksa::findOrFail($request->id_jadwal);

        // Logika untuk membuat Nomor Antrean
        $tanggalHariIni = Carbon::today();
        $jumlahAntreanHariIni = Periksa::where('jadwal_id', $request->id_jadwal)
                                       ->whereDate('tgl_periksa', $tanggalHariIni)
                                       ->count();
        $nomorAntreanBaru = $jumlahAntreanHariIni + 1;

        Periksa::create([
            'pasien_id' => Auth::id(),
            'dokter_id' => $jadwal->dokter_id,
            'jadwal_id' => $request->id_jadwal,
            'keluhan' => $request->keluhan,
            'status' => 'menunggu',
            'tgl_periksa' => Carbon::now(),
            'no_antrian' => $nomorAntreanBaru,
        ]);

        return redirect()->route('pasien.poli.daftar')
                         ->with('success', 'Pendaftaran poli berhasil! Nomor antrian Anda: ' . $nomorAntreanBaru);
    }

    /**
     * Untuk Mengambil jadwal periksa berdasarkan ID poli untuk AJAX.
     */
    public function getJadwalByPoli($poliID)
    {
        $jadwal = JadwalPeriksa::with('dokter')
                    ->whereHas('dokter', function ($query) use ($poliID) {
                        $query->where('poli_id', $poliID);
                    })
                    ->get();
        return response()->json($jadwal);
    }
    
    /**
     * Untuk Menampilkan dashboard utama untuk pasien.
     */
    public function dashboard()
    {
        $pasienId = Auth::id();
        $jumlahRiwayat = Periksa::where('pasien_id', $pasienId)->count();
        $jumlahResep = 0; 
        $jumlahJadwal = JadwalOperasi::where('pasien_id', $pasienId)
                                        ->where('waktu_operasi', '>=', now())
                                        ->count();
        $jumlahPesan = 1; 
        return view('pasien.dashboard', compact('jumlahRiwayat', 'jumlahResep', 'jumlahJadwal', 'jumlahPesan'));
    }

    /**
     * Untuk Menampilkan halaman riwayat pemeriksaan pasien.
     */
    public function riwayatPemeriksaan()
    {
        $riwayatPemeriksaan = Periksa::where('pasien_id', Auth::id())->with('dokter')->latest()->get();
        return view('pasien.periksa.index', compact('riwayatPemeriksaan'));
    }

    /**
     * Untuk Menampilkan halaman resep obat pasien.
     */
    public function resepObat()
    {
        $resepObat = []; 
        return view('pasien.obat.index', compact('resepObat'));
    }

    /**
     * Untuk Menampilkan halaman profil medis pasien.
     */
    public function profilMedis()
    {
        $pasien = Auth::user();
        $riwayatPemeriksaan = Periksa::where('pasien_id', $pasien->id)
                                        ->with('dokter')
                                        ->latest('tgl_periksa')
                                        ->take(5)->get();
        $jadwalOperasi = JadwalOperasi::where('pasien_id', $pasien->id)
                                        ->where('waktu_operasi', '>=', now())
                                        ->orderBy('waktu_operasi')->get();
        return view('pasien.profil.medis', compact('pasien', 'riwayatPemeriksaan', 'jadwalOperasi'));
    }

    /**
     * Untuk Menampilkan halaman detail untuk satu pendaftaran poli.
     */
    public function showDetail($id)
    {
        $pendaftaran = Periksa::with(['jadwal.poli', 'dokter'])->findOrFail($id);
        if ($pendaftaran->pasien_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk melihat detail ini.');
        }
        return view('pasien.detail_poli', compact('pendaftaran'));
    }
}
