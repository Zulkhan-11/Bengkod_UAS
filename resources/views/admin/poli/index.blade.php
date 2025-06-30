@extends('adminlte::page')

@section('title', 'Mengelola Poli')

@section('content_header')
    <h1>Mengelola Poli</h1>
@stop

@section('content')

    {{-- Notifikasi untuk menampilkan pesan sukses setelah aksi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            {{ session('success') }}
        </div>
    @endif
    
    {{-- Notifikasi untuk menampilkan error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- CARD UNTUK FORM TAMBAH DATA --}}
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Poli Baru</h3>
        </div>
        <form action="{{ route('admin.poli.store') }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group">
                    <label for="nama_poli">Nama Poli</label>
                    <input type="text" class="form-control" id="nama_poli" name="nama_poli" value="{{ old('nama_poli') }}" placeholder="Masukkan nama poli" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="{{ old('deskripsi') }}" placeholder="Masukkan deskripsi singkat">
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>


    {{-- CARD UNTUK TABEL DAFTAR DATA --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Poli</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Poli</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($polis as $poli)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $poli->nama_poli }}</td>
                            <td>{{ $poli->deskripsi }}</td> 
                            <td>
                                <form action="{{ route('admin.poli.destroy', $poli->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus?');" class="d-inline">
                                    <a href="{{ route('admin.poli.edit', $poli->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data poliklinik.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop
