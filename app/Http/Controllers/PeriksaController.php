<?php

// Pastikan namespace ini sesuai dengan lokasi file Anda
namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Periksa;
use App\Models\Obat;
use App\Models\DetailPeriksa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PeriksaController extends Controller
{
    /**
     * Menampilkan SEMUA pasien untuk dokter yang login hari ini.
     * Tidak lagi memfilter berdasarkan status 'menunggu'.
     */
    public function index()
    {
        $dokterId = Auth::id();
        $daftarPeriksa = Periksa::with('pasien')
                                ->where('dokter_id', $dokterId)
                                ->whereDate('tgl_periksa', now()) 
                                ->orderBy('status', 'asc') // Tampilkan 'menunggu' dulu
                                ->orderBy('created_at', 'asc')
                                ->get();
        return view('dokter.periksa.index', compact('daftarPeriksa'));
    }

    /**
     * Menampilkan form untuk memulai pemeriksaan (data baru).
     */
    public function create(Periksa $periksa)
    {
        if ($periksa->dokter_id != Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        $obats = Obat::get(['id', 'nama_obat', 'harga']); 
        
        return view('dokter.periksa.form', compact('periksa', 'obats'));
    }

    /**
     * Menampilkan form untuk mengedit pemeriksaan yang sudah ada.
     */
    public function edit(Periksa $periksa)
    {
        if ($periksa->dokter_id != Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }
        
        $periksa->load('detail'); // Memuat detail resep yang sudah ada
        $obats = Obat::get(['id', 'nama_obat', 'harga']);
        
        return view('dokter.periksa.edit', compact('periksa', 'obats')); 
    }

    /**
     * Menyimpan data dari pemeriksaan BARU.
     */
    public function store(Request $request, Periksa $periksa)
    {
        // Untuk data baru, kita panggil fungsi update.
        // Ini untuk menjaga agar logika validasi dan penyimpanan terpusat di satu fungsi.
        return $this->update($request, $periksa);
    }
    
    /**
     * Memperbarui data pemeriksaan yang sudah ada.
     * Fungsi ini sekarang menjadi pusat logika penyimpanan.
     */
    public function update(Request $request, Periksa $periksa)
    {
        if ($periksa->dokter_id != Auth::id()) {
            abort(403, 'Akses tidak diizinkan.');
        }

        // Validasi untuk form resep dinamis, termasuk tanggal yang bisa diedit
        $request->validate([
            'tgl_periksa' => 'nullable|date', // 'nullable' karena di form 'create' tidak ada input ini
            'catatan' => 'required|string',
            'obat_id' => 'nullable|array',
            'obat_id.*' => 'required_with:obat_id|exists:obat,id',
            'jumlah' => 'nullable|array',
            'jumlah.*' => 'required_with:obat_id|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $periksa) {
            $totalHarga = 0;

            // Hapus detail resep lama agar bisa diganti dengan yang baru
            DetailPeriksa::where('periksa_id', $periksa->id)->delete();

            // Proses setiap item obat yang diresepkan
            if ($request->has('obat_id')) {
                foreach ($request->obat_id as $index => $id_obat) {
                    $obat = Obat::find($id_obat);
                    $jumlah = $request->jumlah[$index];
                    
                    if ($obat && $jumlah > 0) {
                        DetailPeriksa::create([
                            'periksa_id' => $periksa->id,
                            'obat_id' => $id_obat,
                            'jumlah' => $jumlah,
                        ]);
                        $totalHarga += $obat->harga * $jumlah;
                    }
                }
            }
            
            // Siapkan data untuk di-update
            $updateData = [
                'catatan' => $request->catatan,
                'diagnosa' => 'Sesuai resep.',
                'total_harga_obat' => $totalHarga,
                'status' => 'selesai',
            ];

            // Jika ada input tanggal baru dari form edit, gunakan itu
            if ($request->has('tgl_periksa')) {
                $updateData['tgl_periksa'] = $request->tgl_periksa;
            }

            // Update data pemeriksaan utama
            $periksa->update($updateData);
        });

        return redirect()->route('dokter.periksa.index')->with('success', 'Data pemeriksaan berhasil diperbarui!');
    }
    
    /**
     * Menampilkan riwayat pemeriksaan.
     */
    public function riwayat()
    {
        $riwayatPeriksa = Periksa::with('pasien')
                                ->where('dokter_id', Auth::id())
                                ->where('status', 'selesai')
                                ->latest('tgl_periksa')
                                ->get();
        return view('dokter.riwayat.index', compact('riwayatPeriksa'));
    }
}
