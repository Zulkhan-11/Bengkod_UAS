{{-- resources/views/pasien/detail_poli.blade.php --}}
@extends('adminlte::page')

@section('title', 'Detail Pendaftaran Poli')

@section('content_header')
    <h1>Detail Pendaftaran Poli</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">Detail Pendaftaran</h3>
                <div class="card-tools">
                    {{-- Tombol untuk kembali ke daftar poli --}}
                    <a href="{{ route('pasien.poli.daftar') }}" class="btn btn-tool"><i class="fas fa-times"></i></a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4">Nomor Rekam Medis:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->user->no_rm ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Nama Pasien:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->user->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Poli:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->jadwal->poli->nama_poli ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Dokter:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->jadwal->dokter->name ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Hari:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->jadwal->hari ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Waktu Mulai:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pendaftaran->jadwal->jam_mulai ?? '')->format('H:i') }}</dd>

                    <dt class="col-sm-4">Waktu Selesai:</dt>
                    <dd class="col-sm-8">{{ \Carbon\Carbon::parse($pendaftaran->jadwal->jam_selesai ?? '')->format('H:i') }}</dd>

                    <dt class="col-sm-4">Nomor Antrian:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->no_antrian ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Keluhan:</dt>
                    <dd class="col-sm-8">{{ $pendaftaran->keluhan ?? 'N/A' }}</dd>

                    <dt class="col-sm-4">Status:</dt>
                    <dd class="col-sm-8">
                        @php
                            $statusClass = '';
                            $displayText = '';
                            switch(strtolower($pendaftaran->status)) {
                                case 'menunggu':
                                    $statusClass = 'badge bg-danger';
                                    $displayText = 'Belum diperiksa';
                                    break;
                                case 'diterima':
                                    $statusClass = 'badge bg-success';
                                    $displayText = 'Diterima';
                                    break;
                                case 'selesai':
                                    $statusClass = 'badge bg-primary';
                                    $displayText = 'Selesai';
                                    break;
                                case 'ditolak':
                                    $statusClass = 'badge bg-danger';
                                    $displayText = 'Ditolak';
                                    break;
                                default:
                                    $statusClass = 'badge bg-secondary';
                                    $displayText = $pendaftaran->status;
                                    break;
                            }
                        @endphp
                        <span class="{{ $statusClass }}">{{ $displayText }}</span>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</div>
@stop