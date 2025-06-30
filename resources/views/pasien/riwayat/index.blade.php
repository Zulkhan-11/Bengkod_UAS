@extends('adminlte::page')

@section('title', 'Riwayat Pemeriksaan')

@section('content_header')
    <h1>Riwayat Pemeriksaan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Riwayat Pemeriksaan Anda</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Tanggal Periksa</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                {{-- PERBAIKAN: Menggunakan variabel $riwayatPemeriksaan sesuai yang dikirim Controller --}}
                @forelse ($riwayatPemeriksaan as $periksa)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d F Y') }}</td>
                        <td>{{ $periksa->jadwal?->poli?->nama_poli ?? 'N/A' }}</td>
                        <td>{{ $periksa->dokter?->name ?? 'N/A' }}</td>
                        <td>
                            @if ($periksa->status == 'menunggu')
                                <span class="badge badge-warning">Belum Diperiksa</span>
                            @else
                                <span class="badge badge-success">Selesai</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('pasien.riwayat.show', $periksa->id) }}" class="btn btn-sm btn-info">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Anda belum memiliki riwayat pemeriksaan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop
