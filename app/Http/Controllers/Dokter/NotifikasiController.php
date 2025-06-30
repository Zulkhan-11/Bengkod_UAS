<?php

namespace App\Http\Controllers\Dokter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    public function index()
    {
        // untuk mengambil semua notifikasi untuk dokter yang sedang login
        $notifikasis = Notifikasi::where('user_id', Auth::id())->latest()->get();

        // untuk menampilkan view dan kirim data
        return view('dokter.notifikasi.index', compact('notifikasis'));
    }
}