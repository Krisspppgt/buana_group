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
            --brand: #0D5A47;
            --muted: #64748b;
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

        /* Enhanced styling for all business unit modules (Web & Mobile) */
        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.05), 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1px solid #e2e8f0;
            transition: box-shadow 0.2s ease, border-color 0.2s ease;
        }
        .card:hover {
            border-color: #cbd5e1;
        }
        .sub {
            color: var(--muted);
            font-size: 0.88rem;
            line-height: 1.45;
        }
        .eco-banner {
            display: flex;
            align-items: center;
            gap: 12px;
            background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 14px 16px;
            border-radius: 16px;
            font-size: 0.88rem;
            box-shadow: 0 2px 10px rgba(16, 185, 129, 0.08);
        }
        .tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-weight: 600;
            white-space: nowrap;
            transition: all 0.15s ease;
        }
        .tag:hover {
            border-color: #0D5A47;
            color: #0D5A47;
        }
        .tag-highlight {
            background: #0D5A47;
            color: #ffffff !important;
            border-color: #0D5A47;
            box-shadow: 0 2px 8px rgba(13, 90, 71, 0.25);
        }
        label { display: block; font-size: 0.85rem; margin: 12px 0 5px; font-weight: 700; color: #334155; }
        input[type=text], input[type=tel], input[type=password], input[type=number], input[type=date], input[type=datetime-local], select, textarea {
            width: 100%; padding: 11px 14px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 0.92rem;
            background: #ffffff; color: #0f172a; transition: all .15s ease;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: var(--buana-primary); box-shadow: 0 0 0 3px rgba(13, 90, 71, 0.14); }
        .err { color: #ef4444; font-size: 0.85rem; margin-top: 4px; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 11px 20px; border: 0; border-radius: 12px;
            background: var(--buana-primary); color: #fff; font-size: 0.92rem; font-weight: 700; text-align: center;
            text-decoration: none; cursor: pointer; transition: all .15s ease; width: 100%;
            box-shadow: 0 2px 6px rgba(13, 90, 71, 0.2);
        }
        .btn:hover { background: var(--buana-dark); transform: translateY(-1px); }
        .btn-ghost { background: #ffffff; color: #475569; border: 1px solid #cbd5e1; box-shadow: none; }
        .btn-ghost:hover { background: #f1f5f9; color: #1e293b; }
        .unit-grid-2col {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            align-items: start;
        }
        @media (min-width: 768px) {
            .unit-grid-2col {
                grid-template-columns: 7fr 5fr;
                gap: 24px;
            }
            .unit-sticky-col {
                position: sticky;
                top: 90px;
            }
        }
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
    {{-- DESKTOP WEB TOPBAR & NAVIGATION (Modern, Balanced & Mega-Dropdown)       --}}
    {{-- ========================================================================= --}}
    @php
        $isUnitRoute = str_starts_with($currentRoute, 'water.')
            || str_starts_with($currentRoute, 'food.')
            || str_starts_with($currentRoute, 'tour.')
            || str_starts_with($currentRoute, 'ticketing.')
            || str_starts_with($currentRoute, 'konveksi.');
    @endphp
    <header class="hidden md:block bg-gradient-to-r from-[#094234] via-[#0D5A47] to-[#0a4d3c] text-white border-b border-emerald-800/80 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-18 flex justify-between items-center gap-4">
            {{-- 1. Brand Identity --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                <div class="w-10 h-10 rounded-xl bg-white/15 border border-white/25 flex items-center justify-center shadow-sm group-hover:bg-white/25 transition">
                    <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9c0 4.97 4.03 9 9 9 4.97 0 9-4.03 9-9 0-4.97-4.03-9-9-9-4.97 0-9 4.03-9 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-base font-extrabold text-white leading-none tracking-tight flex items-center gap-2">
                        <span>Buana Group</span>
                        <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-emerald-400/20 text-emerald-200 border border-emerald-400/30 rounded-full">Super App</span>
                    </div>
                    <div class="text-[11px] text-emerald-200/85 font-medium leading-tight mt-1">PT Buana Sejahtera &bull; UNWAHAS</div>
                </div>
            </a>

            {{-- 2. Center Pill Navigation Bar --}}
            <nav class="flex items-center gap-1 bg-emerald-950/45 p-1.5 rounded-2xl border border-emerald-700/50 text-xs font-semibold">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $currentRoute === 'home' ? 'bg-white text-[#0D5A47] font-extrabold shadow-xs' : 'text-emerald-100 hover:text-white hover:bg-white/10' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>Beranda</span>
                </a>

                {{-- Dropdown 5 Unit Usaha (Hover Bridge + Click Support) --}}
                <div class="relative group" id="desktop-unit-dropdown-wrapper">
                    <button type="button"
                        onclick="toggleDesktopUnitDropdown(event)"
                        class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 cursor-pointer focus:outline-none {{ $isUnitRoute ? 'bg-white text-[#0D5A47] font-extrabold shadow-xs' : 'text-emerald-100 hover:text-white hover:bg-white/10' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span>5 Unit Usaha</span>
                        <svg class="w-3.5 h-3.5 opacity-80 group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    {{-- Invisible Hover Bridge (pt-2.5) prevents mouseleave gap! --}}
                    <div id="desktop-unit-dropdown-menu" class="absolute top-full left-1/2 -translate-x-1/2 pt-2.5 w-88 hidden group-hover:block z-50">
                        <div class="bg-white border border-gray-200/90 rounded-3xl shadow-2xl p-3 text-gray-800 ring-1 ring-black/5">
                            <div class="px-3 py-2 border-b border-gray-100 flex items-center justify-between">
                                <span class="text-[11px] font-extrabold uppercase tracking-wider text-gray-400">Modul Unit Bisnis Utama</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-50 text-[#0D5A47]">5 Layanan Aktif</span>
                            </div>

                            <div class="mt-1.5 space-y-1">
                                <a href="{{ route('water.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-sky-50/80 transition group/item {{ str_starts_with($currentRoute, 'water.') ? 'bg-sky-50/70 ring-1 ring-sky-200' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-lg shrink-0 group-hover/item:scale-105 transition">💧</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-gray-900 group-hover/item:text-sky-800">Air Mineral Unwahas</div>
                                        <div class="text-[11px] text-gray-500 truncate">Galon 19L, botol, langganan & B2B bulk</div>
                                    </div>
                                    <span class="text-gray-300 group-hover/item:text-sky-600 group-hover/item:translate-x-0.5 transition">&rarr;</span>
                                </a>

                                <a href="{{ route('food.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-amber-50/80 transition group/item {{ str_starts_with($currentRoute, 'food.') ? 'bg-amber-50/70 ring-1 ring-amber-200' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg shrink-0 group-hover/item:scale-105 transition">🍱</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-gray-900 group-hover/item:text-amber-800">Buana Food Services</div>
                                        <div class="text-[11px] text-gray-500 truncate">Snack box, nasi kotak, kalkulator & rundown</div>
                                    </div>
                                    <span class="text-gray-300 group-hover/item:text-amber-600 group-hover/item:translate-x-0.5 transition">&rarr;</span>
                                </a>

                                <a href="{{ route('tour.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-emerald-50/80 transition group/item {{ str_starts_with($currentRoute, 'tour.') ? 'bg-emerald-50/70 ring-1 ring-emerald-200' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-[#0D5A47] flex items-center justify-center text-lg shrink-0 group-hover/item:scale-105 transition">✈️</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-gray-900 group-hover/item:text-[#0D5A47]">Buana Tour & Travel</div>
                                        <div class="text-[11px] text-gray-500 truncate">Wisata, ziarah wali, KKL & cicilan Umrah</div>
                                    </div>
                                    <span class="text-gray-300 group-hover/item:text-[#0D5A47] group-hover/item:translate-x-0.5 transition">&rarr;</span>
                                </a>

                                <a href="{{ route('ticketing.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-indigo-50/80 transition group/item {{ str_starts_with($currentRoute, 'ticketing.') ? 'bg-indigo-50/70 ring-1 ring-indigo-200' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-lg shrink-0 group-hover/item:scale-105 transition">🎫</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-gray-900 group-hover/item:text-indigo-800">Buana Ticketing</div>
                                        <div class="text-[11px] text-gray-500 truncate">Kereta, pesawat, bus, kapal & event kampus</div>
                                    </div>
                                    <span class="text-gray-300 group-hover/item:text-indigo-600 group-hover/item:translate-x-0.5 transition">&rarr;</span>
                                </a>

                                <a href="{{ route('konveksi.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-pink-50/80 transition group/item {{ str_starts_with($currentRoute, 'konveksi.') ? 'bg-pink-50/70 ring-1 ring-pink-200' : '' }}">
                                    <div class="w-10 h-10 rounded-xl bg-pink-100 text-pink-700 flex items-center justify-center text-lg shrink-0 group-hover/item:scale-105 transition">👕</div>
                                    <div class="min-w-0 flex-1">
                                        <div class="text-xs font-extrabold text-gray-900 group-hover/item:text-pink-800">Buana Konveksi</div>
                                        <div class="text-[11px] text-gray-500 truncate">Jas almamater, PDH/PDL & live tracking jahit</div>
                                    </div>
                                    <span class="text-gray-300 group-hover/item:text-pink-600 group-hover/item:translate-x-0.5 transition">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('order.campus.rincian') }}" class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $currentRoute === 'order.campus.rincian' ? 'bg-white text-[#0D5A47] font-extrabold shadow-xs' : 'text-emerald-100 hover:text-white hover:bg-white/10' }}">
                    <span>🍱</span>
                    <span>Paket Rapat</span>
                </a>

                <a href="{{ route('order.campus.detail') }}" class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $currentRoute === 'order.campus.detail' ? 'bg-white text-[#0D5A47] font-extrabold shadow-xs' : 'text-emerald-100 hover:text-white hover:bg-white/10' }}">
                    <span>📦</span>
                    <span>Lacak Pesanan</span>
                </a>
            </nav>

            {{-- 3. Right: User Profile, Loyalty Points, Admin Shortcut & Logout --}}
            <div class="flex items-center gap-2 shrink-0">
                @auth
                    <a href="{{ route('loyalty.index') }}" class="px-3 py-2 bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200 font-extrabold text-xs rounded-xl transition shadow-2xs flex items-center gap-1.5" title="Dompet Buana Loyalty Points">
                        <span>🪙</span>
                        <span>{{ number_format((int) ($user->loyalty_points ?? 0), 0, ',', '.') }} Poin</span>
                    </a>

                    @if ($user->isAdmin())
                        <a href="/admin" class="px-3 py-2 bg-amber-400 hover:bg-amber-300 text-amber-950 font-extrabold text-xs rounded-xl transition shadow-sm flex items-center gap-1.5">
                            <span>📊</span>
                            <span>Admin</span>
                        </a>
                    @endif

                    <div class="flex items-center bg-emerald-950/50 border border-emerald-700/60 rounded-2xl p-1 pl-2.5 gap-2.5">
                        <div class="flex items-center gap-2">
                            <span class="w-7 h-7 rounded-xl bg-white text-[#0D5A47] flex items-center justify-center font-extrabold text-xs shadow-2xs">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                            <div class="text-left pr-1">
                                <span class="font-bold text-white text-xs block leading-none max-w-28 truncate">{{ $user->name }}</span>
                                <span class="text-[10px] text-emerald-300 font-semibold block leading-none mt-1">{{ $user->role_badge }}</span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl bg-white/10 hover:bg-red-500 text-emerald-100 hover:text-white transition cursor-pointer" title="Keluar (Logout)">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-white text-[#0D5A47] font-bold text-xs rounded-xl hover:bg-emerald-50 transition shadow-xs">
                        Masuk Akun
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <script>
        function toggleDesktopUnitDropdown(e) {
            e.stopPropagation();
            const menu = document.getElementById('desktop-unit-dropdown-menu');
            if (menu) {
                menu.classList.toggle('!block');
            }
        }
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('desktop-unit-dropdown-wrapper');
            const menu = document.getElementById('desktop-unit-dropdown-menu');
            if (wrapper && menu && !wrapper.contains(e.target)) {
                menu.classList.remove('!block');
            }
        });
    </script>

    {{-- ========================================================================= --}}
    {{-- MOBILE STICKY TOP HEADER (Modern Super App Header untuk Smartphone < md) --}}
    {{-- ========================================================================= --}}
    @php
        $isCustomFullPage = in_array($currentRoute, ['home', 'order.campus.rincian', 'order.campus.detail', 'mockup.showcase'], true);
    @endphp
    @if (! $isCustomFullPage)
        <div class="md:hidden w-full bg-[#0D5A47] text-white px-4 py-3 flex justify-between items-center sticky top-0 z-50 shadow-md">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18m-9-9c0 4.97 4.03 9 9 9 4.97 0 9-4.03 9-9 0-4.97-4.03-9-9-9-4.97 0-9 4.03-9 9z" />
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-bold leading-none">Buana Group</div>
                    <div class="text-[10px] text-emerald-200 mt-0.5 leading-none">Super App • UNWAHAS</div>
                </div>
            </a>
            <div class="flex items-center gap-1.5">
                @auth
                    <a href="{{ route('loyalty.index') }}" class="px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-200 font-extrabold text-[11px] rounded-full shadow-2xs flex items-center gap-1">
                        <span>🪙</span>
                        <span>{{ number_format((int) ($user->loyalty_points ?? 0), 0, ',', '.') }}</span>
                    </a>
                    @if ($user->isAdmin())
                        <a href="/admin" class="px-2.5 py-1 bg-amber-400 text-amber-950 font-extrabold text-[11px] rounded-full shadow-2xs">
                            📊 Admin
                        </a>
                    @endif
                @endauth
            </div>
        </div>
    @endif

    {{-- Dynamic Content Area --}}
    <main class="flex-1 w-full">
        @if ($isCustomFullPage)
            @yield('content')
        @else
            <div class="max-w-5xl mx-auto px-4 sm:px-6 pt-5 pb-28 md:py-8">
                @yield('content')
            </div>
        @endif
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
    {{-- MOBILE BOTTOM NAVIGATION (Persis Mockup Slide 8 + Menu Cepat 5 Unit)      --}}
    {{-- ========================================================================= --}}
    <nav class="md:hidden fixed bottom-0 left-0 right-0 border-t border-gray-200 pt-2 pb-1.5 bg-white/95 backdrop-blur-md z-50 shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
        <div class="flex justify-around items-center text-[11px]">
            {{-- 1. Beranda --}}
            <a href="{{ route('home') }}" class="flex flex-col items-center gap-1 {{ $currentRoute == 'home' ? 'text-[#0D5A47] font-bold' : 'text-gray-400 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Beranda</span>
            </a>

            {{-- 2. Layanan 5 Unit --}}
            <button type="button" onclick="document.getElementById('mobile-units-menu').classList.toggle('hidden'); document.getElementById('mobile-account-menu')?.classList.add('hidden');" class="flex flex-col items-center gap-1 {{ str_starts_with($currentRoute, 'water.') || str_starts_with($currentRoute, 'food.') || str_starts_with($currentRoute, 'tour.') || str_starts_with($currentRoute, 'ticketing.') || str_starts_with($currentRoute, 'konveksi.') ? 'text-[#0D5A47] font-bold' : 'text-gray-400 font-medium' }} cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                <span>5 Unit</span>
            </button>

            {{-- 3. Pesanan --}}
            <a href="{{ route('order.campus.detail') }}" class="flex flex-col items-center gap-1 {{ ($currentRoute == 'order.campus.detail' || $currentRoute == 'order.campus.rincian') ? 'text-[#0D5A47] font-bold' : 'text-gray-400 font-medium' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Pesanan</span>
            </a>

            {{-- 4. Akun --}}
            <button type="button" onclick="document.getElementById('mobile-account-menu')?.classList.toggle('hidden'); document.getElementById('mobile-units-menu').classList.add('hidden');" class="flex flex-col items-center gap-1 text-gray-400 hover:text-[#0D5A47] font-medium cursor-pointer focus:outline-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Akun</span>
            </button>
        </div>

        {{-- Mobile 5 Units Quick Sheet --}}
        <div id="mobile-units-menu" class="hidden absolute bottom-full left-3 right-3 mb-2.5 bg-white rounded-3xl shadow-2xl border border-gray-200 p-4 text-left z-50 ring-1 ring-black/5">
            <div class="flex justify-between items-center pb-2.5 mb-2.5 border-b border-gray-100">
                <div>
                    <span class="text-xs font-extrabold text-gray-900 block">Modul Unit Usaha Utama</span>
                    <span class="text-[10px] text-gray-400">Pilih layanan ekosistem Buana Group</span>
                </div>
                <button type="button" onclick="document.getElementById('mobile-units-menu').classList.add('hidden')" class="w-7 h-7 rounded-full bg-gray-100 text-gray-500 hover:text-gray-800 flex items-center justify-center text-sm font-bold">&times;</button>
            </div>
            <div class="grid grid-cols-1 gap-1.5 text-xs">
                <a href="{{ route('water.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-sky-50 text-gray-800 transition {{ str_starts_with($currentRoute, 'water.') ? 'bg-sky-50 ring-1 ring-sky-200' : '' }}">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center text-base shrink-0">💧</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold text-gray-900 text-xs">Air Mineral Unwahas</div>
                        <div class="text-[10px] text-gray-500 truncate">Galon 19L, botol, langganan & B2B bulk</div>
                    </div>
                    <span class="text-gray-400">&rarr;</span>
                </a>
                <a href="{{ route('food.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-amber-50 text-gray-800 transition {{ str_starts_with($currentRoute, 'food.') ? 'bg-amber-50 ring-1 ring-amber-200' : '' }}">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-base shrink-0">🍱</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold text-gray-900 text-xs">Buana Food Services</div>
                        <div class="text-[10px] text-gray-500 truncate">Snack box, nasi kotak & rundown acara</div>
                    </div>
                    <span class="text-gray-400">&rarr;</span>
                </a>
                <a href="{{ route('tour.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-emerald-50 text-gray-800 transition {{ str_starts_with($currentRoute, 'tour.') ? 'bg-emerald-50 ring-1 ring-emerald-200' : '' }}">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-[#0D5A47] flex items-center justify-center text-base shrink-0">✈️</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold text-gray-900 text-xs">Buana Tour & Travel</div>
                        <div class="text-[10px] text-gray-500 truncate">Wisata, ziarah wali & cicilan Umrah</div>
                    </div>
                    <span class="text-gray-400">&rarr;</span>
                </a>
                <a href="{{ route('ticketing.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-indigo-50 text-gray-800 transition {{ str_starts_with($currentRoute, 'ticketing.') ? 'bg-indigo-50 ring-1 ring-indigo-200' : '' }}">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center text-base shrink-0">🎫</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold text-gray-900 text-xs">Buana Ticketing</div>
                        <div class="text-[10px] text-gray-500 truncate">Kereta, pesawat, bus, kapal & event</div>
                    </div>
                    <span class="text-gray-400">&rarr;</span>
                </a>
                <a href="{{ route('konveksi.index') }}" class="flex items-center gap-3 p-2.5 rounded-2xl hover:bg-pink-50 text-gray-800 transition {{ str_starts_with($currentRoute, 'konveksi.') ? 'bg-pink-50 ring-1 ring-pink-200' : '' }}">
                    <div class="w-9 h-9 rounded-xl bg-pink-100 text-pink-700 flex items-center justify-center text-base shrink-0">👕</div>
                    <div class="flex-1 min-w-0">
                        <div class="font-extrabold text-gray-900 text-xs">Buana Konveksi</div>
                        <div class="text-[10px] text-gray-500 truncate">Jas almamater, PDH & live tracking jahit</div>
                    </div>
                    <span class="text-gray-400">&rarr;</span>
                </a>
            </div>
        </div>

        {{-- Mobile Account Popover (Admin & Logout) --}}
        @auth
        <div id="mobile-account-menu" class="hidden absolute bottom-full right-3 mb-2 w-64 bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 text-left z-50">
            <div class="flex items-center gap-2.5 pb-3 border-b border-gray-100">
                <span class="w-9 h-9 rounded-full bg-[#0D5A47] text-white flex items-center justify-center font-bold text-xs shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-gray-900 truncate">{{ $user->name }}</p>
                    <p class="text-[11px] text-emerald-700 font-semibold truncate">{{ $user->role_badge }}</p>
                </div>
            </div>
            <a href="{{ route('loyalty.index') }}" class="mt-2.5 w-full flex items-center justify-between py-2 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-950 border border-amber-200 text-xs font-extrabold transition">
                <span class="flex items-center gap-1.5"><span>🪙</span><span>Buana Loyalty Points</span></span>
                <span>{{ number_format((int) ($user->loyalty_points ?? 0), 0, ',', '.') }} Poin &rarr;</span>
            </a>
            @if ($user->isAdmin())
                <a href="/admin" class="mt-2 w-full flex items-center justify-center gap-2 py-2 px-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-[#0D5A47] border border-emerald-200 text-xs font-bold transition">
                    <span>📊</span>
                    <span>Buka Dashboard Admin</span>
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
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

    @livewireScripts
</body>
</html>
