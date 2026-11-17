@extends('layouts.app')
@section('title', 'Beranda - Buana Group')

@section('content')
<h1>Halo, {{ auth()->user()->name }}</h1>
<p class="sub">Pilih layanan yang kamu butuhkan.</p>

<div class="grid">
    <a class="unit" href="#"><b>Air Mineral Unwahas</b><span>Botol, gelas, dan galon</span></a>
    <a class="unit" href="#"><b>Buana Food Services</b><span>Snack box, nasi kotak, katering</span></a>
    <a class="unit" href="#"><b>Buana Konveksi</b><span>Almamater, PDH, PDL, kaos, jaket</span></a>
</div>

<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn-ghost">Keluar</button>
</form>
@endsection
