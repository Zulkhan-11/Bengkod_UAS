@extends('adminlte::page')

@section('title', 'Edit Periksa Pasien')

@section('content_header')
    <h1>Edit Pemeriksaan: {{ $periksa->pasien->name ?? 'N/A' }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ route('dokter.periksa.update', $periksa->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- KARTU DETAIL PASIEN --}}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Pasien</label>
                                <input type="text" class="form-control" value="{{ $periksa->pasien->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                             <div class="form-group">
                                <label>Keluhan Utama</label>
                                <input type="text" class="form-control" value="{{ $periksa->keluhan }}" readonly>
                            </div>
                        </div>
                        {{-- =============================================== --}}
                        {{-- == BAGIAN INI YANG DIPERBARUI                == --}}
                        {{-- =============================================== --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tanggal Periksa</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($periksa->tgl_periksa)->format('d F Y H:i') }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KARTU CATATAN / DIAGNOSA --}}
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="card-title">Catatan / Diagnosa</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        {{-- Mengubah rows menjadi 3 agar lebih kecil --}}
                        <textarea id="catatan" name="catatan" class="form-control" rows="3" placeholder="Masukkan catatan dan diagnosa hasil pemeriksaan..." required>{{ old('catatan', $periksa->catatan) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- KARTU RESEP OBAT --}}
            <div class="card">
                <div class="card-header bg-success">
                    <h3 class="card-title">Resep Obat</h3>
                </div>
                <div class="card-body">
                    <div id="resep-obat-container">
                        {{-- Baris resep akan ditambahkan di sini oleh JavaScript --}}
                    </div>
                    <button type="button" id="tambah-obat" class="btn btn-info mt-2">
                        <i class="fas fa-plus"></i> Tambah Obat
                    </button>
                </div>
            </div>

            {{-- KARTU BIAYA --}}
            <div class="card">
                 <div class="card-header bg-warning">
                    <h3 class="card-title">Total Biaya</h3>
                 </div>
                 <div class="card-body">
                    <div class="form-group">
                        <label for="total_harga_obat">Total Harga Obat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" id="total_harga_obat" name="total_harga_obat" class="form-control" value="{{ old('total_harga_obat', $periksa->total_harga_obat) }}" readonly>
                        </div>
                    </div>
                 </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="mt-3 mb-4">
                <a href="{{ route('dokter.periksa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary float-right">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@stop

@push('js')
<script>
$(document).ready(function() {
    let resepCounter = 0;
    const obats = @json($obats);
    const resepLama = @json($periksa->detail);

    function calculateTotal() {
        let grandTotal = 0;
        $('.resep-row').each(function() {
            const obatId = $(this).find('.obat-select').val();
            const quantity = $(this).find('.obat-quantity').val();
            if (obatId && quantity > 0) {
                const selectedObat = obats.find(obat => obat.id == obatId);
                if (selectedObat) {
                    grandTotal += selectedObat.harga * quantity;
                }
            }
        });
        $('#total_harga_obat').val(grandTotal);
    }

    function addResepRow(obatId = '', jumlah = '') {
        resepCounter++;
        let obatOptions = '<option value="">-- Pilih Obat --</option>';
        obats.forEach(function(obat) {
            const isSelected = obat.id == obatId ? 'selected' : '';
            obatOptions += `<option value="${obat.id}" data-harga="${obat.harga}" ${isSelected}>${obat.nama_obat} (Rp ${obat.harga})</option>`;
        });

        const newResepRow = `
            <div class="row align-items-center resep-row mb-2" id="resep-row-${resepCounter}">
                <div class="col-md-7">
                    <select name="obat_id[]" class="form-control obat-select" required>${obatOptions}</select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="jumlah[]" class="form-control obat-quantity" placeholder="Jumlah" min="1" value="${jumlah}" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn-sm hapus-resep" data-row-id="${resepCounter}">Hapus</button>
                </div>
            </div>
        `;
        $('#resep-obat-container').append(newResepRow);
    }

    if (resepLama && resepLama.length > 0) {
        resepLama.forEach(function(resep) {
            addResepRow(resep.obat_id, resep.jumlah);
        });
    }

    $('#tambah-obat').on('click', function() {
        addResepRow();
    });

    $(document).on('click', '.hapus-resep', function() {
        $(this).closest('.resep-row').remove();
        calculateTotal();
    });

    $(document).on('change', '.obat-select, .obat-quantity', function() {
        calculateTotal();
    });

    calculateTotal();
});
</script>
@endpush
