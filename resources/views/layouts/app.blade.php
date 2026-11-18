<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#0D5A47">
    <title>@yield('title', 'Buana Group - PT Buana Sejahtera Semarang | Yayasan Wahid Hasyim')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --buana-primary: #0D5A47;
            --buana-dark: #084032;
            --buana-light: #e6f4ea;
            --buana-bg: #f8fafc;
            --buana-text: #0f172a;
            --buana-border: #e2e8f0;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f8fafc;
            color: var(--buana-text);
            line-height: 1.5;
            -webkit-tap-highlight-color: transparent;
        }

        /* Compatibility styling for existing sub-modules */
        .card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,.04);
            border: 1px solid var(--buana-border);
        }
        label { display: block; font-size: 0.88rem; margin: 12px 0 4px; font-weight: 600; color: #334155; }
        input[type=text], input[type=tel], input[type=password], select, textarea {
            width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 0.95rem;
            transition: border-color .15s ease;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--buana-primary); box-shadow: 0 0 0 3px rgba(13, 90, 71, 0.15); }
        .err { color: #ef4444; font-size: 0.85rem; margin-top: 4px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; border: 0; border-radius: 10px;
            background: var(--buana-primary); color: #fff; font-size: 0.92rem; font-weight: 600; text-align: center;
            text-decoration: none; cursor: pointer; transition: all .15s ease;
        }
        .btn:hover { background: var(--buana-dark); }
        .btn-ghost { background: transparent; color: #475569; border: 1px solid #cbd5e1; }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }
        .badge {
            font-size: 0.75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px;
            display: inline-flex; align-items: center; gap: 4px;
        }
        .badge-civitas { background: #dbeafe; color: #1e40af; }
        .badge-admin { background: #fef3c7; color: #92400e; }
        .badge-customer { background: #f1f5f9; color: #475569; }
        .badge-courier { background: #f3e8ff; color: #6b21a8; }
        .points-pill {
            display: inline-flex; align-items: center; gap: 6px; background: #fffbeb;
            border: 1px solid #fde68a; color: #b45309; font-size: 0.8rem; font-weight: 600;
            padding: 4px 10px; border-radius: 12px;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-slate-50 antialiased">

    @php 
        $currentRoute = request()->route()?->getName() ?? '';
        $user = auth()->user();
    @endphp

    {{-- ========================================================================= --}}
    {{-- DESKTOP WEB TOPBAR & NAVIGATION (Berdasarkan Dokumen PDF Konsep Buana)   --}}
    {{-- ========================================================================= --}}
    <header class="hidden md:block bg-[#0D5A47] text-white border-b border-emerald-800/80 shadow-md sticky top-0 z-50">
        {{-- Main Desktop Navigation Bar --}}
        <div class="max-w-7xl mx-auto px-6 py-3.5 flex justify-between items-center">
            {{-- Brand Identity --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center shadow-xs group-hover:scale-105 transition">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9c0 4.97 4.03 9 9 9 4.97 0 9-4.03 9-9 0-4.97-4.03-9-9-9-4.97 0-9 4.03-9 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-lg font-bold text-white leading-none tracking-tight">Buana Group</div>
                    <div class="text-xs text-emerald-200 font-medium leading-tight mt-0.5">Indonesian Buana Group &bull; Platform Terpadu</div>
                </div>
            </a>

            {{-- Segmented Mode Toggle (UNWAHAS vs Pasar Umum) --}}
            <div class="bg-emerald-950/60 p-1 rounded-full border border-emerald-700/60 flex items-center gap-1 text-xs font-semibold">
                <button type="button" class="px-4 py-1.5 rounded-full bg-white text-[#0D5A47] font-bold shadow-xs">
                    🎓 UNWAHAS (Katalog Institusi)
                </button>
                <button type="button" class="px-4 py-1.5 rounded-full text-emerald-200 hover:text-white transition">
                    🌐 Pasar Umum
                </button>
            </div>

            {{-- Navigation Links --}}
            <nav class="flex items-center gap-6 text-sm font-semibold text-emerald-100">
                <a href="{{ route('home') }}" class="hover:text-white transition {{ $currentRoute == 'home' ? 'text-white border-b-2 border-emerald-300 pb-1 font-bold' : '' }}">
                    Beranda
                </a>
                <div class="relative group cursor-pointer">
                    <span class="hover:text-white flex items-center gap-1 transition">
                        5 Unit Usaha
                        <svg class="w-3.5 h-3.5 text-emerald-300 group-hover:text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                    {{-- Dropdown Menu --}}
                    <div class="absolute top-full left-0 mt-2 w-56 bg-white border border-gray-200 rounded-2xl shadow-xl py-2 hidden group-hover:block z-50 text-gray-800">
                        <a href="{{ route('water.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-[#0D5A47]">
                            <span>💧</span> <span>Air Mineral Unwahas</span>
                        </a>
                        <a href="{{ route('food.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-[#0D5A47]">
                            <span>🍱</span> <span>Buana Food Services</span>
                        </a>
                        <a href="{{ route('tour.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-[#0D5A47]">
                            <span>✈️</span> <span>Buana Tour & Travel</span>
                        </a>
                        <a href="{{ route('ticketing.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-[#0D5A47]">
                            <span>🎫</span> <span>Buana Ticketing</span>
                        </a>
                        <a href="{{ route('konveksi.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs text-gray-700 hover:bg-emerald-50 hover:text-[#0D5A47]">
                            <span>👕</span> <span>Buana Konveksi</span>
                        </a>
                    </div>
                </div>
                <a href="{{ route('order.campus.rincian') }}" class="hover:text-white transition {{ $currentRoute == 'order.campus.rincian' ? 'text-white border-b-2 border-emerald-300 pb-1 font-bold' : '' }}">
                    Pesan Paket Rapat
                </a>
                <a href="{{ route('order.campus.detail') }}" class="hover:text-white transition {{ $currentRoute == 'order.campus.detail' ? 'text-white border-b-2 border-emerald-300 pb-1 font-bold' : '' }}">
                    Lacak Pesanan
                </a>
            </nav>

            {{-- Right: User Info / Action --}}
            <div class="flex items-center gap-3">
                @auth
                    @if ($user->isAdmin())
                        <a href="/admin" class="px-3.5 py-1.5 bg-amber-400 hover:bg-amber-300 text-amber-950 font-extrabold text-xs rounded-full transition shadow-xs flex items-center gap-1.5">
                            <span>📊</span>
                            <span>Dashboard Admin</span>
                        </a>
                    @endif
                    <div class="flex items-center gap-2.5 bg-emerald-950/60 border border-emerald-700/60 rounded-full px-3 py-1.5 text-xs text-white">
                        <span class="w-6 h-6 rounded-full bg-white text-[#0D5A47] flex items-center justify-center font-bold text-[10px]">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                        <div class="text-left">
                            <span class="font-bold text-white block leading-tight">{{ $user->name }}</span>
<<<<<<< Updated upstream
                            <span class="text-[10px] text-emerald-300 font-semibold block leading-tight">{{ $user->role?->label() ?? 'Pengguna' }}</span>
=======
                            <span class="text-[10px] text-emerald-300 font-semibold block leading-tight">{{ $user->role_badge }}</span>
>>>>>>> Stashed changes
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-1.5 px-3 py-2 rounded-full bg-white/10 hover:bg-red-500/90 border border-white/20 text-white text-xs font-semibold transition cursor-pointer" title="Keluar dari akun">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-white text-[#0D5A47] font-bold text-xs rounded-xl hover:bg-emerald-50 transition shadow-xs">
                        Masuk Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ========================================================================= --}}
    {{-- MOBILE STATUS BAR (Persis Mockup Slide 8 untuk Layar Smartphone < md)    --}}
    {{-- ========================================================================= --}}
    <div class="md:hidden w-full bg-white px-5 pt-2.5 pb-1 flex justify-between items-center text-xs font-semibold text-gray-800 select-none border-b border-gray-100 sticky top-0 z-50">
        <span>10.24</span>
        <div class="flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 4C7.31 4 3.07 5.9 0 8.98L12 21 24 8.98C20.93 5.9 16.69 4 12 4z"/></svg>
            <span>92%</span>
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M17 5v14a2 2 0 0 1-2 2H9a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2z"/></svg>
        </div>
    </div>

    {{-- Dynamic Content Area --}}
    <main class="flex-1 w-full">
        @yield('content')
    </main>

    {{-- ========================================================================= --}}
    {{-- DESKTOP WEB FOOTER (Referensi PDF: PT Buana Sejahtera Semarang & UNWAHAS) --}}
    {{-- ========================================================================= --}}
    <footer class="hidden md:block bg-white border-t border-gray-200 mt-16 text-gray-600 text-xs py-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-4 gap-8">
            <div class="col-span-1">
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-[#0D5A47] flex items-center justify-center text-white font-bold">B</div>
                    <span class="font-bold text-sm text-[#0D5A47]">PT Buana Sejahtera Semarang</span>
                </div>
                <p class="text-gray-500 leading-relaxed">
                    Badan usaha di bawah naungan Yayasan Wahid Hasyim yang mengintegrasikan layanan kebutuhan logistik kampus dan pasar komersial luas.
                </p>
                <div class="mt-4 text-[11px] text-gray-400">
                    &copy; {{ date('Y') }} Buana Group. Hak cipta dilindungi.
                </div>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 mb-3 uppercase tracking-wider text-[11px]">Unit Bisnis Utama</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('water.index') }}" class="hover:text-[#0D5A47]">Air Mineral Unwahas</a></li>
                    <li><a href="{{ route('food.index') }}" class="hover:text-[#0D5A47]">Buana Food Services (Katering)</a></li>
                    <li><a href="{{ route('tour.index') }}" class="hover:text-[#0D5A47]">Buana Tour & Travel (Wisata & Umrah)</a></li>
                    <li><a href="{{ route('ticketing.index') }}" class="hover:text-[#0D5A47]">Buana Ticketing (Transport & Event)</a></li>
                    <li><a href="{{ route('konveksi.index') }}" class="hover:text-[#0D5A47]">Buana Konveksi (Almamater & PDH)</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 mb-3 uppercase tracking-wider text-[11px]">Tata Kelola & Kebijakan</h4>
                <ul class="space-y-2">
                    <li><span class="text-gray-500">Skema Tempo Institusi (Maks 30 Hari)</span></li>
                    <li><span class="text-gray-500">Verifikasi SPK Pejabat Fakultas</span></li>
                    <li><span class="text-gray-500">Kepatuhan UU PDP No. 27 Tahun 2022</span></li>
                    <li><span class="text-gray-500">Standar Pengendalian Risiko & Arus Kas</span></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-900 mb-3 uppercase tracking-wider text-[11px]">Kampus & Operasional</h4>
                <p class="text-gray-500 leading-relaxed">
                    Universitas Wahid Hasyim (UNWAHAS)<br>
                    Jl. Menoreh Tengah X No. 22, Sampangan, Gajahmungkur, Kota Semarang, Jawa Tengah.
                </p>
                <div class="mt-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-[#0D5A47] font-semibold text-[11px] border border-emerald-200">
                        🟢 Sistem Operasional Pilot Aktif
                    </span>
                </div>
            </div>
        </div>
    </footer>

    {{-- ========================================================================= --}}
    {{-- MOBILE BOTTOM NAVIGATION (Persis Mockup Slide 8 untuk Layar Smartphone)   --}}
    {{-- ========================================================================= --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 border-t border-gray-200 pt-2 pb-1 bg-white/95 backdrop-blur-sm z-50 shadow-lg">
        <div class="flex justify-around items-center text-[11px]">
            {{-- 1. Beranda --}}
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ $currentRoute == 'home' ? 'text-[#0D5A47] font-bold' : 'text-gray-400 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
            </a>

            {{-- 2. Pesanan --}}
            <a href="{{ route('order.campus.detail') }}" class="flex flex-col items-center gap-1 {{ ($currentRoute == 'order.campus.detail' || $currentRoute == 'order.campus.rincian') ? 'text-[#0D5A47] font-bold' : 'text-gray-400 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Pesanan</span>
            </a>

            {{-- 3. Akun --}}
            <button type="button" onclick="document.getElementById('mobile-account-menu').classList.toggle('hidden')" class="flex flex-col items-center gap-1 text-gray-400 hover:text-[#0D5A47] font-medium cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Akun</span>
            </button>
        </div>

        {{-- Mobile Account Popover (Logout) --}}
        @auth
        <div id="mobile-account-menu" class="hidden absolute bottom-full right-3 mb-2 w-60 bg-white rounded-2xl shadow-xl border border-gray-200 p-3.5 text-left z-50">
            <div class="flex items-center gap-2.5 pb-2.5 border-b border-gray-100">
                <span class="w-8 h-8 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-gray-500 truncate">{{ $user->role?->label() ?? 'Pengguna' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2.5">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-red-50 hover:bg-red-100 text-red-600 text-xs font-bold transition cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar (Logout)</span>
                </button>
            </form>
        </div>
        @endauth

        {{-- Mobile Indicator Home Bar --}}
        <div class="w-full flex justify-center pt-1 pb-0.5">
            <div class="w-28 h-1 bg-gray-300 rounded-full"></div>
        </div>
    </nav>

</body>
</html>
