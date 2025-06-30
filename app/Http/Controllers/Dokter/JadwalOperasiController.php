<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalOperasi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class JadwalOperasiController extends Controller
{
    /**
     * untuk Menampilkan halaman daftar semua jadwal operasi dengan filter.
     */
    public function index(Request $request) 
    {
        // untuk Memulai query dasar
        $query = JadwalOperasi::where('dokter_id', Auth::id());

        // untuk Menerapkan filter berdasarkan input dari form
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('waktu_operasi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('waktu_operasi', '<=', $request->tanggal_selesai);
        }
        $jadwals = $query->with('pasien')->latest('waktu_operasi')->get();

        // untuk menampilkan view dan kirim data 'jadwals'
        return view('dokter.jadwal.index', compact('jadwals'));
    }

    /**
     * untuk menampilkan form untuk membuat jadwal operasi baru.
     */
    public function create()
    {
        $pasiens = User::where('role', 'pasien')->get();
        return view('dokter.jadwal.create', compact('pasiens'));
    }

    /**
     * untuk Menyimpan data jadwal operasi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pasien_id' => 'required|exists:users,id',
            'jenis_operasi' => 'required|string|max:255',
            'waktu_operasi' => 'required|date',
            'ruang_operasi' => 'required|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['dokter_id'] = Auth::id();

        JadwalOperasi::create($data);

        return redirect()->route('dokter.jadwal.index')
                         ->with('success', 'Jadwal operasi baru berhasil ditambahkan.');
    }

    /**
     * untuk Menampilkan form untuk mengedit jadwal operasi.
     */
    public function edit($id)
    {
        $jadwal = JadwalOperasi::findOrFail($id);
        $pasiens = User::where('role', 'pasien')->get();
        
        return view('dokter.jadwal.edit', compact('jadwal', 'pasiens'));
    }

    /**
     * untuk Memperbarui data jadwal operasi di database.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pasien_id' => 'required|exists:users,id',
            'jenis_operasi' => 'required|string|max:255',
            'waktu_operasi' => 'required|date',
            'ruang_operasi' => 'required|string|max:255',
            'status' => 'required|in:Terjadwal,Selesai,Dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $jadwal = JadwalOperasi::findOrFail($id);
        $jadwal->update($request->all());

        return redirect()->route('dokter.jadwal.index')
                         ->with('success', 'Jadwal operasi berhasil diperbarui.');
    }

    /**
     * untuk Menghapus (membatalkan) data jadwal operasi.
     */
    public function destroy($id)
    {
        $jadwal = JadwalOperasi::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('dokter.jadwal.index')
                         ->with('success', 'Jadwal operasi telah dihapus/dibatalkan.');
    }
}