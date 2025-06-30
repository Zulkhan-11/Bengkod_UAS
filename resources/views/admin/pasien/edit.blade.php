@extends('adminlte::page')

@section('title', 'Edit Pasien')
@section('content_header')<h1>Edit Data Pasien: {{ $pasien->name }}</h1>@stop

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger"><strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card card-warning">
        <div class="card-header"><h3 class="card-title">Form Edit Pasien</h3></div>
        <form action="{{ route('admin.pasien.update', $pasien->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group"><label for="name">Nama Pasien</label><input type="text" class="form-control" id="name" name="name" value="{{ old('name', $pasien->name) }}" required></div>
                <div class="form-group"><label for="alamat">Alamat</label><textarea class="form-control" id="alamat" name="alamat" rows="2" required>{{ old('alamat', $pasien->alamat) }}</textarea></div>
                <div class="form-group"><label for="nik">Nomor KTP</label><input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $pasien->nik) }}" required></div>
                <div class="form-group"><label for="no_hp">Nomor HP</label><input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}" required></div>
                <div class="form-group">
                    <label for="no_rm">Nomor RM</label>
                    {{-- Nilai diambil langsung dari $pasien->no_rm dan input dibuat readonly --}}
                    <input type="text" class="form-control" id="no_rm" name="no_rm" value="{{ $pasien->no_rm }}" readonly>
                    <small class="form-text text-muted">Nomor Rekam Medis tidak dapat diubah.</small>
                </div>
            </div>
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.pasien.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
@stop
