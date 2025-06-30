<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Poli;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function indexDokter()
    {
        $dokters = User::where('role', 'dokter')->latest()->paginate(10);
        $polis = Poli::all();
        return view('admin.dokter.index', compact('dokters', 'polis'));
    }
    public function createDokter()
    {
        $polis = Poli::all();
        return view('admin.dokter.create', compact('polis'));
    }
    public function storeDokter(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'alamat' => ['nullable', 'string'],
            'no_hp' => ['required', 'string', 'max:15'],
            'poli_id' => 'nullable|exists:polis,id',
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => 'dokter',
            'poli_id' => $request->poli_id,
            'nik' => 'DOK-' . time(),
            'password' => Hash::make('password'),
        ]);
        return redirect()->route('admin.dokter.index')->with('success', 'Akun dokter baru berhasil ditambahkan.');
    }
    public function editDokter(User $dokter)
    {
        if ($dokter->role !== 'dokter') { abort(404); }
        $polis = Poli::all();
        return view('admin.dokter.edit', compact('dokter', 'polis'));
    }
    public function updateDokter(Request $request, User $dokter)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($dokter->id)],
            'spesialis' => 'nullable|string|max:100',
            'poli_id' => 'nullable|exists:polis,id',
            'alamat' => 'nullable|string',
            'no_hp' => 'required|string|max:15',
        ]);
        $updateData = $request->only(['name', 'email', 'alamat', 'no_hp', 'spesialis', 'poli_id']);
        $dokter->update($updateData);
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil diperbarui.');
    }
    public function destroyDokter(User $dokter)
    {
        if ($dokter->role !== 'dokter') { abort(404); }
        $dokter->delete();
        return redirect()->route('admin.dokter.index')->with('success', 'Data dokter berhasil dihapus.');
    }

    // PENYESUAIAN ADA DI DALAM METHOD UNTUK PASIEN DI BAWAH INI

    public function indexPasien()
    {
        $pasiens = User::where('role', 'pasien')->latest()->paginate(10);
        return view('admin.pasien.index', compact('pasiens'));
    }

    public function createPasien()
    {
        return view('admin.pasien.create');
    }

    public function editPasien(User $pasien)
    {
        return view('admin.pasien.edit', compact('pasien'));
    }

    public function updatePasien(Request $request, User $pasien)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'digits:16', Rule::unique('users')->ignore($pasien->id)],
            'alamat' => 'required|string',
            'no_hp' => 'required|string|max:15',
            'no_rm' => ['nullable', 'string', Rule::unique('users')->ignore($pasien->id)],
        ]);
        
        // untuk Update data tanpa menyentuh password dan email
        $pasien->update($request->except(['password', 'email']));

        return redirect()->route('admin.pasien.index')
                         ->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroyPasien(User $pasien)
    {
        $pasien->delete();
        return redirect()->route('admin.pasien.index')
                         ->with('success', 'Data pasien berhasil dihapus.');
    }
    
    public function storePasien(Request $request)
    {
    
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nik' => ['required', 'string', 'digits:16', 'unique:users,nik'],
            'alamat' => ['required', 'string'],
            'no_hp' => ['required', 'string', 'max:15'],
        ]);

        // untuk Membuat No. RM, email, dan password default secara otomatis
        $no_rm = date('Ym') . '-' . str_pad(User::where('role', 'pasien')->count() + 1, 3, '0', STR_PAD_LEFT);

        User::create([
            'name' => $request->name,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'role' => 'pasien',
            'no_rm' => $no_rm,
            'email' => 'pasien.' . time() . '@gmail.com',
            'password' => Hash::make('password'),
        ]);

        return redirect()->route('admin.pasien.index')
                         ->with('success', 'Akun pasien baru berhasil ditambahkan.');
    }
}
