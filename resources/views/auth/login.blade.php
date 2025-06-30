@extends('adminlte::auth.login')

{{-- Menambahkan konten ke dalam 'slot' auth_footer --}}
@section('auth_footer')
    {{-- @parent akan menampilkan isi asli dari footer (link 'Lupa Password') --}}
    @parent

    {{-- Ini adalah link untuk halaman registrasi --}}
    <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">
            Daftar keanggotaan baru
        </a>
    </p>
@stop