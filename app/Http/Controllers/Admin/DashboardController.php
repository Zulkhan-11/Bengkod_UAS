<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Obat;
use App\Models\Poli;

class DashboardController extends Controller
{
    public function index()
    {
        // untuk menghitung semua user dengan role 'dokter'
        $jumlahDokter = User::where('role', 'dokter')->count();

        // untuk menghitung semua user dengan role 'pasien'
        $jumlahPasien = User::where('role', 'pasien')->count();

        // untuk menghitung semua jenis obat yang ada
        $jumlahObat = Obat::count();

        // data untuk poli
        $jumlahPoli = Poli::count(); 

        // untuk mengirim semua data ke view
        return view('admin.dashboard', compact(
            'jumlahDokter',
            'jumlahPasien',
            'jumlahObat',
            'jumlahPoli'
        ));
    }
}