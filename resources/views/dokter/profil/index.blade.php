@extends('adminlte::page')

@section('title', 'Profil Dokter') {{-- Mengubah judul halaman --}}

@section('content_header')
    <h1>Profil Dokter</h1> {{-- Mengubah judul header --}}
@stop

@section('content')
<div class="row">
    {{-- Kolom Tunggal: Form Edit --}}
    <div class="col-md-12"> {{-- Menggunakan seluruh lebar kolom --}}
        {{-- Menampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif
        {{-- Menampilkan pesan error validasi jika ada --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header p-2">
                <h3 class="card-title p-2"><i class="fas fa-edit"></i> Edit Data Dokter</h3> {{-- Mengubah judul kartu --}}
            </div>
            <div class="card-body">
                {{-- Form untuk memperbarui profil, mengarah ke route dokter.profil.update --}}
                <form class="form-horizontal" action="{{ route('dokter.profil.update') }}" method="POST">
                    @csrf {{-- Token CSRF untuk keamanan --}}
                    @method('PUT') {{-- Metode HTTP PUT untuk pembaruan --}}

                    <div class="form-group row">
                        <label for="name" class="col-sm-2 col-form-label">Nama Dokter</label> {{-- Mengubah label --}}
                        <div class="col-sm-10">
                            {{-- Input nama, mengisi nilai dari old() untuk retensi data setelah validasi gagal, atau dari Auth::user()->name --}}
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                        </div>
                    </div>
                    
                    {{-- Bidang Alamat Dokter, menggunakan 'alamat' sebagai name dan data --}}
                    <div class="form-group row">
                        <label for="alamat" class="col-sm-2 col-form-label">Alamat Dokter</label>
                        <div class="col-sm-10">
                            {{-- Menggunakan textarea untuk alamat, nilai dari Auth::user()->alamat --}}
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" placeholder="Masukkan alamat lengkap dokter">{{ old('alamat', Auth::user()->alamat) }}</textarea>
                        </div>
                    </div>

                    {{-- Bidang Telepon Dokter, menggunakan 'no_hp' sebagai name dan data --}}
                    <div class="form-group row">
                        <label for="no_hp" class="col-sm-2 col-form-label">Telepon Dokter</label>
                        <div class="col-sm-10">
                            {{-- Input telepon, nilai dari Auth::user()->no_hp --}}
                            <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Contoh: 081234567890" value="{{ old('no_hp', Auth::user()->no_hp) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-sm-2 col-sm-10">
                            {{-- Tombol Simpan Perubahan diatur ke kanan --}}
                            <button type="submit" class="btn btn-primary float-right">Simpan Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
