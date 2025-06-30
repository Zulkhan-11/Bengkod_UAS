<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\JadwalOperasi;
use App\Models\Notifikasi;
use App\Models\Obat;

class HomeController extends Controller
{
    /**
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Untuk Menampilkan dashboard aplikasi.
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    /**
     * Untuk Menampilkan dashboard untuk dokter.
     */
    public function dokter()
    {
        $dokterId = Auth::id();
        $jumlahJadwal = JadwalOperasi::where('dokter_id', $dokterId)->where('status', 'Terjadwal')->count();
        $jumlahPasien = User::where('role', 'pasien')->count();
        $jumlahObat = Obat::count();
        $jumlahNotifikasi = Notifikasi::where('user_id', $dokterId)->where('is_read', false)->count();

        return view('dokter.index', compact(
            'jumlahJadwal',
            'jumlahPasien',
            'jumlahObat',
            'jumlahNotifikasi'
        ));
    }

    /**
     * Menampilkan halaman profil dokter.
     * Memanggil view 'dokter.profil' (asumsi file adalah resources/views/dokter/profil.blade.php)
     * Atau 'dokter.profil.index' jika file adalah resources/views/dokter/profil/index.blade.php
     */
    public function profil()
    {
        // Untuk Menyesuaikan baris ini berdasarkan lokasi file profil.blade.php Anda
        return view('dokter.profil.index');
    }

    /**
     * Untuk Memperbarui profil dokter. (Disesuaikan dengan nama kolom 'alamat' dan 'no_hp')
     */
    public function updateProfil(Request $request)
    {
        $user = Auth::user();

        // Validasi disesuaikan dengan form baru dan nama kolom yang benar
        $request->validate([
            'name' => 'required|string|max:255',
            'alamat' => 'nullable|string|max:255',
            'no_hp' => 'nullable|string|max:20', 
        ]);

        // untuk Update informasi dasar dengan nama kolom yang benar
        $user->name = $request->name;
        $user->alamat = $request->alamat;
        $user->no_hp = $request->no_hp; 

        // untuk Simpan perubahan ke database
        $user->save();

        // Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('dokter.profil')->with('success', 'Profil berhasil diperbarui!');
    }
}
