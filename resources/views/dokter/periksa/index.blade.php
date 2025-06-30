@extends('adminlte::page')

@section('title', 'Daftar Periksa Pasien')

@section('content_header')
    <h1>Daftar Periksa Pasien</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Antrean Pasien Hari Ini</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Urut</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan</th>
                            <th>Status</th>
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Variabel $daftarPeriksa dikirim dari PeriksaController --}}
                        @forelse ($daftarPeriksa as $periksa)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $periksa->pasien->name ?? 'N/A' }}</td>
                                <td>{{ $periksa->keluhan }}</td>
                                <td>
                                    {{-- Tampilkan status  --}}
                                    @if ($periksa->status == 'menunggu')
                                        <span class="badge badge-warning">Menunggu</span>
                                    @else
                                        <span class="badge badge-success">Selesai</span>
                                    @endif
                                </td>
                                <td>
                                    {{--  LOGIKA TOMBOL BARU --}}
                                    @if ($periksa->status == 'menunggu')
                                        {{-- Jika belum diperiksa, tombolnya 'Periksa' (biru) --}}
                                        <a href="{{ route('dokter.periksa.mulai', $periksa->id) }}" class="btn btn-sm btn-primary">
                                            Periksa
                                        </a>
                                    @else
                                        {{-- Jika sudah selesai, tombolnya 'Edit' (kuning) --}}
                                        <a href="{{ route('dokter.periksa.edit', $periksa->id) }}" class="btn btn-sm btn-warning">
                                            Edit
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada pasien yang menunggu saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
