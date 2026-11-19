@extends('layouts.app')
@section('title', 'Profil Akun & Buku Alamat - Buana Group')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header Banner Profil --}}
    <div class="bg-gradient-to-r from-[#094234] via-[#0D5A47] to-[#0a4d3c] rounded-3xl p-6 sm:p-8 text-white shadow-lg relative overflow-hidden">
        <div class="absolute -right-8 -bottom-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-white text-[#0D5A47] flex items-center justify-center font-black text-2xl shadow-md shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">{{ $user->name }}</h1>
                        <span class="px-2.5 py-0.5 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-100 text-[11px] font-bold">
                            {{ $user->role_badge }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-emerald-100 mt-1">
                        {{ $user->email }} &bull; {{ $user->phone }}
                        @if ($user->campus_id)
                            &bull; NIM/NIP: <b class="text-white">{{ $user->campus_id }}</b>
                        @endif
                    </p>
                </div>
            </div>

            <a href="{{ route('loyalty.index') }}" class="self-start sm:self-center px-4 py-2.5 rounded-xl bg-amber-400 hover:bg-amber-300 text-amber-950 font-bold text-xs shadow-sm transition flex items-center gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ number_format((int) $user->loyalty_points, 0, ',', '.') }} Buana Points</span>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl flex items-center gap-3 text-sm font-semibold">
            <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    @if ($errors->any())
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-xs font-semibold space-y-1">
            @foreach ($errors->all() as $error)
                <div>• {{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        {{-- KOLOM KIRI: EDIT PROFIL AKUN (5 Cols) --}}
        <div class="md:col-span-5 card space-y-4">
            <div class="pb-3 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-900">Informasi Profil Akun</h2>
                <p class="text-xs text-gray-500 mt-0.5">Lengkapi NIM/NIP UNWAHAS untuk mendapatkan tarif khusus civitas akademika.</p>
            </div>

            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="mt-0">Nama Lengkap *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="40">
                </div>

                <div>
                    <label for="email" class="mt-0">Alamat Email *</label>
                    <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" required maxlength="50">
                </div>

                <div>
                    <label for="phone" class="mt-0">Nomor WhatsApp *</label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" required maxlength="15" placeholder="081234567890">
                </div>

                <div>
                    <label for="campus_id" class="mt-0">NIM / NIP UNWAHAS (Opsional)</label>
                    <input type="text" name="campus_id" id="campus_id" value="{{ old('campus_id', $user->campus_id) }}" maxlength="20" placeholder="Kosongkan jika pelanggan umum">
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <label for="password" class="mt-0">Kata Sandi Baru (Opsional)</label>
                    <input type="password" name="password" id="password" placeholder="Kosongkan jika tidak ingin mengganti">
                </div>

                <div>
                    <label for="password_confirmation" class="mt-0">Konfirmasi Kata Sandi Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi baru">
                </div>

                <button type="submit" class="btn" style="margin-top: 12px;">
                    Simpan Perubahan Profil
                </button>
            </form>
        </div>

        {{-- KOLOM KANAN: BUKU ALAMAT PENGIRIMAN (7 Cols) --}}
        <div class="md:col-span-7 space-y-5">
            {{-- Daftar Alamat Tersimpan --}}
            <div class="card space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <h2 class="text-base font-bold text-gray-900">Buku Alamat Pengiriman</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Alamat Utama otomatis terisi saat Anda memesan Air Mineral & Katering.</p>
                    </div>
                    <span class="px-2.5 py-1 rounded-full bg-emerald-50 text-[#0D5A47] text-xs font-bold">
                        {{ $addresses->count() }} Alamat
                    </span>
                </div>

                @if ($addresses->isEmpty())
                    <div class="p-6 text-center bg-slate-50 rounded-2xl border border-dashed border-gray-200 text-xs text-gray-500">
                        <div class="font-bold text-gray-700">Belum Ada Alamat Tersimpan</div>
                        <p class="mt-0.5">Tambahkan alamat kantor fakultas, asrama, atau rumah Anda di formulir di bawah ini.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach ($addresses as $addr)
                            <div class="p-4 rounded-2xl border {{ $addr->is_main ? 'border-emerald-500 bg-emerald-50/40' : 'border-gray-200 bg-white' }} flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $addr->is_main ? 'bg-[#0D5A47] text-white' : 'bg-gray-100 text-gray-700' }}">
                                            {{ $addr->label }}
                                        </span>
                                        @if ($addr->is_main)
                                            <span class="text-[11px] font-bold text-emerald-700">Alamat Utama</span>
                                        @endif
                                    </div>
                                    <div class="text-xs font-bold text-gray-900">{{ $addr->receiver }} ({{ $addr->phone }})</div>
                                    <div class="text-xs text-gray-600">{{ $addr->detail }}</div>
                                </div>

                                <div class="flex items-center gap-2 shrink-0">
                                    @if (! $addr->is_main)
                                        <form method="POST" action="{{ route('account.addresses.main', $addr) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#0D5A47] text-xs font-bold border border-emerald-200 cursor-pointer">
                                                Jadikan Utama
                                            </button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('account.addresses.destroy', $addr) }}" onsubmit="return confirm('Hapus alamat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold border border-red-200 cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Form Tambah Alamat Baru --}}
            <div class="card space-y-4">
                <h3 class="text-sm font-bold text-gray-900">Tambah Alamat Pengiriman Baru</h3>

                <form method="POST" action="{{ route('account.addresses.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label for="addr_label" class="mt-0">Label Lokasi *</label>
                            <input type="text" name="label" id="addr_label" required maxlength="15" placeholder="Kampus / Kantor / Rumah">
                        </div>
                        <div>
                            <label for="addr_receiver" class="mt-0">Nama Penerima / Unit *</label>
                            <input type="text" name="receiver" id="addr_receiver" required maxlength="40" value="{{ $user->name }}" placeholder="Nama penerima">
                        </div>
                    </div>

                    <div>
                        <label for="addr_phone" class="mt-0">Nomor WhatsApp Penerima *</label>
                        <input type="tel" name="phone" id="addr_phone" required maxlength="15" value="{{ $user->phone }}" placeholder="081234567890">
                    </div>

                    <div>
                        <label for="addr_detail" class="mt-0">Alamat Lengkap / Gedung & Ruangan *</label>
                        <textarea name="detail" id="addr_detail" rows="2" required maxlength="150" placeholder="Contoh: Gedung Dekanat Fakultas Farmasi Lt. 2, Jl. Menoreh Tengah X No. 22"></textarea>
                    </div>

                    <label class="flex items-center gap-2 text-xs font-bold text-gray-700 cursor-pointer m-0">
                        <input type="checkbox" name="is_main" value="1" class="w-4 h-4 text-[#0D5A47] rounded">
                        <span>Jadikan sebagai Alamat Utama (otomatis terisi saat checkout)</span>
                    </label>

                    <button type="submit" class="btn" style="margin-top: 8px;">
                        Simpan Alamat Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
