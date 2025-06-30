<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use App\Models\JadwalPeriksa;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalPeriksaController extends Controller
{
    /**
     * untuk Menampilkan halaman daftar jadwal periksa milik dokter yang login.
     */
    public function index()
    {
        // untuk Mengurutkan jadwal agar yang aktif selalu di atas
        $jadwals = JadwalPeriksa::where('dokter_id', Auth::id())
                    ->orderBy('status', 'desc')
                    ->get();
        return view('dokter.jadwal_periksa.index', compact('jadwals'));
    }

    /**
     * untuk Menampilkan halaman dengan form untuk menambah jadwal baru.
     */
    public function create()
    {
        return view('dokter.jadwal_periksa.create');
    }

    /**
     * untuk Menyimpan jadwal baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        JadwalPeriksa::create([
            'dokter_id' => Auth::id(),
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => false,
        ]);

        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    /**
     *untuk Menampilkan form untuk mengedit jadwal yang spesifik.
     */
    public function edit(JadwalPeriksa $jadwalPeriksa)
    {
        if ($jadwalPeriksa->dokter_id != Auth::id()) {
            abort(403);
        }
        return view('dokter.jadwal_periksa.edit', compact('jadwalPeriksa'));
    }

    /**
     * untuk Memperbarui status jadwal dari halaman edit.
     */
    public function update(Request $request, JadwalPeriksa $jadwalPeriksa)
    {
        if ($jadwalPeriksa->dokter_id != Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|boolean',
        ]);
        
        if ($request->status == 1) {
            JadwalPeriksa::where('dokter_id', Auth::id())
                          ->where('id', '!=', $jadwalPeriksa->id)
                          ->update(['status' => false]);
        }

        $jadwalPeriksa->update([
            'status' => $request->status,
        ]);

        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Status jadwal berhasil diperbarui.');
    }

    /**
     * untuk Menghapus jadwal.
     */
    public function destroy(JadwalPeriksa $jadwalPeriksa)
    {
        if ($jadwalPeriksa->dokter_id != Auth::id()) {
            abort(403);
        }
        $jadwalPeriksa->delete();
        return redirect()->route('dokter.jadwal-periksa.index')->with('success', 'Jadwal berhasil dihapus.');
    }

    public function getJadwalByPoli($poli_id)
    {
        $dokterIds = User::where('role', 'dokter')
                         ->where('poli_id', $poli_id)
                         ->pluck('id');

        $jadwals = JadwalPeriksa::with('dokter')
                    ->whereIn('dokter_id', $dokterIds)
                    ->where('status', true)
                    ->get();

        return response()->json($jadwals);
    }
}
