<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules; 

class PasienController extends Controller
{

    /**
     * Menampilkan form untuk mengedit data pasien.
     * Laravel akan otomatis mencari Pasien berdasarkan ID berkat Route Model Binding.
     *
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function edit(Pasien $pasien)
    {
        // untuk Mengirim data pasien yang akan diedit ke view
        return view('admin.pasien.edit', compact('pasien'));
    }

    /**
     * untuk Memperbarui data pasien di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pasien $pasien)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                // Pastikan email unik, kecuali untuk pasien ini sendiri
                Rule::unique('pasiens')->ignore($pasien->id),
            ],
            'no_hp' => 'required|string|max:15',
        ]);

        // Update data pasien
        $pasien->update([
            'nama' => $request->nama,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ]);

        // Redirect kembali ke halaman daftar pasien dengan pesan sukses
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * untuk Menghapus data pasien dari database.
     *
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pasien $pasien)
    {
        // untuk Hapus data pasien
        $pasien->delete();

        // Redirect kembali ke halaman daftar pasien dengan pesan sukses
        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil dihapus.');
    }
}