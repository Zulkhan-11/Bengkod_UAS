@extends('adminlte::page')

@section('title', 'Riwayat Pasien')

@section('content_header')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active">Riwayat Pasien</li>
    </ol>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Riwayat Pemeriksaan Selesai</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Tanggal Periksa</th>
                            <th>Nama Pasien</th>
                            <th>Catatan</th>
                            {{-- Lebar kolom aksi disesuaikan --}}
                            <th style="width: 100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($riwayatPeriksa as $periksa)
                            @php
                                // Menyiapkan daftar obat dari relasi untuk modal
                                $obatList = $periksa->detail->map(function($d) {
                                    return $d->obat->nama_obat ?? 'Obat tidak ditemukan';
                                });
                            @endphp
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $periksa->created_at->format('d F Y') }}</td>
                                <td>{{ $periksa->pasien->name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($periksa->catatan, 50, '...') }}</td>
                                <td>
                                    {{-- HANYA TOMBOL DETAIL, TOMBOL EDIT DIHILANGKAN --}}
                                    <button type="button" class="btn btn-sm btn-info detail-btn" 
                                        data-toggle="modal" 
                                        data-target="#detailModal"
                                        data-pasien-nama="{{ $periksa->pasien->name ?? 'N/A' }}"
                                        data-tanggal="{{ $periksa->created_at->format('d-m-Y H:i') }}"
                                        data-keluhan="{{ $periksa->keluhan }}"
                                        data-catatan="{{ $periksa->catatan }}"
                                        data-obat='{{ $obatList->toJson() }}'
                                        data-biaya="Rp{{ number_format($periksa->total_harga_obat, 0, ',', '.') }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    Belum ada riwayat pemeriksaan yang selesai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Detail Riwayat -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Detail Riwayat Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal Periksa</th>
                            <th>Nama Pasien</th>
                            <th>Keluhan Awal</th>
                            <th>Catatan Dokter</th>
                            <th>Resep Obat</th>
                            <th>Total Biaya Obat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td id="modal-tanggal"></td>
                            <td id="modal-pasien-nama"></td>
                            <td id="modal-keluhan"></td>
                            <td id="modal-catatan"></td>
                            <td id="modal-obat"></td>
                            <td id="modal-biaya"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
$(document).ready(function() {
    // Event listener saat modal akan ditampilkan
    $('#detailModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); 
        
        // Ekstrak data dari atribut data-*
        var pasienNama = button.data('pasien-nama');
        var tanggal = button.data('tanggal');
        var keluhan = button.data('keluhan');
        var catatan = button.data('catatan');
        var biaya = button.data('biaya');
        var obatList = button.data('obat');

        // Ubah string JSON obat menjadi daftar HTML yang rapi
        var obatHtml = '<ul class="list-unstyled mb-0">';
        if(obatList && obatList.length > 0) {
            obatList.forEach(function(obat) {
                obatHtml += '<li>' + obat + '</li>';
            });
        } else {
            obatHtml += '<li>-</li>';
        }
        obatHtml += '</ul>';

        // Update konten modal
        var modal = $(this);
        modal.find('.modal-title').text('Riwayat ' + pasienNama);
        modal.find('#modal-tanggal').text(tanggal);
        modal.find('#modal-pasien-nama').text(pasienNama);
        modal.find('#modal-keluhan').text(keluhan);
        modal.find('#modal-catatan').text(catatan);
        modal.find('#modal-obat').html(obatHtml);
        modal.find('#modal-biaya').text(biaya);
    });
});
</script>
@stop
