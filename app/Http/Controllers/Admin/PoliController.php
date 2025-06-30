<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Poli;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliController extends Controller
{
    public function index()
    {
        $polis = Poli::latest()->get();
        return view('admin.poli.index', compact('polis'));
    }

    public function create()
    {
        return view('admin.poli.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama_poli' => 'required|string|max:255|unique:polis',
            'deskripsi' => 'nullable|string|max:255', // Menggunakan 'deskripsi'
        ]);

        Poli::create($validatedData);
        return redirect()->route('admin.poli.index')->with('success', 'Poli baru berhasil ditambahkan.');
    }

    public function show(Poli $poli)
    {
    
    }

    public function edit(Poli $poli)
    {
        return view('admin.poli.edit', compact('poli'));
    }

    public function update(Request $request, Poli $poli)
    {
        $validatedData = $request->validate([
            'nama_poli' => ['required','string','max:255', Rule::unique('polis')->ignore($poli->id)],
            'deskripsi' => 'nullable|string|max:255', // Menggunakan 'deskripsi'
        ]);

        $poli->update($validatedData);
        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil diperbarui.');
    }

    public function destroy(Poli $poli)
    {
        $poli->delete();
        return redirect()->route('admin.poli.index')->with('success', 'Data poli berhasil dihapus.');
    }
}
