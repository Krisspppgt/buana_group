@extends('layouts.app')
@section('title', 'Daftar - Buana Group')

@section('content')
<div class="card">
    <h1>Daftar</h1>
    <p class="sub">Buat akun untuk memesan layanan Buana Group.</p>

    <form method="POST" action="/daftar">
        @csrf

        <label for="name">Nama</label>
        <input id="name" name="name" type="text" maxlength="40" autocomplete="name"
               value="{{ old('name') }}" required>
        @error('name') <div class="err">{{ $message }}</div> @enderror

        <label for="phone">Nomor HP</label>
        <input id="phone" name="phone" type="tel" inputmode="numeric" autocomplete="tel"
               value="{{ old('phone') }}" placeholder="081234567890" required>
        @error('phone') <div class="err">{{ $message }}</div> @enderror

        <label for="campus_id">NIM / NIP (opsional)</label>
        <input id="campus_id" name="campus_id" type="text" maxlength="20" value="{{ old('campus_id') }}">
        <div class="sub" style="margin:4px 0 0">Isi jika kamu mahasiswa atau pegawai, untuk harga khusus.</div>
        @error('campus_id') <div class="err">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" required>
        @error('password') <div class="err">{{ $message }}</div> @enderror

        <label for="password_confirmation">Ulangi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password"
               autocomplete="new-password" required>

        <button type="submit">Daftar</button>
    </form>

    <div class="link">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></div>
</div>
@endsection
