@extends('adminlte::page')

@section('title', 'Dashboard Pasien')

@section('content_header')
    <h1>Dashboard pasien</h1>
@stop

@section('content')
<div class="container-fluid">
    {{-- Baris untuk 4 kotak statistik --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    {{-- Anda bisa mengganti angka ini dengan data dari controller, contoh: {{ $jumlahPasien }} --}}
                    <h3>150</h3>
                    <p>Pasien Baru</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a href="#" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>53<sup style="font-size: 20px">%</sup></h3>
                    <p>Tingkat Kunjungan</p>
                </div>
                <div class="icon"><i class="ion ion-stats-bars"></i></div>
                <a href="#" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>44</h3>
                    <p>Jadwal Terdaftar</p>
                </div>
                <div class="icon"><i class="ion ion-person-add"></i></div>
                <a href="#" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>65</h3>
                    <p>Pemeriksaan Selesai</p>
                </div>
                <div class="icon"><i class="ion ion-pie-graph"></i></div>
                <a href="#" class="small-box-footer">Info lebih lanjut <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>


</div>
@stop

@push('css')
{{-- Library Ion-Icons ini tetap diperlukan untuk ikon di kotak statistik --}}
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

{{-- Bagian @push('js') untuk grafik sudah dihapus seluruhnya --}}
