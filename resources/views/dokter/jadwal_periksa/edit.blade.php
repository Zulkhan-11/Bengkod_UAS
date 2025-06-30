@extends('adminlte::page')

@section('title', 'Edit Status Jadwal')

@section('content_header')
    <h1>Edit Status Jadwal</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif
        <div class="card card-primary">
            <div class="card-header"><h3 class="card-title">Informasi Jadwal</h3></div>
            <form action="{{ route('dokter.jadwal-periksa.update', $jadwalPeriksa->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    {{-- Input Hari, Jam Mulai, dan Jam Selesai --}}
                    <div class="form-group">
                        <label>Hari</label>
                        <input type="text" class="form-control" value="{{ $jadwalPeriksa->hari }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($jadwalPeriksa->jam_mulai)->format('H:i') }}" readonly>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai</label>
                        <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($jadwalPeriksa->jam_selesai)->format('H:i') }}" readonly>
                    </div>
                    
                    <hr>

                    {{-- Hanya pilihan Status yang bisa diubah --}}
                    <div class="form-group">
                        <label>Ubah Status</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status_aktif" value="1" {{ $jadwalPeriksa->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_aktif">Aktif</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status_tidak_aktif" value="0" {{ !$jadwalPeriksa->status ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_tidak_aktif">Tidak Aktif</label>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('dokter.jadwal-periksa.index') }}" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
