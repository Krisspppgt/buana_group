<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta content="web_blank" name="shell-type"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk &amp; Daftar Portal Terpadu - Buana Group | Yayasan Wahid Hasyim</title>

    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

    <style>
        @layer base {
            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
            }
            body {
                overscroll-behavior: none;
            }
            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom, 0px);
            }
            .pt-safe {
                padding-top: env(safe-area-inset-top, 0px);
            }
            main > :first-child {
                margin-top: 0 !important;
            }
            main > :last-child {
                margin-bottom: 0 !important;
            }
        }
        ::-webkit-scrollbar {
            display: none;
        }
    </style>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-secondary-fixed": "#002113",
                        "on-secondary": "#ffffff",
                        "primary-fixed-dim": "#89d7a9",
                        "surface": "#f9f9ff",
                        "surface-container-low": "#f0f3ff",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed": "#d9e6da",
                        "on-background": "#111c2d",
                        "primary-container": "#005e3a",
                        "surface-container-highest": "#d8e3fb",
                        "inverse-on-surface": "#ecf1ff",
                        "tertiary": "#323d35",
                        "secondary-fixed": "#6ffbbe",
                        "surface-container-lowest": "#ffffff",
                        "on-primary": "#ffffff",
                        "surface-dim": "#cfdaf2",
                        "on-secondary-container": "#00714d",
                        "surface-container": "#e7eeff",
                        "surface-tint": "#196b46",
                        "inverse-primary": "#89d7a9",
                        "on-error": "#ffffff",
                        "surface-variant": "#d8e3fb",
                        "on-primary-container": "#87d5a7",
                        "on-primary-fixed-variant": "#005232",
                        "secondary-container": "#6cf8bb",
                        "on-tertiary-fixed": "#131e17",
                        "primary-fixed": "#a4f3c4",
                        "inverse-surface": "#263143",
                        "secondary-fixed-dim": "#4edea3",
                        "on-primary-fixed": "#002111",
                        "on-secondary-fixed-variant": "#005236",
                        "on-error-container": "#93000a",
                        "background": "#f9f9ff",
                        "on-tertiary-fixed-variant": "#3e4a41",
                        "on-tertiary-container": "#bbc8bd",
                        "surface-container-high": "#dee8ff",
                        "on-surface-variant": "#3f4942",
                        "outline-variant": "#bfc9c0",
                        "surface-bright": "#f9f9ff",
                        "error-container": "#ffdad6",
                        "error": "#ba1a1a",
                        "tertiary-fixed-dim": "#bdcabe",
                        "secondary": "#006c49",
                        "primary": "#004429",
                        "outline": "#6f7a71",
                        "on-surface": "#111c2d",
                        "tertiary-container": "#48544b"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "space-xl": "1.75rem",
                        "space-sm": "0.5rem",
                        gutter: "1rem",
                        "space-xs": "0.25rem",
                        margin: "1rem",
                        "space-lg": "1.25rem",
                        "space-md": "1rem"
                    },
                    fontFamily: {
                        "headline-md": ["Plus Jakarta Sans"],
                        "label-md": ["Plus Jakarta Sans"],
                        "title-sm": ["Plus Jakarta Sans"],
                        "headline-lg": ["Plus Jakarta Sans"],
                        "label-sm": ["Plus Jakarta Sans"],
                        "body-sm": ["Plus Jakarta Sans"],
                        "headline-sm": ["Plus Jakarta Sans"],
                        "body-md": ["Plus Jakarta Sans"],
                        "title-md": ["Plus Jakarta Sans"],
                        "body-lg": ["Plus Jakarta Sans"]
                    },
                    fontSize: {
                        "headline-md": ["22px", { lineHeight: "30px", fontWeight: "700" }],
                        "label-md": ["13px", { lineHeight: "18px", fontWeight: "600" }],
                        "title-sm": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "headline-lg": ["30px", { lineHeight: "38px", fontWeight: "700" }],
                        "label-sm": ["11px", { lineHeight: "14px", fontWeight: "500" }],
                        "body-sm": ["12px", { lineHeight: "16px", fontWeight: "400" }],
                        "headline-sm": ["18px", { lineHeight: "24px", fontWeight: "700" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "title-md": ["16px", { lineHeight: "22px", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-[#f0f3f8] font-body-md text-on-surface antialiased selection:bg-primary-container selection:text-white">

    <!-- ========================================================================= -->
    <!-- 1. TAMPILAN DESKTOP / TABLET (Layar Luas >= lg)                          -->
    <!-- ========================================================================= -->
    <main class="hidden lg:flex min-h-screen w-full items-center justify-center p-4 md:p-8 bg-gradient-to-br from-surface-container-low via-[#ebf1fa] to-[#e4ecf7]">
        <div class="flex flex-col w-full">
            <div class="w-full max-w-7xl mx-auto my-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 rounded-2xl overflow-hidden shadow-2xl bg-surface-container-lowest border border-white/80">
                    
                    <!-- Sisi Kiri: Branding & Visual Showcase Portal (Forest Green Theme) -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-primary-container via-primary to-[#002414] p-8 lg:p-12 text-on-primary flex flex-col justify-between relative overflow-hidden">
                        <!-- Subtle Ambient Rings & Shapes -->
                        <div class="absolute -right-24 -top-24 w-88 h-88 rounded-full bg-secondary-fixed/15 blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-20 -bottom-20 w-96 h-96 rounded-full bg-primary-fixed/10 blur-2xl pointer-events-none"></div>

                        <!-- Top Content: Brand & Identity -->
                        <div class="relative z-10 flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <!-- Elemen Logo Buana Group SVG Elegan -->
                                <a href="{{ route('home') }}" class="bg-surface-container-lowest px-4 py-2.5 rounded-xl shadow-md inline-flex items-center gap-3 hover:opacity-95 transition">
                                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-[#005e3a] to-[#003822] flex items-center justify-center shadow-inner flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="currentColor" fill-opacity="0.15"></path>
                                            <path d="M12 3C7.03 3 3 7.03 3 12C3 16.97 7.03 21 12 21C14.76 21 17.22 19.75 18.89 17.78C16.44 17.9 13.9 16.66 12.63 14.39C11.36 12.12 11.66 9.4 13.11 7.47C12.75 7.42 12.38 7.4 12 7.4V3Z" fill="currentColor"></path>
                                            <path d="M15.5 5.5C14.2 6.8 13.5 8.7 13.8 10.7C14.1 12.7 15.3 14.4 17.1 15.3C18 13.8 18.3 12 18 10.2C17.7 8.4 16.7 6.8 15.5 5.5Z" fill="#6ffbbe"></path>
                                        </svg>
                                    </div>
                                    <div class="flex flex-col text-left">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-extrabold text-[15px] tracking-tight text-primary leading-tight font-headline-sm">BUANA GROUP</span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#006c49]"></span>
                                        </div>
                                        <span class="text-[10px] tracking-wider text-outline font-semibold uppercase leading-none">Indonesian Buana Group</span>
                                    </div>
                                </a>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-secondary-fixed/20 border border-secondary-fixed/30 text-secondary-fixed text-label-sm font-label-sm tracking-wide font-medium">
                                    <span class="w-2 h-2 rounded-full bg-secondary-fixed animate-pulse"></span>
                                    PORTAL RESMI
                                </span>
                            </div>

                            <div class="space-y-3 pt-2">
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-surface-container-lowest/15 border border-white/10 text-on-primary text-label-sm font-medium w-fit">
                                    <span class="material-symbols-outlined text-sm text-secondary-fixed">verified</span>
                                    Naungan Yayasan Wahid Hasyim Semarang
                                </div>
                                <h1 class="font-headline-lg text-headline-lg text-on-primary tracking-tight font-bold">Portal Layanan &amp; Bisnis Terpadu Buana Group</h1>
                                <p class="font-body-md text-body-md text-on-primary-container leading-relaxed">Ekosistem pengadaan resmi terintegrasi UNWAHAS Semarang: pemenuhan logistik air mineral galon/kemasan, konsumsi rapat, konveksi seragam, dan biro perjalanan wisata.</p>
                            </div>

                            <!-- Feature Grid -->
                            <div class="grid grid-cols-2 gap-3 pt-2">
                                <a href="{{ route('water.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">water_drop</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-title-sm text-on-primary truncate">Air Mineral Buana</div>
                                        <div class="font-label-sm text-label-sm text-on-primary-container truncate">Galon, Botol &amp; Cup</div>
                                    </div>
                                </a>

                                <a href="{{ route('food.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">restaurant</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-title-sm text-on-primary truncate">Katering &amp; Rapat</div>
                                        <div class="font-label-sm text-label-sm text-on-primary-container truncate">Snack Box &amp; Buffet Dinamis</div>
                                    </div>
                                </a>

                                <a href="{{ route('tour.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">flight_takeoff</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-title-sm text-on-primary truncate">Travel &amp; Wisata</div>
                                        <div class="font-label-sm text-label-sm text-on-primary-container truncate">Tiket, Bus &amp; Studi Banding</div>
                                    </div>
                                </a>

                                <a href="{{ route('konveksi.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">styler</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-title-sm text-on-primary truncate">Konveksi Seragam</div>
                                        <div class="font-label-sm text-label-sm text-on-primary-container truncate">PDH, Kaos &amp; Cinderamata</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Bottom Showcase Card / Testimonial -->
                        <div class="relative z-10 mt-8 pt-4">
                            <div class="bg-surface-container-lowest/10 border border-white/15 backdrop-blur-md rounded-xl p-4 flex flex-col gap-2.5 shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full overflow-hidden bg-surface-container-highest flex-shrink-0 ring-2 ring-secondary-fixed/50">
                                        <img class="w-full h-full object-cover" alt="Dr. H. Sulaiman, M.Si" src="https://lh3.googleusercontent.com/aida-public/AB6AXuC9lkbvZTXPDFFrs7CuFi0FXOrl0KQkt1EHyhJNJcQYHr-RekRNLR_ZLodn-SiLBEFluZQ1kDdIwFLO71lgMkGM6hiCZviCHY1X6EhRwIbuJIagLwshebMwTxHL3Phw7Yiv6cRKjgQ6Inlz8aa7UYzgwSc2fxo8SRnN-t0oz3fz7p6fVO0k8OtwII7JtY9uC2aKyRx3StGOWJVkodPPgNDRdq9iA6oH_bxOHT6LV0E9"/>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-label-md text-label-md text-on-primary font-semibold leading-tight truncate">Dr. H. Sulaiman, M.Si</p>
                                        <p class="font-label-sm text-label-sm text-on-primary-container truncate">Dekan Farmasi UNWAHAS Semarang</p>
                                    </div>
                                    <span class="material-symbols-outlined text-secondary-fixed text-lg" style="font-variation-settings: 'FILL' 1;">verified</span>
                                </div>
                                <p class="font-body-sm text-body-sm text-inverse-on-surface italic line-clamp-2">
                                    "Pemesanan konsumsi rapat fakultas kini transparan, tepat waktu, dan langsung terverifikasi anggaran dinas tanpa ribet."
                                </p>
                            </div>
                            <div class="flex items-center justify-between text-label-sm font-label-sm text-on-primary-container/80 pt-4 text-[11px]">
                                <span>&copy; {{ date('Y') }} PT Buana Riset Mandiri - Yayasan Wahid Hasyim</span>
                                <span>Versi 3.4.2 (Terakreditasi)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Form Autentikasi Interaktif (Login / Register Switcher) -->
                    <div class="lg:col-span-7 p-6 sm:p-8 lg:p-12 bg-surface-container-lowest flex flex-col justify-center">
                        <div class="max-w-xl w-full mx-auto space-y-6">

                            <!-- Header Tab Switcher (Jelas & Menonjol) -->
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h2 class="text-xl sm:text-2xl font-bold text-on-surface tracking-tight" id="form-heading">Selamat Datang di Buana Group</h2>
                                        <p class="text-body-sm text-on-surface-variant" id="form-subheading">Silakan masuk atau buat akun baru untuk memulai transaksi pengadaan.</p>
                                    </div>
                                </div>

                                <!-- Toggle Tabs -->
                                <div class="p-1 rounded-xl bg-surface-container-low border border-surface-container-high grid grid-cols-2 gap-1 mt-3 shadow-inner">
                                    <button class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-white shadow font-semibold" id="tab-login" type="button">
                                        <span class="material-symbols-outlined text-lg">login</span>
                                        <span>Masuk (Login)</span>
                                    </button>
                                    <a href="{{ route('register') }}" class="flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 text-on-surface-variant hover:text-on-surface hover:bg-white/60 font-semibold" id="tab-register">
                                        <span class="material-symbols-outlined text-lg">person_add</span>
                                        <span>Daftar Akun Baru (Register)</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Notifikasi Alert Server (Error / Sukses) -->
                            @if (isset($errors) && $errors->any())
                                <div class="p-3.5 rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2.5 text-xs shadow-xs animate-fadeIn">
                                    <span class="material-symbols-outlined text-lg flex-shrink-0 text-error">error</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-error">Mohon periksa formulir berikut:</div>
                                        <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-error-container">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            @if (session('status') || session('success'))
                                <div class="p-3.5 rounded-xl bg-secondary-fixed/40 border border-secondary text-primary flex items-center gap-2.5 text-xs shadow-xs">
                                    <span class="material-symbols-outlined text-lg flex-shrink-0 text-secondary">check_circle</span>
                                    <span class="font-medium">{{ session('status') ?? session('success') }}</span>
                                </div>
                            @endif

                            <!-- SSO Integrasi Khusus -->
                            <div class="p-3.5 rounded-xl bg-gradient-to-r from-primary-container/10 via-surface-container-low to-secondary-fixed/10 border border-primary/20 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-lg bg-primary-container/15 flex items-center justify-center text-primary flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-title-sm text-title-sm text-on-surface font-bold truncate">SSO Yayasan Wahid Hasyim</p>
                                            <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-primary text-white leading-none">RESMI</span>
                                        </div>
                                        <p class="font-body-sm text-body-sm text-on-surface-variant truncate">Civitas UNWAHAS dapat login instan tanpa daftar ulang</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 w-full sm:w-auto">
                                    <button onclick="showSsoNotice('SSO Yayasan Wahid Hasyim')" class="w-full sm:w-auto px-3.5 py-2 rounded-lg bg-primary-container hover:bg-primary text-white font-label-md text-label-md shadow-sm transition-all whitespace-nowrap flex items-center justify-center gap-1.5 active:scale-95 cursor-pointer" type="button">
                                        <span class="material-symbols-outlined text-sm">login</span>
                                        <span>Masuk SSO</span>
                                    </button>
                                    <button onclick="showSsoNotice('Google Workspace UNWAHAS')" class="px-3 py-2 rounded-lg border border-outline-variant hover:bg-surface-container-low text-on-surface font-label-md text-label-md transition-all flex items-center justify-center gap-1.5 bg-white cursor-pointer" title="Masuk dengan Google" type="button">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"></path><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"></path></svg>
                                        <span class="hidden sm:inline">Google</span>
                                    </button>
                                </div>
                            </div>

                            <div class="relative flex items-center">
                                <div class="flex-grow border-t border-surface-container-high"></div>
                                <span class="flex-shrink mx-3 font-label-sm text-label-sm text-outline font-medium" id="divider-text">atau masuk dengan kredensial portal</span>
                                <div class="flex-grow border-t border-surface-container-high"></div>
                            </div>

                            <!-- VIEW 1: Form Masuk (Login) -->
                            <div id="view-login">
                                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                                    @csrf

                                    <!-- Email / Nomor Telepon -->
                                    <div class="space-y-1.5">
                                        <label class="block font-label-md text-label-md text-on-surface font-semibold" for="login-account">
                                            Email / Nomor Telepon
                                        </label>
                                        <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                <span class="material-symbols-outlined text-xl">alternate_email</span>
                                            </div>
                                            <input class="w-full pl-11 pr-4 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-md text-body-md focus:outline-none border-0" id="login-account" name="phone" value="{{ old('phone', old('account')) }}" placeholder="nama@unwahas.ac.id atau 08123456789" required="" type="text"/>
                                        </div>
                                        @error('phone')
                                            <p class="text-error font-label-sm text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- NIM / NIP Civitas Kampus -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="login-nim-nip">
                                                NIM / NIP Civitas Kampus
                                            </label>
                                            <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-secondary font-semibold bg-secondary/10 px-2 py-0.5 rounded-full">
                                                <span class="material-symbols-outlined text-xs">verified</span> Khusus UNWAHAS (Opsional)
                                            </span>
                                        </div>
                                        <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                <span class="material-symbols-outlined text-xl">badge</span>
                                            </div>
                                            <input class="w-full pl-11 pr-4 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-md text-body-md focus:outline-none border-0" id="login-nim-nip" name="campus_id" value="{{ old('campus_id') }}" placeholder="Masukkan NIM (Mahasiswa) atau NIP (Dosen/Pegawai)" type="text"/>
                                        </div>
                                        <p class="font-label-sm text-label-sm text-outline flex items-center gap-1">
                                            <span class="material-symbols-outlined text-sm text-secondary">info</span> Otomatis mendeteksi tarif khusus civitas &amp; alur SPJ dinas
                                        </p>
                                    </div>

                                    <!-- Kata Sandi -->
                                    <div class="space-y-1.5">
                                        <div class="flex items-center justify-between">
                                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="login-password">
                                                Kata Sandi
                                            </label>
                                            <a class="font-label-sm text-label-sm text-primary hover:underline font-semibold" href="javascript:void(0)" onclick="showForgotNotice()">
                                                Lupa Kata Sandi?
                                            </a>
                                        </div>
                                        <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                <span class="material-symbols-outlined text-xl">lock</span>
                                            </div>
                                            <input class="w-full pl-11 pr-11 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-md text-body-md focus:outline-none border-0" id="login-password" name="password" placeholder="Masukkan kata sandi akun" required="" type="password"/>
                                            <button class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer" onclick="togglePasswordVisibility('login-password', 'eye-login')" type="button">
                                                <span class="material-symbols-outlined text-xl" id="eye-login">visibility</span>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="text-error font-label-sm text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Remember Me & Security Badge -->
                                    <div class="flex items-center justify-between pt-1">
                                        <label class="flex items-center gap-2 cursor-pointer select-none">
                                            <input checked="" class="w-4 h-4 rounded text-primary-container focus:ring-primary accent-[#005e3a]" name="remember" value="1" type="checkbox"/>
                                            <span class="font-body-sm text-body-sm text-on-surface-variant font-medium">Ingat Saya di Perangkat Ini</span>
                                        </label>
                                        <span class="font-label-sm text-label-sm text-secondary flex items-center gap-1 font-medium">
                                            <span class="material-symbols-outlined text-sm">security</span> Terenkripsi TLS 1.3
                                        </span>
                                    </div>

                                    <!-- Primary Submit Button -->
                                    <button class="w-full min-h-[46px] py-3 px-6 rounded-lg bg-primary-container hover:bg-primary text-white font-title-sm text-title-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group active:scale-[0.99] font-bold cursor-pointer" type="submit">
                                        <span>Masuk ke Akun Portal</span>
                                        <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                    </button>

                                    <div class="text-center pt-2">
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Belum punya akun Buana Group? </span>
                                        <a class="font-label-md text-label-md text-primary font-bold hover:underline cursor-pointer" href="{{ route('register') }}">Daftar Akun Baru di sini</a>
                                    </div>
                                </form>
                            </div>

                            <!-- VIEW 2: Form Registrasi (Daftar Akun Baru) -->
                            <div class="hidden" id="view-register">
                                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                                    @csrf

                                    <!-- 1. Pilihan Kategori / Status Pengguna (Peran) -->
                                    <div class="space-y-1.5">
                                        <label class="block font-label-md text-label-md text-on-surface font-semibold">Pilih Kategori / Status Pengguna</label>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                                            <!-- Opsi 1: Civitas UNWAHAS -->
                                            <label class="cursor-pointer relative">
                                                <input checked="" class="peer hidden" name="role_register" onchange="handleRoleSelection('unwahas')" type="radio" value="unwahas"/>
                                                <div class="p-3 rounded-xl border border-surface-container-high bg-surface-container-low peer-checked:border-primary-container peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary-container text-left transition-all h-full flex flex-col justify-between">
                                                    <div class="flex items-center justify-between mb-1.5">
                                                        <span class="w-7 h-7 rounded-lg bg-primary-container/10 text-primary flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-lg">school</span>
                                                        </span>
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-primary text-white">Diskon</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-label-md text-label-md font-bold text-on-surface block leading-tight">Mahasiswa / Dosen</span>
                                                        <span class="text-[11px] text-on-surface-variant block mt-0.5">Civitas Kampus • Akses Harga Khusus &amp; SPJ</span>
                                                    </div>
                                                </div>
                                            </label>
                                            <!-- Opsi 2: Umum (B2C) -->
                                            <label class="cursor-pointer relative">
                                                <input class="peer hidden" name="role_register" onchange="handleRoleSelection('umum')" type="radio" value="umum"/>
                                                <div class="p-3 rounded-xl border border-surface-container-high bg-surface-container-low peer-checked:border-primary-container peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary-container text-left transition-all h-full flex flex-col justify-between">
                                                    <div class="flex items-center justify-between mb-1.5">
                                                        <span class="w-7 h-7 rounded-lg bg-primary-container/10 text-primary flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-lg">person</span>
                                                        </span>
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-secondary/15 text-secondary">B2C</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-label-md text-label-md font-bold text-on-surface block leading-tight">Masyarakat Umum</span>
                                                        <span class="text-[11px] text-on-surface-variant block mt-0.5">Konsumen Publik • Belanja Pribadi &amp; Wisata</span>
                                                    </div>
                                                </div>
                                            </label>
                                            <!-- Opsi 3: Mitra / Instansi (B2B) -->
                                            <label class="cursor-pointer relative">
                                                <input class="peer hidden" name="role_register" onchange="handleRoleSelection('instansi')" type="radio" value="instansi"/>
                                                <div class="p-3 rounded-xl border border-surface-container-high bg-surface-container-low peer-checked:border-primary-container peer-checked:bg-primary/5 peer-checked:ring-1 peer-checked:ring-primary-container text-left transition-all h-full flex flex-col justify-between">
                                                    <div class="flex items-center justify-between mb-1.5">
                                                        <span class="w-7 h-7 rounded-lg bg-primary-container/10 text-primary flex items-center justify-center">
                                                            <span class="material-symbols-outlined text-lg">domain</span>
                                                        </span>
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-tertiary/15 text-tertiary">B2B</span>
                                                    </div>
                                                    <div>
                                                        <span class="font-label-md text-label-md font-bold text-on-surface block leading-tight">Mitra / Instansi</span>
                                                        <span class="text-[11px] text-on-surface-variant block mt-0.5">Badan Usaha, Vendor &amp; Rekanan Dinas</span>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- 2. Formulir Data Diri Utama -->
                                    <div class="space-y-3">
                                        <!-- Nama Lengkap -->
                                        <div class="space-y-1.5">
                                            <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-name">
                                                Nama Lengkap <span class="text-error">*</span>
                                            </label>
                                            <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                    <span class="material-symbols-outlined text-lg">person</span>
                                                </div>
                                                <input class="w-full pl-10 pr-4 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-md text-body-md focus:outline-none border-0" id="reg-name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap sesuai KTP / Identitas" required="" type="text"/>
                                            </div>
                                        </div>

                                        <!-- Grid: Email Aktif & Nomor WhatsApp -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div class="space-y-1.5">
                                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-email">
                                                    Email Aktif <span class="text-error">*</span>
                                                </label>
                                                <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                        <span class="material-symbols-outlined text-lg">mail</span>
                                                    </div>
                                                    <input class="w-full pl-10 pr-3 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-sm text-body-sm focus:outline-none border-0" id="reg-email" name="email" value="{{ old('email') }}" placeholder="nama@domain.ac.id" required="" type="email"/>
                                                </div>
                                                <p class="text-[11px] text-outline">Untuk verifikasi akun &amp; invoice tagihan</p>
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-whatsapp">
                                                    Nomor WhatsApp <span class="text-error">*</span>
                                                </label>
                                                <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                        <span class="material-symbols-outlined text-lg">phone_iphone</span>
                                                    </div>
                                                    <input class="w-full pl-10 pr-3 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-sm text-body-sm focus:outline-none border-0" id="reg-whatsapp" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required="" type="tel"/>
                                                </div>
                                                <p class="text-[11px] text-outline">Wajib untuk notifikasi status pesanan &amp; kiriman</p>
                                            </div>
                                        </div>

                                        <!-- 3. Verifikasi Khusus Civitas Akademika (Kondisional / Field UNWAHAS) -->
                                        <div class="p-3 rounded-xl bg-primary/5 border border-primary/20 space-y-1.5 transition-all" id="civitas-verification-box">
                                            <div class="flex items-center justify-between">
                                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-nim-nip">
                                                    NIM (Mahasiswa) / NIP (Dosen &amp; Pegawai)
                                                </label>
                                                <span class="inline-flex items-center gap-1 font-label-sm text-label-sm text-primary font-bold bg-primary/10 px-2.5 py-0.5 rounded-full border border-primary/20">
                                                    <span class="material-symbols-outlined text-xs">verified</span> Khusus Civitas UNWAHAS
                                                </span>
                                            </div>
                                            <div class="relative rounded-lg bg-white border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                    <span class="material-symbols-outlined text-lg">badge</span>
                                                </div>
                                                <input class="w-full pl-10 pr-4 py-2 bg-transparent text-on-surface placeholder:text-outline/70 font-body-sm text-body-sm focus:outline-none border-0" id="reg-nim-nip" name="campus_id" value="{{ old('campus_id') }}" placeholder="Contoh: 21102001 (NIM) atau 061208... (NIP/NIDN)" type="text"/>
                                            </div>
                                            <p class="text-[11px] text-on-surface-variant flex items-center gap-1">
                                                <span class="material-symbols-outlined text-xs text-secondary">check_circle</span> Mengaktifkan subsidi otomatis &amp; fasilitas faktur instansi internal kampus
                                            </p>
                                        </div>

                                        <!-- 4. Keamanan Akun: Password & Konfirmasi Password -->
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                                            <div class="space-y-1.5">
                                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-password">
                                                    Kata Sandi <span class="text-error">*</span>
                                                </label>
                                                <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                    <input class="w-full pl-3.5 pr-10 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-sm text-body-sm focus:outline-none border-0" id="reg-password" name="password" placeholder="Minimal 8 karakter" required="" type="password"/>
                                                    <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer" onclick="togglePasswordVisibility('reg-password', 'eye-reg-1')" type="button">
                                                        <span class="material-symbols-outlined text-lg" id="eye-reg-1">visibility</span>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="space-y-1.5">
                                                <label class="block font-label-md text-label-md text-on-surface font-semibold" for="reg-password-confirm">
                                                    Konfirmasi Sandi <span class="text-error">*</span>
                                                </label>
                                                <div class="relative rounded-lg bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                    <input class="w-full pl-3.5 pr-10 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 font-body-sm text-body-sm focus:outline-none border-0" id="reg-password-confirm" name="password_confirmation" placeholder="Ulangi kata sandi" required="" type="password"/>
                                                    <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer" onclick="togglePasswordVisibility('reg-password-confirm', 'eye-reg-2')" type="button">
                                                        <span class="material-symbols-outlined text-lg" id="eye-reg-2">visibility</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 5. Persetujuan & Kebijakan -->
                                    <div class="pt-1">
                                        <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                            <input checked="" class="w-4 h-4 mt-0.5 rounded text-primary-container focus:ring-primary accent-[#005e3a]" required="" type="checkbox"/>
                                            <span class="font-body-sm text-body-sm text-on-surface-variant leading-snug">
                                                Saya menyetujui <a class="text-primary font-semibold hover:underline" href="javascript:void(0)" onclick="alert('Syarat & Ketentuan berlaku untuk transaksi resmi di lingkungan Buana Group & Yayasan Wahid Hasyim.')">Syarat &amp; Ketentuan Layanan</a> serta <a class="text-primary font-semibold hover:underline" href="javascript:void(0)" onclick="alert('Data Anda dilindungi sesuai standar UU Perlindungan Data Pribadi (UU PDP).')">Kebijakan Privasi</a> Buana Group Yayasan Wahid Hasyim Semarang.
                                            </span>
                                        </label>
                                    </div>

                                    <!-- Tombol Aksi Utama -->
                                    <button class="w-full min-h-[46px] py-3 px-6 rounded-lg bg-[#005e3a] hover:bg-[#004429] text-white font-title-sm text-title-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group active:scale-[0.99] font-bold cursor-pointer" type="submit">
                                        <span>Daftar Sekarang</span>
                                        <span class="material-symbols-outlined text-xl group-hover:translate-x-1 transition-transform">how_to_reg</span>
                                    </button>

                                    <!-- Teks Navigasi Kembali ke Login -->
                                    <div class="text-center pt-2">
                                        <span class="font-body-sm text-body-sm text-on-surface-variant">Sudah memiliki akun portal? </span>
                                        <button class="font-label-md text-label-md text-primary font-bold hover:underline cursor-pointer" onclick="switchAuthTab('login')" type="button">Masuk di sini</button>
                                    </div>
                                </form>
                            </div>

                            <!-- Helpdesk Bantuan Resmi -->
                            <div class="pt-3 rounded-xl bg-surface-container-low border border-surface-container-high/60 p-3.5 flex items-center justify-between text-on-surface-variant">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary flex-shrink-0">
                                        <span class="material-symbols-outlined text-lg">support_agent</span>
                                    </div>
                                    <div class="text-left">
                                        <div class="font-label-sm text-label-sm font-semibold text-on-surface">Pusat Bantuan &amp; Helpdesk UNWAHAS</div>
                                        <div class="font-body-sm text-body-sm text-outline text-[11px]">Layanan Pelanggan Ext. 142 / WhatsApp 0812-3456-7890</div>
                                    </div>
                                </div>
                                <a class="px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface border border-surface-container-high font-label-sm text-label-sm text-on-surface font-semibold shadow-sm transition-all" href="https://wa.me/6281234567890?text=Halo%20Admin%20Buana%20Group%2C%20saya%20butuh%20bantuan%20login%20portal." target="_blank">
                                    Bantuan
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- 2. TAMPILAN MOBILE / APLIKASI (Layar Smartphone < lg & WebView Mobile App)-->
    <!-- ========================================================================= -->
    <main class="lg:hidden flex-1 flex flex-col relative w-full bg-surface min-h-screen pt-safe pb-safe">
        <div class="flex flex-col w-full px-margin pb-space-xl">
            
            <!-- Brand & Identity Header Mobile -->
            <div class="flex flex-col items-center text-center mt-space-lg mb-space-md">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#005e3a] to-[#003822] flex items-center justify-center shadow-md flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM12 20C7.59 20 4 16.41 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 16.41 16.41 20 12 20Z" fill="currentColor" fill-opacity="0.15"></path>
                            <path d="M12 3C7.03 3 3 7.03 3 12C3 16.97 7.03 21 12 21C14.76 21 17.22 19.75 18.89 17.78C16.44 17.9 13.9 16.66 12.63 14.39C11.36 12.12 11.66 9.4 13.11 7.47C12.75 7.42 12.38 7.4 12 7.4V3Z" fill="currentColor"></path>
                            <path d="M15.5 5.5C14.2 6.8 13.5 8.7 13.8 10.7C14.1 12.7 15.3 14.4 17.1 15.3C18 13.8 18.3 12 18 10.2C17.7 8.4 16.7 6.8 15.5 5.5Z" fill="#6ffbbe"></path>
                        </svg>
                    </div>
                    <div class="text-left">
                        <div class="font-bold text-[17px] text-primary leading-tight">BUANA GROUP</div>
                        <div class="text-[10px] text-outline font-semibold tracking-wider uppercase">Portal Layanan Terpadu UNWAHAS</div>
                    </div>
                </a>

                <p class="font-body-md text-body-md text-on-surface-variant flex items-center justify-center gap-1" id="mobile-header-toggle-wrap">
                    <span id="mobile-header-text">Belum punya akun?</span>
                    <a class="font-title-sm text-title-sm text-secondary hover:text-primary transition-colors font-semibold underline underline-offset-2 cursor-pointer" id="mobile-header-btn" href="{{ route('register') }}">
                        Daftar di sini
                    </a>
                </p>
            </div>

            <!-- Notifikasi Alert Mobile -->
            @if (isset($errors) && $errors->any())
                <div class="p-3 mb-space-sm rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2 text-xs">
                    <span class="material-symbols-outlined text-base flex-shrink-0 text-error">error</span>
                    <div class="min-w-0 flex-1">
                        <ul class="list-disc list-inside space-y-0.5 text-on-error-container">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('status') || session('success'))
                <div class="p-3 mb-space-sm rounded-xl bg-secondary-fixed/40 border border-secondary text-primary flex items-center gap-2 text-xs">
                    <span class="material-symbols-outlined text-base flex-shrink-0 text-secondary">check_circle</span>
                    <span>{{ session('status') ?? session('success') }}</span>
                </div>
            @endif

            <!-- ================= MOBILE VIEW: LOGIN ================= -->
            <div id="mobile-view-login">
                <!-- Segmented Switcher (Campus UNWAHAS vs Umum) -->
                <div class="bg-surface-container-low p-1 rounded-full flex items-center mb-space-md shadow-sm">
                    <button class="flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-on-primary shadow-sm flex items-center justify-center gap-1.5 cursor-pointer" id="tab-civitas" onclick="switchAccountType('unwahas')" type="button">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                        Civitas UNWAHAS
                    </button>
                    <button class="flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 text-outline flex items-center justify-center gap-1.5 cursor-pointer" id="tab-umum" onclick="switchAccountType('umum')" type="button">
                        <span class="material-symbols-outlined text-[18px]">person</span>
                        Pengguna Umum
                    </button>
                </div>

                <!-- Card Form Container -->
                <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm mb-space-md flex flex-col gap-space-md">
                    <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-space-md">
                        @csrf

                        <!-- Input 1: Email atau Nomor Telepon -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between" id="mobile-identity-label">
                                <span>Email atau Nomor Telepon</span>
                                <span class="text-secondary font-label-sm text-label-sm font-normal">Wajib</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">mail</span>
                                <input class="w-full pl-11 pr-4 py-3 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-1 focus:ring-primary-container transition-all border-0" id="mobile-identity-input" name="phone" value="{{ old('phone', old('account')) }}" placeholder="nama@email.com atau 08xxxxxxxxxx" required="" type="text"/>
                            </div>
                        </div>

                        <!-- Input 2: NIM / NIP Civitas Akademika with helper badge -->
                        <div class="flex flex-col gap-1.5" id="mobile-campus-container">
                            <label class="font-label-md text-label-md text-on-surface font-semibold flex items-center justify-between">
                                <span>NIM / NIP Civitas Akademika</span>
                                <span class="text-outline font-label-sm text-label-sm font-normal">Khusus UNWAHAS</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">badge</span>
                                <input class="w-full pl-11 pr-4 py-3 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-1 focus:ring-primary-container transition-all border-0" id="mobile-campus-input" name="campus_id" value="{{ old('campus_id') }}" placeholder="Masukkan NIM atau NIP/NIDN" type="text"/>
                            </div>
                            <div class="flex items-center gap-1.5 p-2 rounded-lg bg-secondary-fixed/30 text-on-secondary-fixed mt-0.5" id="mobile-segment-badge">
                                <span class="material-symbols-outlined text-secondary text-[16px]">verified</span>
                                <span class="font-label-sm text-label-sm text-secondary font-medium leading-tight">Mendeteksi otomatis hak akses harga khusus &amp; subsidi kampus</span>
                            </div>
                        </div>

                        <!-- Input 3: Kata Sandi with Show/Hide Toggle -->
                        <div class="flex flex-col gap-1.5">
                            <div class="flex items-center justify-between">
                                <label class="font-label-md text-label-md text-on-surface font-semibold">Kata Sandi</label>
                                <button class="font-label-sm text-label-sm text-secondary hover:text-primary transition-colors flex items-center gap-0.5 cursor-pointer" onclick="showForgotNotice()" type="button" title="Pemulihan via Email atau OTP WhatsApp">
                                    <span>Lupa Kata Sandi?</span>
                                </button>
                            </div>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px] pointer-events-none">lock</span>
                                <input class="w-full pl-11 pr-11 py-3 bg-surface-container-low rounded-lg font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none focus:bg-surface-container-lowest focus:ring-1 focus:ring-primary-container transition-all border-0" id="mobile-password-input" name="password" placeholder="Masukkan kata sandi akun" required="" type="password"/>
                                <button aria-label="Tampilkan kata sandi" class="absolute right-3 text-outline hover:text-on-surface transition-colors p-1 flex items-center justify-center cursor-pointer" onclick="togglePasswordVisibility('mobile-password-input', 'mobile-password-toggle-icon')" type="button">
                                    <span class="material-symbols-outlined text-[20px]" id="mobile-password-toggle-icon">visibility_off</span>
                                </button>
                            </div>
                            <p class="font-label-sm text-label-sm text-outline">
                                *Pemulihan via Email institusi atau OTP WhatsApp terdaftar
                            </p>
                        </div>

                        <!-- Remember Me Checkbox -->
                        <div class="flex items-center justify-between pt-1">
                            <label class="flex items-center gap-2.5 cursor-pointer select-none">
                                <input checked="" class="w-4 h-4 rounded text-secondary accent-secondary focus:ring-0 cursor-pointer" name="remember" value="1" type="checkbox"/>
                                <span class="font-body-sm text-body-sm text-on-surface-variant font-medium">Ingat Saya</span>
                            </label>
                            <span class="flex items-center gap-1 font-label-sm text-label-sm text-outline">
                                <span class="material-symbols-outlined text-[14px]">lock_clock</span>
                                30 hari
                            </span>
                        </div>

                        <!-- Primary CTA Button -->
                        <button class="w-full py-3.5 px-4 bg-primary-container active:scale-[0.99] text-on-primary font-title-sm text-title-sm rounded-full shadow-sm hover:opacity-95 transition-all flex items-center justify-center gap-2 mt-1 font-bold cursor-pointer" type="submit">
                            <span>Masuk</span>
                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                        </button>
                    </form>
                </div>

                <!-- Divider -->
                <div class="relative flex items-center justify-center my-space-sm">
                    <div class="w-full h-px bg-surface-container-highest"></div>
                    <span class="absolute bg-surface px-3 font-label-sm text-label-sm text-outline">atau masuk dengan</span>
                </div>

                <!-- Secondary SSO Campus Options -->
                <div class="flex flex-col gap-space-sm mt-space-xs">
                    <!-- SSO Yayasan Wahid Hasyim (Primary SSO) -->
                    <button onclick="showSsoNotice('SSO Yayasan Wahid Hasyim')" class="w-full py-3.5 px-4 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl font-title-sm text-title-sm text-on-surface shadow-sm transition-all flex items-center justify-between border border-primary/20 cursor-pointer text-left" type="button">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center text-primary flex-shrink-0">
                                <span class="material-symbols-outlined text-[22px]">account_balance</span>
                            </div>
                            <div class="flex flex-col text-left">
                                <div class="flex items-center gap-1.5">
                                    <span class="font-title-sm text-title-sm font-semibold text-primary">Masuk Cepat SSO Akun Yayasan Wahid Hasyim</span>
                                </div>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Login otomatis tanpa buat akun baru</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline text-[18px]">chevron_right</span>
                    </button>

                    <!-- Google Option -->
                    <button onclick="showSsoNotice('Akun Google UNWAHAS')" class="w-full py-2.5 px-4 bg-surface-container-lowest hover:bg-surface-container-low rounded-xl font-title-sm text-title-sm text-on-surface shadow-sm transition-all flex items-center justify-between cursor-pointer text-left" type="button">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-surface-container-high flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4" viewBox="0 0 24 24">
                                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"></path>
                                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"></path>
                                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z" fill="#FBBC05"></path>
                                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z" fill="#EA4335"></path>
                                </svg>
                            </div>
                            <div class="flex flex-col text-left">
                                <span class="font-title-sm text-title-sm">Akun Google</span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Gunakan akun Google institusi @unwahas.ac.id</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-outline text-[18px]">chevron_right</span>
                    </button>
                </div>

                <!-- Registration Helper / Footer Card -->
                <div class="mt-space-lg text-center flex flex-col items-center gap-1">
                    <p class="font-body-md text-body-md text-on-surface-variant flex items-center justify-center gap-1">
                        <span>Belum punya akun?</span>
                        <a class="font-title-sm text-title-sm text-secondary hover:text-primary transition-colors font-semibold underline underline-offset-2 cursor-pointer" href="{{ route('register') }}">
                            Daftar di sini
                        </a>
                    </p>
                </div>
            </div>

            <!-- ================= MOBILE VIEW: REGISTER ================= -->
            <div class="hidden" id="mobile-view-register">
                <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm mb-space-md flex flex-col gap-space-md">
                    <div class="border-b border-surface-container-high pb-3">
                        <h2 class="text-lg font-bold text-on-surface">Pendaftaran Akun Baru</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Dapatkan subsidi civitas kampus &amp; kemudahan pengadaan</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-space-md">
                        @csrf

                        <!-- Pilihan Peran Mobile -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold">Status Akun</label>
                            <div class="grid grid-cols-3 gap-1.5">
                                <label class="cursor-pointer">
                                    <input checked="" class="peer hidden" name="role_register" onchange="handleMobileRoleSelection('unwahas')" type="radio" value="unwahas"/>
                                    <div class="p-2 rounded-lg border border-surface-container-high peer-checked:border-primary-container peer-checked:bg-primary/5 text-center text-xs font-semibold">
                                        🎓 UNWAHAS
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input class="peer hidden" name="role_register" onchange="handleMobileRoleSelection('umum')" type="radio" value="umum"/>
                                    <div class="p-2 rounded-lg border border-surface-container-high peer-checked:border-primary-container peer-checked:bg-primary/5 text-center text-xs font-semibold">
                                        👤 Umum
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input class="peer hidden" name="role_register" onchange="handleMobileRoleSelection('instansi')" type="radio" value="instansi"/>
                                    <div class="p-2 rounded-lg border border-surface-container-high peer-checked:border-primary-container peer-checked:bg-primary/5 text-center text-xs font-semibold">
                                        🏢 Mitra
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Nama Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-name">
                                Nama Lengkap <span class="text-error">*</span>
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-name" name="name" value="{{ old('name') }}" placeholder="Nama lengkap sesuai KTP" required="" type="text"/>
                        </div>

                        <!-- Email Aktif -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-email">
                                Email Aktif <span class="text-error">*</span>
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required="" type="email"/>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-whatsapp">
                                Nomor WhatsApp <span class="text-error">*</span>
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-whatsapp" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required="" type="tel"/>
                        </div>

                        <!-- NIM / NIP -->
                        <div class="flex flex-col gap-1.5" id="mobile-reg-civitas-box">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-nim-nip">
                                NIM / NIP (Civitas Kampus)
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-nim-nip" name="campus_id" value="{{ old('campus_id') }}" placeholder="NIM / NIP untuk klaim subsidi harga" type="text"/>
                        </div>

                        <!-- Kata Sandi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-password">
                                Kata Sandi <span class="text-error">*</span>
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-password" name="password" placeholder="Minimal 8 karakter" required="" type="password"/>
                        </div>

                        <!-- Konfirmasi Sandi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface font-semibold" for="mobile-reg-password-confirm">
                                Konfirmasi Sandi <span class="text-error">*</span>
                            </label>
                            <input class="w-full px-3.5 py-2.5 bg-surface-container-low rounded-lg font-body-sm text-body-sm text-on-surface border-0 focus:ring-1 focus:ring-primary-container" id="mobile-reg-password-confirm" name="password_confirmation" placeholder="Ulangi kata sandi" required="" type="password"/>
                        </div>

                        <!-- Persetujuan Syarat -->
                        <label class="flex items-start gap-2 cursor-pointer select-none pt-1">
                            <input checked="" class="w-4 h-4 mt-0.5 rounded text-primary-container focus:ring-0 accent-[#005e3a]" required="" type="checkbox"/>
                            <span class="text-[11px] text-on-surface-variant leading-snug">
                                Saya menyetujui Ketentuan Layanan &amp; Kebijakan Privasi Buana Group UNWAHAS.
                            </span>
                        </label>

                        <!-- Tombol Submit Registrasi -->
                        <button class="w-full py-3.5 px-4 bg-[#005e3a] active:scale-[0.99] text-on-primary font-title-sm text-title-sm rounded-full shadow-sm hover:opacity-95 transition-all flex items-center justify-center gap-2 mt-1 font-bold cursor-pointer" type="submit">
                            <span>Daftar Sekarang</span>
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                        </button>

                        <div class="text-center pt-1">
                            <button class="text-xs text-primary font-bold hover:underline cursor-pointer" onclick="switchMobileAuthTab('login')" type="button">
                                &larr; Sudah punya akun? Masuk di sini
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Micro trust badge -->
            <div class="mt-space-lg flex items-center justify-center gap-1.5 text-outline">
                <span class="material-symbols-outlined text-[16px] text-secondary">verified</span>
                <span class="font-label-sm text-label-sm">Unit Usaha Resmi Universitas Wahid Hasyim</span>
            </div>

        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- SCRIPT LOGIKA ANTARMUKA & INTERAKSI                                      -->
    <!-- ========================================================================= -->
    <script>
        let activeTab = '{{ $initialTab ?? "login" }}';

        // 1. Toggle Tab Web (Desktop)
        function switchAuthTab(mode) {
            activeTab = mode;
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');
            const viewLogin = document.getElementById('view-login');
            const viewRegister = document.getElementById('view-register');
            const heading = document.getElementById('form-heading');
            const subheading = document.getElementById('form-subheading');
            const dividerText = document.getElementById('divider-text');

            if (!tabLogin || !tabRegister || !viewLogin || !viewRegister) return;

            if (mode === 'login') {
                tabLogin.className = 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-white shadow font-semibold';
                tabRegister.className = 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 text-on-surface-variant hover:text-on-surface hover:bg-white/60 font-semibold';
                
                viewLogin.classList.remove('hidden');
                viewRegister.classList.add('hidden');
                
                if (heading) heading.innerText = 'Masuk ke Akun Portal';
                if (subheading) subheading.innerText = 'Gunakan kredensial portal atau login cepat dengan SSO Yayasan Wahid Hasyim.';
                if (dividerText) dividerText.innerText = 'atau masuk dengan kredensial portal';
            } else {
                tabRegister.className = 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-white shadow font-semibold';
                tabLogin.className = 'flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg font-title-sm text-title-sm transition-all duration-200 text-on-surface-variant hover:text-on-surface hover:bg-white/60 font-semibold';
                
                viewRegister.classList.remove('hidden');
                viewLogin.classList.add('hidden');
                
                if (heading) heading.innerText = 'Daftar Akun Baru Buana Group';
                if (subheading) subheading.innerText = 'Lengkapi data formulir di bawah ini untuk mendapatkan akun pengadaan & hak harga khusus.';
                if (dividerText) dividerText.innerText = 'atau lengkapi formulir pendaftaran manual';
            }
        }

        // 2. Toggle Tab Mobile (Smartphone)
        function switchMobileAuthTab(mode) {
            const viewLogin = document.getElementById('mobile-view-login');
            const viewRegister = document.getElementById('mobile-view-register');
            const headerText = document.getElementById('mobile-header-text');
            const headerBtn = document.getElementById('mobile-header-btn');

            if (!viewLogin || !viewRegister) return;

            if (mode === 'login') {
                viewLogin.classList.remove('hidden');
                viewRegister.classList.add('hidden');
                if (headerText) headerText.innerText = 'Belum punya akun?';
                if (headerBtn) {
                    headerBtn.innerText = 'Daftar di sini';
                    headerBtn.onclick = function() { switchMobileAuthTab('register'); };
                }
            } else {
                viewRegister.classList.remove('hidden');
                viewLogin.classList.add('hidden');
                if (headerText) headerText.innerText = 'Sudah punya akun?';
                if (headerBtn) {
                    headerBtn.innerText = 'Masuk di sini';
                    headerBtn.onclick = function() { switchMobileAuthTab('login'); };
                }
            }
        }

        // 3. Pilihan Role Web
        function handleRoleSelection(role) {
            const boxCivitas = document.getElementById('civitas-verification-box');
            const inputNimNip = document.getElementById('reg-nim-nip');
            if (!boxCivitas || !inputNimNip) return;

            if (role === 'unwahas') {
                boxCivitas.classList.remove('opacity-40');
                inputNimNip.placeholder = 'Contoh: 21102001 (NIM) atau 061208... (NIP/NIDN)';
                inputNimNip.disabled = false;
            } else if (role === 'instansi') {
                boxCivitas.classList.remove('opacity-40');
                inputNimNip.placeholder = 'Nomor Pokok Perusahaan / ID Instansi Mitra (opsional)';
                inputNimNip.disabled = false;
            } else {
                boxCivitas.classList.add('opacity-40');
                inputNimNip.placeholder = 'Hanya khusus civitas UNWAHAS atau Instansi Rekanan';
                inputNimNip.disabled = false;
            }
        }

        // 4. Pilihan Role Mobile
        function handleMobileRoleSelection(role) {
            const boxCivitas = document.getElementById('mobile-reg-civitas-box');
            const inputNimNip = document.getElementById('mobile-reg-nim-nip');
            if (!boxCivitas || !inputNimNip) return;

            if (role === 'unwahas') {
                boxCivitas.classList.remove('opacity-40');
                inputNimNip.placeholder = 'NIM / NIP untuk klaim subsidi harga';
            } else if (role === 'instansi') {
                boxCivitas.classList.remove('opacity-40');
                inputNimNip.placeholder = 'ID Mitra / Nama Instansi (opsional)';
            } else {
                boxCivitas.classList.add('opacity-40');
                inputNimNip.placeholder = 'Bisa dikosongkan untuk umum';
            }
        }

        // 5. Segmented Switcher Mobile (Civitas UNWAHAS vs Umum)
        function switchAccountType(type) {
            const tabCivitas = document.getElementById('tab-civitas');
            const tabUmum = document.getElementById('tab-umum');
            const label = document.getElementById('mobile-identity-label');
            const input = document.getElementById('mobile-identity-input');
            const campusContainer = document.getElementById('mobile-campus-container');
            const badge = document.getElementById('mobile-segment-badge');

            if (!tabCivitas || !tabUmum) return;

            if (type === 'unwahas') {
                tabCivitas.className = 'flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-on-primary shadow-sm flex items-center justify-center gap-1.5 cursor-pointer';
                tabUmum.className = 'flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 text-outline flex items-center justify-center gap-1.5 cursor-pointer';
                if (label) label.innerHTML = '<span>Email Institusi atau No. HP</span><span class="text-secondary font-label-sm text-label-sm font-normal">Wajib</span>';
                if (input) input.placeholder = 'Contoh: nama@unwahas.ac.id atau 0812...';
                if (campusContainer) campusContainer.classList.remove('hidden');
                if (badge) badge.innerHTML = '<span class="material-symbols-outlined text-secondary text-[16px]">verified</span><span class="font-label-sm text-label-sm text-secondary font-medium leading-tight">Mendeteksi otomatis hak akses harga khusus &amp; subsidi kampus</span>';
            } else {
                tabUmum.className = 'flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 bg-primary-container text-on-primary shadow-sm flex items-center justify-center gap-1.5 cursor-pointer';
                tabCivitas.className = 'flex-1 py-2.5 px-3 rounded-full font-title-sm text-title-sm transition-all duration-200 text-outline flex items-center justify-center gap-1.5 cursor-pointer';
                if (label) label.innerHTML = '<span>No. Handphone atau Email</span><span class="text-secondary font-label-sm text-label-sm font-normal">Wajib</span>';
                if (input) input.placeholder = 'Contoh: 08123456789 atau nama@email.com';
                if (campusContainer) campusContainer.classList.add('hidden');
            }
        }

        // 6. Toggle Show/Hide Password
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        // 7. Notifikasi SSO & Forgot Password
        function showSsoNotice(provider) {
            alert('Layanan ' + provider + ' sedang dalam tahap sinkronisasi server kampus Yayasan Wahid Hasyim. Untuk saat ini, silakan gunakan login manual menggunakan Email / No. HP Anda.');
        }

        function showForgotNotice() {
            alert('Untuk pemulihan kata sandi akun, silakan hubungi Helpdesk Layanan UNWAHAS via WhatsApp: 0812-3456-7890 dengan menyertakan Nomor HP terdaftar / NIM / NIP.');
        }

        // 8. Inisialisasi Tab Awal
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            const hash = window.location.hash;

            if (activeTab === 'register' || tabParam === 'register' || hash === '#register') {
                switchAuthTab('register');
                switchMobileAuthTab('register');
            } else {
                switchAuthTab('login');
                switchMobileAuthTab('login');
            }
        });
    </script>
</body>
</html>
