@extends('layouts.app')
@section('title', 'Masuk - Buana Group')

@section('content')
<div class="card">
    <h1>Masuk</h1>
    <p class="sub">Gunakan nomor HP yang terdaftar.</p>

    <form method="POST" action="/login">
        @csrf

        <label for="phone">Nomor HP</label>
        <input id="phone" name="phone" type="tel" inputmode="numeric" autocomplete="tel"
               value="{{ old('phone') }}" placeholder="081234567890" required>
        @error('phone') <div class="err">{{ $message }}</div> @enderror

        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        @error('password') <div class="err">{{ $message }}</div> @enderror

        <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>

        <button type="submit">Masuk</button>
    </form>

    <div class="link">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></div>
</div>
@endsection
