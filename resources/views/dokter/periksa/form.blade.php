@extends('adminlte::page')

@section('title', 'Periksa Pasien')

@section('content_header')
    <h1>Periksa Pasien</h1>
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dokter.dashboard') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('dokter.periksa.index') }}">Daftar Periksa</a></li>
        <li class="breadcrumb-item active">Periksa Pasien</li>
    </ol>
@stop

@section('content')
<form action="{{ route('dokter.periksa.store', $periksa->id) }}" method="POST">
    @csrf
    <div class="row">
        <div class="col-12">

            {{-- KARTU DETAIL PASIEN & DIAGNOSA --}}
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Detail Pasien & Diagnosa</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Pasien</label>
                                <input type="text" class="form-control" value="{{ $periksa->pasien->name ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label>Keluhan Utama</label>
                                <input type="text" class="form-control" value="{{ $periksa->keluhan }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="catatan">Catatan / Diagnosa</label>
                        <textarea id="catatan" name="catatan" class="form-control" rows="4" placeholder="Masukkan catatan dan diagnosa hasil pemeriksaan..." required>{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- KARTU RESEP OBAT --}}
            <div class="card card-success">
                <div class="card-header">
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
            <div class="card card-warning">
                 <div class="card-header">
                    <h3 class="card-title">Total Biaya</h3>
                 </div>
                 <div class="card-body">
                    <div class="form-group">
                        <label for="total_harga_obat">Total Harga Obat</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="number" id="total_harga_obat" name="total_harga_obat" class="form-control" value="0" readonly>
                        </div>
                    </div>
                 </div>
            </div>

            {{-- TOMBOL AKSI --}}
            <div class="mt-3 mb-4">
                <a href="{{ route('dokter.periksa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary float-right">Simpan Hasil Pemeriksaan</button>
            </div>

        </div>
    </div>
</form>
@stop

@push('js')
<script>
$(document).ready(function() {
    let resepCounter = 0;
    const obats = @json($obats);

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

    $('#tambah-obat').on('click', function() {
        resepCounter++;
        
        let obatOptions = '<option value="">-- Pilih Obat --</option>';
        obats.forEach(function(obat) {
            obatOptions += `<option value="${obat.id}" data-harga="${obat.harga}">${obat.nama_obat} (Rp ${obat.harga})</option>`;
        });

        const newResepRow = `
            <div class="row align-items-center resep-row mb-2" id="resep-row-${resepCounter}">
                <div class="col-md-7">
                    <label>Nama Obat</label>
                    <select name="obat_id[]" class="form-control obat-select" required>
                        ${obatOptions}
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah[]" class="form-control obat-quantity" placeholder="Jumlah" min="1" required>
                </div>
                <div class="col-md-2">
                    <label>&nbsp;</label>
                    <button type="button" class="btn btn-danger form-control hapus-resep" data-row-id="${resepCounter}">Hapus</button>
                </div>
            </div>
        `;
        $('#resep-obat-container').append(newResepRow);
    });

    $(document).on('change', '.obat-select, .obat-quantity', function() {
        calculateTotal();
    });

    $(document).on('click', '.hapus-resep', function() {
        const rowId = $(this).data('row-id');
        $('#resep-row-' + rowId).remove();
        calculateTotal();
    });
});
</script>
@endpush
