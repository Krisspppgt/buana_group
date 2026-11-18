<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta content="web_blank" name="shell-type"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Akun Baru - Buana Group | Universitas Wahid Hasyim</title>

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

    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#004429",
                        "primary-container": "#005e3a",
                        "primary-fixed": "#a4f3c4",
                        "primary-fixed-dim": "#89d7a9",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#87d5a7",
                        "on-primary-fixed": "#002111",
                        "on-primary-fixed-variant": "#005232",
                        "secondary": "#006c49",
                        "secondary-container": "#6cf8bb",
                        "secondary-fixed": "#6ffbbe",
                        "secondary-fixed-dim": "#4edea3",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#00714d",
                        "on-secondary-fixed": "#002113",
                        "on-secondary-fixed-variant": "#005236",
                        "tertiary": "#323d35",
                        "tertiary-container": "#48544b",
                        "tertiary-fixed": "#d9e6da",
                        "tertiary-fixed-dim": "#bdcabe",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#bbc8bd",
                        "on-tertiary-fixed": "#131e17",
                        "on-tertiary-fixed-variant": "#3e4a41",
                        "surface": "#f9f9ff",
                        "surface-dim": "#cfdaf2",
                        "surface-bright": "#f9f9ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f0f3ff",
                        "surface-container": "#e7eeff",
                        "surface-container-high": "#dee8ff",
                        "surface-container-highest": "#d8e3fb",
                        "surface-variant": "#d8e3fb",
                        "surface-tint": "#196b46",
                        "on-surface": "#111c2d",
                        "on-surface-variant": "#3f4942",
                        "outline": "#6f7a71",
                        "outline-variant": "#bfc9c0",
                        "background": "#f9f9ff",
                        "on-background": "#111c2d",
                        "inverse-surface": "#263143",
                        "inverse-on-surface": "#ecf1ff",
                        "inverse-primary": "#89d7a9",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error": "#ffffff",
                        "on-error-container": "#93000a"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-md": "1rem",
                        "space-lg": "1.25rem",
                        "space-xl": "1.75rem"
                    },
                    fontFamily: {
                        "body-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"],
                        "title-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "title-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-[#f0f3f8] font-body-md text-on-surface antialiased selection:bg-primary-container selection:text-white">

    <!-- ========================================================================= -->
    <!-- 1. TAMPILAN WEB (Desktop & Layar Lebar >= lg) - PERSIS SCREENSHOT DESKTOP  -->
    <!-- ========================================================================= -->
    <main class="hidden lg:flex min-h-screen w-full items-center justify-center p-4 md:p-8 bg-gradient-to-br from-surface-container-low via-[#ebf1fa] to-[#e4ecf7]">
        <div class="flex flex-col w-full">
            <div class="w-full max-w-7xl mx-auto my-auto">
                <div class="grid grid-cols-1 lg:grid-cols-12 rounded-2xl overflow-hidden shadow-2xl bg-surface-container-lowest border border-white/80">
                    
                    <!-- Sisi Kiri: Branding & Visual Showcase Portal (Forest Green Theme) -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-primary-container via-primary to-[#002414] p-8 lg:p-12 text-on-primary flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute -right-24 -top-24 w-88 h-88 rounded-full bg-secondary-fixed/15 blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-20 -bottom-20 w-96 h-96 rounded-full bg-primary-fixed/10 blur-2xl pointer-events-none"></div>

                        <div class="relative z-10 flex flex-col gap-6">
                            <!-- Top Badges Portal -->
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-black/35 border border-white/10 text-white text-[11px] font-semibold tracking-wide">
                                    <span class="w-2 h-2 rounded-full bg-[#6ffbbe] animate-pulse"></span>
                                    PORTAL RESMI
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-white text-[11px] font-medium">
                                    <span class="material-symbols-outlined text-sm text-[#6ffbbe]">verified</span>
                                    Naungan Yayasan Wahid Hasyim Semarang
                                </span>
                            </div>

                            <!-- Buana Group Brand Identification -->
                            <div class="flex items-center gap-3 pt-1">
                                <a href="{{ route('home') }}" class="w-12 h-12 rounded-xl bg-white flex items-center justify-center shadow-lg p-2 flex-shrink-0 hover:opacity-95 transition-opacity">
                                    <img src="{{ asset('images/logo.png') }}" onerror="this.onerror=null; this.src='https://lh3.googleusercontent.com/aida/ALJJyrDsKJUjHiQ5_bbhSBifX0PaQA13g9pIGnHsWmKqpZJIZ9sCwo5AwIrYZngww5oRUQnPSXmlv_71f3KYwhnLAZ0med4WJbZt3Bd9OhM7Wss6NhzqqdK8ljp76aFzy5tc1uBZ67z-fUNLIurhlLqAgJUcoxmbnMJIj3Jk01fNu2UUTvt3TAALMqyTyjCax8IUWJiRj-t_S54W0Vwr0UuU6X0_kAcDz-xD13qV69crFgrl';" alt="Buana Group" class="w-full h-full object-contain">
                                </a>
                                <div>
                                    <h2 class="text-xl font-black text-white tracking-tight leading-none font-headline-sm">Buana Group</h2>
                                    <p class="text-[10px] tracking-widest text-[#a4f3c4] font-semibold uppercase mt-1">INDONESIAN BUANA GROUP</p>
                                </div>
                            </div>

                            <!-- Showcase Hero Title -->
                            <div class="space-y-2.5 pt-1">
                                <h1 class="font-headline-lg text-2xl lg:text-3xl text-on-primary tracking-tight font-extrabold leading-snug">
                                    Gabung Ekosistem Layanan Bisnis Buana Group
                                </h1>
                                <p class="font-body-md text-xs lg:text-sm text-[#d9e6da] leading-relaxed">
                                    Solusi terpadu pengadaan resmi kebutuhan kampus dan umum: Air Mineral kemasan &amp; galon, Katering Rapat, Biro Wisata &amp; Tiket, serta Konveksi Seragam resmi terverifikasi.
                                </p>
                            </div>

                            <!-- 4 Business Unit Showcase Grid -->
                            <div class="grid grid-cols-2 gap-2.5 pt-1">
                                <a href="{{ route('water.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">water_drop</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-xs font-bold text-on-primary truncate">Air Mineral Buana</div>
                                        <div class="font-label-sm text-[10px] text-on-primary-container truncate">Galon, Botol &amp; Cup Higienis</div>
                                    </div>
                                </a>

                                <a href="{{ route('food.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">restaurant</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-xs font-bold text-on-primary truncate">Katering &amp; Rapat</div>
                                        <div class="font-label-sm text-[10px] text-on-primary-container truncate">Snack Box &amp; Prasmanan Halal</div>
                                    </div>
                                </a>

                                <a href="{{ route('tour.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">directions_bus</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-xs font-bold text-on-primary truncate">Travel &amp; Wisata</div>
                                        <div class="font-label-sm text-[10px] text-on-primary-container truncate">Tiket, Armada &amp; KKL/Studi</div>
                                    </div>
                                </a>

                                <a href="{{ route('konveksi.index') }}" class="bg-surface-container-lowest/10 border border-white/10 backdrop-blur-sm rounded-xl p-3 flex items-center gap-2.5 transition-transform hover:-translate-y-0.5">
                                    <div class="w-10 h-10 rounded-lg bg-surface-container-lowest/15 flex items-center justify-center text-secondary-fixed flex-shrink-0">
                                        <span class="material-symbols-outlined text-2xl">styler</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-title-sm text-xs font-bold text-on-primary truncate">Konveksi Seragam</div>
                                        <div class="font-label-sm text-[10px] text-on-primary-container truncate">PDH, Kaos &amp; Jas Almamater</div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Testimonial Box (Persis Screenshot) -->
                        <div class="relative z-10 mt-8 pt-4">
                            <div class="bg-surface-container-lowest/10 border border-white/15 backdrop-blur-md rounded-xl p-4 flex items-start gap-3 shadow-sm">
                                <div class="w-8 h-8 rounded-full bg-[#006c49] flex items-center justify-center text-[#6ffbbe] flex-shrink-0 font-serif font-black text-xl leading-none">
                                    “
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-body-sm text-xs text-inverse-on-surface italic leading-relaxed">
                                        "Layanan terpusat ini mempercepat alur logistik, efisiensi pertanggungjawaban SPJ, serta menjamin mutu setiap operasional civitas Unwahas."
                                    </p>
                                    <p class="text-[11px] text-[#a4f3c4] font-semibold mt-1.5">
                                        Biro Sarana &amp; Prasarana Yayasan Wahid Hasyim
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-on-primary-container/80 pt-4">
                                <span>&copy; PT Buana Riset Mandiri &bull; Yayasan Wahid Hasyim</span>
                                <span class="px-2 py-0.5 rounded-full bg-white/10 text-[#6ffbbe] font-medium">Versi 3.4.2 (Terakreditasi)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Sisi Kanan: Dedicated Registration Form (Persis Screenshot) -->
                    <div class="lg:col-span-7 p-6 sm:p-8 lg:p-12 bg-surface-container-lowest flex flex-col justify-center">
                        <div class="max-w-xl w-full mx-auto space-y-4">
                            
                            <!-- Header Form -->
                            <div class="space-y-1">
                                <div class="flex items-center justify-between">
                                    <h2 class="text-2xl font-extrabold text-on-surface tracking-tight">Daftar Akun Baru</h2>
                                    <a class="inline-flex items-center gap-1 font-label-md text-xs font-bold text-primary hover:underline" href="{{ route('login') }}">
                                        Sudah punya akun? Masuk <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </div>
                                <p class="text-body-sm text-xs text-on-surface-variant">Lengkapi data berikut untuk memulai pemesanan dan nikmati hak akses khusus.</p>
                            </div>

                            <!-- Alert Error Server -->
                            @if (isset($errors) && $errors->any())
                                <div class="p-3.5 rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2.5 text-xs shadow-xs">
                                    <span class="material-symbols-outlined text-lg flex-shrink-0 text-error">error</span>
                                    <div class="min-w-0 flex-1">
                                        <div class="font-bold text-error">Mohon periksa kesalahan berikut:</div>
                                        <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-error-container">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('register') }}" class="space-y-3.5" id="form-register-desktop">
                                @csrf
                                <input type="hidden" name="role_register" id="desktop-role-input" value="unwahas">

                                <!-- Role Selection Cards (Persis Screenshot) -->
                                <div class="space-y-1.5">
                                    <label class="block font-label-md text-xs text-on-surface font-bold">Pilih Kategori Pengguna / Peran Akun</label>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5" id="desktopRoleGroup">
                                        <!-- Card 1: Civitas UNWAHAS (Default Active) -->
                                        <button type="button" onclick="selectDesktopRole('unwahas')" id="desk-role-unwahas" class="p-3 rounded-xl border border-primary bg-primary text-on-primary text-left transition-all h-full flex flex-col justify-between shadow-sm cursor-pointer">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="w-7 h-7 rounded-lg bg-white/15 text-white flex items-center justify-center" id="desk-icon-box-unwahas">
                                                    <span class="material-symbols-outlined text-lg">school</span>
                                                </span>
                                                <span class="w-2.5 h-2.5 rounded-full bg-secondary-fixed ring-2 ring-primary" id="desk-dot-unwahas"></span>
                                            </div>
                                            <div>
                                                <span class="font-label-md text-sm font-bold block leading-tight text-white" id="desk-title-unwahas">Civitas UNWAHAS</span>
                                                <span class="text-[11px] block mt-0.5 text-on-primary-container" id="desk-sub-unwahas">Dosen, Mahasiswa &amp; Staff</span>
                                            </div>
                                        </button>

                                        <!-- Card 2: Masyarakat Umum -->
                                        <button type="button" onclick="selectDesktopRole('umum')" id="desk-role-umum" class="p-3 rounded-xl border border-surface-container-high bg-surface-container-lowest text-on-surface text-left transition-all h-full flex flex-col justify-between hover:border-outline cursor-pointer">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="w-7 h-7 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center" id="desk-icon-box-umum">
                                                    <span class="material-symbols-outlined text-lg">person</span>
                                                </span>
                                                <span class="w-2.5 h-2.5 rounded-full bg-secondary-fixed ring-2 ring-primary hidden" id="desk-dot-umum"></span>
                                            </div>
                                            <div>
                                                <span class="font-label-md text-sm font-bold block leading-tight text-on-surface" id="desk-title-umum">Masyarakat Umum</span>
                                                <span class="text-[11px] block mt-0.5 text-on-surface-variant" id="desk-sub-umum">Pesanan B2C &amp; Mandiri</span>
                                            </div>
                                        </button>

                                        <!-- Card 3: Mitra / Instansi -->
                                        <button type="button" onclick="selectDesktopRole('instansi')" id="desk-role-instansi" class="p-3 rounded-xl border border-surface-container-high bg-surface-container-lowest text-on-surface text-left transition-all h-full flex flex-col justify-between hover:border-outline cursor-pointer">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="w-7 h-7 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center" id="desk-icon-box-instansi">
                                                    <span class="material-symbols-outlined text-lg">storefront</span>
                                                </span>
                                                <span class="w-2.5 h-2.5 rounded-full bg-secondary-fixed ring-2 ring-primary hidden" id="desk-dot-instansi"></span>
                                            </div>
                                            <div>
                                                <span class="font-label-md text-sm font-bold block leading-tight text-on-surface" id="desk-title-instansi">Mitra / Instansi</span>
                                                <span class="text-[11px] block mt-0.5 text-on-surface-variant" id="desk-sub-instansi">Pengadaan B2B &amp; Kantor</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Info Banner Civitas -->
                                <div class="p-3 rounded-xl bg-surface-container border border-surface-container-high flex items-start gap-2.5" id="civitas-verification-box">
                                    <span class="material-symbols-outlined text-primary text-[20px] mt-0.5" id="civitas-box-icon">verified_user</span>
                                    <div class="flex-1">
                                        <p class="font-title-sm text-xs text-primary font-bold" id="civitas-box-title">Fasilitas Khusus Civitas UNWAHAS</p>
                                        <p class="font-body-sm text-[11px] text-on-surface-variant leading-snug mt-0.5" id="civitas-box-desc">
                                            Otomatis mendeteksi tarif khusus internal, fasilitas SPJ terpusat terintegrasi fakultas, dan verifikasi akun instan via portal akademik.
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <!-- Nama Lengkap -->
                                    <div class="space-y-1">
                                        <label class="block font-label-md text-xs text-on-surface font-semibold flex items-center justify-between" for="fullName">
                                            <span>Nama Lengkap <span class="text-error">*</span></span>
                                            <span class="text-[11px] text-on-surface-variant font-normal">Sesuai KTP / Identitas Resmi</span>
                                        </label>
                                        <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                <span class="material-symbols-outlined text-lg">badge</span>
                                            </div>
                                            <input class="w-full pl-10 pr-4 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="fullName" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap Anda" required="" type="text"/>
                                        </div>
                                    </div>

                                    <!-- Grid Email & WhatsApp (+62 prefix) -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <!-- Email Aktif -->
                                        <div class="space-y-1">
                                            <label class="block font-label-md text-xs text-on-surface font-semibold" for="email">Email Aktif <span class="text-error">*</span></label>
                                            <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                    <span class="material-symbols-outlined text-lg">mail</span>
                                                </div>
                                                <input class="w-full pl-10 pr-3 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="email" name="email" value="{{ old('email') }}" placeholder="nama@domain.com" required="" type="email"/>
                                            </div>
                                        </div>

                                        <!-- WhatsApp Aktif with +62 Box -->
                                        <div class="space-y-1">
                                            <label class="block font-label-md text-xs text-on-surface font-semibold" for="whatsapp">Nomor WhatsApp Aktif <span class="text-error">*</span></label>
                                            <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all flex items-center">
                                                <div class="pl-3.5 pr-2 py-2.5 flex items-center gap-1.5 text-on-surface-variant font-bold text-xs select-none pointer-events-none">
                                                    <span>+62</span>
                                                    <span class="h-4 w-px bg-outline-variant/60 ml-1"></span>
                                                </div>
                                                <input class="w-full pr-3 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="whatsapp" name="phone" value="{{ old('phone') }}" placeholder="812-3456-7890" required="" type="tel"/>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Campus Field (NIM / NIP) -->
                                    <div class="space-y-1" id="civitasFieldGroup">
                                        <div class="flex items-center justify-between">
                                            <label class="block font-label-md text-xs text-on-surface font-semibold" for="reg-nim-nip">
                                                <span id="label-nim-nip">NIM / NIP</span> <span class="text-error" id="req-star-desktop">*</span>
                                            </label>
                                            <span class="inline-flex items-center gap-1 text-[11px] text-primary font-bold bg-[#d8e3fb] text-[#005e3a] px-2.5 py-0.5 rounded-full border border-primary/20" id="badge-nim-desktop">
                                                Civitas Verification
                                            </span>
                                        </div>
                                        <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                <span class="material-symbols-outlined text-lg">badge</span>
                                            </div>
                                            <input class="w-full pl-10 pr-4 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="reg-nim-nip" name="campus_id" value="{{ old('campus_id') }}" placeholder="Contoh: 21102001 (NIM) atau 198501... (NIP)" type="text"/>
                                        </div>
                                    </div>

                                    <!-- Grid Kata Sandi & Konfirmasi -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="block font-label-md text-xs text-on-surface font-semibold" for="password">Kata Sandi <span class="text-error">*</span></label>
                                            <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                    <span class="material-symbols-outlined text-lg">lock</span>
                                                </div>
                                                <input class="w-full pl-10 pr-10 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="password" name="password" placeholder="Minimal 8 karakter" required="" type="password"/>
                                                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer" onclick="togglePassVisibility('password', this)" type="button">
                                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="block font-label-md text-xs text-on-surface font-semibold" for="passwordConfirm">Konfirmasi Sandi <span class="text-error">*</span></label>
                                            <div class="relative rounded-xl bg-surface-container-low border border-surface-container-high focus-within:border-primary-container focus-within:bg-white focus-within:ring-2 focus-within:ring-primary-container/20 transition-all">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-outline">
                                                    <span class="material-symbols-outlined text-lg">sync_lock</span>
                                                </div>
                                                <input class="w-full pl-10 pr-10 py-2.5 bg-transparent text-on-surface placeholder:text-outline/70 text-xs focus:outline-none border-0" id="passwordConfirm" name="password_confirmation" placeholder="Ulangi kata sandi" required="" type="password"/>
                                                <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-outline hover:text-on-surface focus:outline-none cursor-pointer" onclick="togglePassVisibility('passwordConfirm', this)" type="button">
                                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Persetujuan Checkbox (Persis Screenshot) -->
                                <div class="pt-1">
                                    <label class="flex items-start gap-2.5 cursor-pointer select-none">
                                        <input checked="" class="w-4 h-4 mt-0.5 rounded text-primary-container focus:ring-primary accent-[#004429]" required="" type="checkbox"/>
                                        <span class="font-body-sm text-xs text-on-surface-variant leading-snug">
                                            Saya menyetujui <a class="text-primary font-bold hover:underline" href="javascript:void(0)" onclick="alert('Syarat & Ketentuan Layanan berlaku untuk seluruh transaksi resmi Buana Group.')">Syarat &amp; Ketentuan</a> serta <a class="text-primary font-bold hover:underline" href="javascript:void(0)" onclick="alert('Kebijakan Privasi menjamin keamanan data pengguna sesuai UU PDP No. 27 Tahun 2022.')">Kebijakan Privasi</a> pengelolaan akun resmi Buana Group (Yayasan Wahid Hasyim Semarang).
                                        </span>
                                    </label>
                                </div>

                                <!-- Submit Button (Persis Screenshot: Solid Dark Green with Arrow) -->
                                <button class="w-full min-h-[46px] py-3 px-6 rounded-xl bg-[#004429] hover:bg-[#00331e] text-white text-sm shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2 group active:scale-[0.99] font-bold cursor-pointer" type="submit">
                                    <span>Daftar Sekarang</span>
                                    <span class="material-symbols-outlined text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </button>

                                <!-- Divider ATAU DAFTAR INSTAN -->
                                <div class="relative flex items-center my-2">
                                    <div class="flex-grow border-t border-surface-container-high"></div>
                                    <span class="flex-shrink mx-3 text-[11px] text-outline font-bold uppercase tracking-wider">ATAU DAFTAR INSTAN</span>
                                    <div class="flex-grow border-t border-surface-container-high"></div>
                                </div>

                                <!-- SSO Buttons -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                    <button onclick="alert('Pendaftaran SSO Yayasan UNWAHAS sedang tahap sinkronisasi server kampus.')" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-surface-container-lowest hover:bg-surface-container border border-surface-container-high text-on-surface shadow-xs transition-all cursor-pointer" type="button">
                                        <span class="material-symbols-outlined text-primary text-[18px]">account_balance</span>
                                        <span class="text-xs font-bold truncate">SSO Yayasan UNWAHAS</span>
                                    </button>
                                    <button onclick="alert('Pendaftaran Google Institusi sedang tahap sinkronisasi server kampus.')" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl bg-surface-container-lowest hover:bg-surface-container border border-surface-container-high text-on-surface shadow-xs transition-all cursor-pointer" type="button">
                                        <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24">
                                            <path d="M23.745 12.27c0-.7-.06-1.4-.19-2.07H12v4.51h6.6c-.29 1.52-1.14 2.8-2.4 3.66v3.05h3.88c2.27-2.09 3.66-5.17 3.66-9.15z" fill="#4285F4"></path>
                                            <path d="M12 24c3.24 0 5.95-1.08 7.93-2.91l-3.88-3.05c-1.08.72-2.45 1.16-4.05 1.16-3.12 0-5.77-2.1-6.72-4.93H1.25v3.15C3.26 21.36 7.33 24 12 24z" fill="#34A853"></path>
                                            <path d="M5.28 14.27c-.25-.72-.38-1.49-.38-2.27s.13-1.55.38-2.27V6.58H1.25C.45 8.18 0 9.99 0 12s.45 3.82 1.25 5.42l4.03-3.15z" fill="#FBBC05"></path>
                                            <path d="M12 4.75c1.77 0 3.35.61 4.6 1.8l3.42-3.42C17.95 1.19 15.24 0 12 0 7.33 0 3.26 2.64 1.25 6.58l4.03 3.15c.95-2.83 3.6-4.98 6.72-4.98z" fill="#EA4335"></path>
                                        </svg>
                                        <span class="text-xs font-bold">Google Institusi</span>
                                    </button>
                                </div>
                            </form>

                            <!-- Helpdesk Banner (Persis Screenshot) -->
                            <div class="rounded-xl bg-surface-container-low border border-surface-container-high/60 p-3 flex items-center justify-between text-on-surface-variant">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-9 h-9 rounded-full bg-secondary-fixed/30 flex items-center justify-center text-primary flex-shrink-0">
                                        <span class="material-symbols-outlined text-lg">support_agent</span>
                                    </div>
                                    <div class="text-left">
                                        <div class="text-xs font-bold text-on-surface">Butuh Bantuan Registrasi?</div>
                                        <div class="text-[11px] text-outline">Layanan Helpdesk UNWAHAS &amp; Buana Group</div>
                                    </div>
                                </div>
                                <a class="px-3 py-1.5 rounded-lg bg-surface-container-lowest hover:bg-surface border border-surface-container-high text-xs text-primary font-bold shadow-xs transition-all flex items-center gap-1.5" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20pendaftaran%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                                    <span class="material-symbols-outlined text-sm">chat</span>
                                    <span>WhatsApp CS</span>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- 2. TAMPILAN MOBILE (Layar Smartphone < lg) - PERSIS SCREENSHOT MOBILE     -->
    <!-- ========================================================================= -->
    <main class="lg:hidden flex-1 flex flex-col relative w-full bg-surface min-h-screen pt-safe pb-safe">
        <div class="flex flex-col w-full pb-8">
            
            <!-- Brand Top Header / App Bar (Persis Screenshot) -->
            <div class="w-full bg-surface-container-lowest px-4 pt-3 pb-4 shadow-sm flex items-center justify-between sticky top-0 z-30">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('login') }}" aria-label="Kembali" class="w-9 h-9 rounded-full bg-surface-container flex items-center justify-center text-on-surface hover:bg-surface-container-high transition-colors">
                        <span class="material-symbols-outlined text-lg">arrow_back</span>
                    </a>
                    <img alt="Logo Buana Group" class="h-8 object-contain" src="https://lh3.googleusercontent.com/aida/ALJJyrDsKJUjHiQ5_bbhSBifX0PaQA13g9pIGnHsWmKqpZJIZ9sCwo5AwIrYZngww5oRUQnPSXmlv_71f3KYwhnLAZ0med4WJbZt3Bd9OhM7Wss6NhzqqdK8ljp76aFzy5tc1uBZ67z-fUNLIurhlLqAgJUcoxmbnMJIj3Jk01fNu2UUTvt3TAALMqyTyjCax8IUWJiRj-t_S54W0Vwr0UuU6X0_kAcDz-xD13qV69crFgrl">
                </div>
                <div class="flex items-center space-x-1.5 px-3 py-1 bg-surface-container rounded-full">
                    <span class="w-2 h-2 rounded-full bg-secondary"></span>
                    <span class="font-label-sm text-xs font-bold text-secondary">Aman &amp; Terverifikasi</span>
                </div>
            </div>

            <div class="px-4 mt-4 flex flex-col space-y-4">
                
                <!-- Notifikasi Alert Server Mobile -->
                @if (isset($errors) && $errors->any())
                    <div class="p-3 rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2 text-xs">
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

                <!-- Title Section Card (Persis Screenshot) -->
                <div class="bg-surface-container-lowest rounded-xl p-4 shadow-sm">
                    <div class="flex items-start justify-between">
                        <div>
                            <h1 class="font-headline-sm text-lg font-bold text-on-surface">Daftar Akun Baru</h1>
                            <p class="font-body-sm text-xs text-on-surface-variant mt-1 leading-snug">Mencakup unit usaha Air Mineral, Katering, Travel, Tiket, &amp; Konveksi</p>
                        </div>
                        <div class="w-10 h-10 rounded-xl bg-secondary-fixed/30 flex items-center justify-center text-primary shrink-0 ml-2">
                            <span class="material-symbols-outlined text-xl">person_add</span>
                        </div>
                    </div>

                    <!-- Role Selector Stack -->
                    <div class="mt-4">
                        <label class="font-label-md text-xs font-bold text-on-surface block mb-2">Pilih Status / Kategori Pengguna <span class="text-error">*</span></label>
                        <div class="grid grid-cols-1 gap-2">
                            <!-- Role Civitas -->
                            <button type="button" id="role-civitas" onclick="selectRoleOption('civitas')" class="w-full p-2.5 rounded-xl border border-secondary bg-secondary-fixed/20 text-left flex items-center justify-between transition-all duration-200 cursor-pointer">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-primary-container text-on-primary flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-base">school</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-xs text-on-surface font-bold">Mahasiswa / Dosen Unwahas</p>
                                        <p class="font-label-sm text-[11px] text-secondary font-semibold">Civitas Kampus &bull; Tarif &amp; Akses SPJ Internal</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-primary text-xl" id="role-icon-civitas">check_circle</span>
                            </button>

                            <!-- Role Umum -->
                            <button type="button" id="role-umum" onclick="selectRoleOption('umum')" class="w-full p-2.5 rounded-xl border border-transparent bg-surface-container-low hover:bg-surface-container text-left flex items-center justify-between transition-all duration-200 cursor-pointer">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-base">person</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-xs text-on-surface font-bold">Masyarakat Umum (B2C)</p>
                                        <p class="font-label-sm text-[11px] text-on-surface-variant">Belanja pribadi &amp; pemesanan personal</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-on-surface-variant/40 text-xl" id="role-icon-umum">radio_button_unchecked</span>
                            </button>

                            <!-- Role Mitra -->
                            <button type="button" id="role-mitra" onclick="selectRoleOption('mitra')" class="w-full p-2.5 rounded-xl border border-transparent bg-surface-container-low hover:bg-surface-container text-left flex items-center justify-between transition-all duration-200 cursor-pointer">
                                <div class="flex items-center space-x-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-base">corporate_fare</span>
                                    </div>
                                    <div>
                                        <p class="font-label-md text-xs text-on-surface font-bold">Mitra / Instansi (B2B)</p>
                                        <p class="font-label-sm text-[11px] text-on-surface-variant">Perusahaan swasta, yayasan, &amp; vendor</p>
                                    </div>
                                </div>
                                <span class="material-symbols-outlined text-on-surface-variant/40 text-xl" id="role-icon-mitra">radio_button_unchecked</span>
                            </button>
                        </div>
                    </div>

                    <div class="mt-3 p-3 bg-secondary-fixed/20 rounded-lg flex items-start space-x-2.5 transition-all" id="role-banner">
                        <span class="material-symbols-outlined text-secondary text-sm shrink-0 mt-0.5" id="role-banner-icon">verified_user</span>
                        <p class="font-body-sm text-xs text-on-secondary-container" id="role-banner-text">
                            Khusus civitas UNWAHAS: Dapatkan harga khusus internal, fasilitas SPJ terpusat, &amp; integrasi akun akademik.
                        </p>
                    </div>
                </div>

                <!-- Registration Form Mobile -->
                <form method="POST" action="{{ route('register') }}" class="space-y-4" id="form-register-mobile">
                    @csrf
                    <input type="hidden" name="role_register" id="mobile-role-input" value="unwahas">

                    <!-- Nama Lengkap Card (Persis Screenshot) -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm space-y-1.5">
                        <label class="font-label-md text-xs font-bold text-on-surface flex items-center space-x-1" for="full-name">
                            <span>Nama Lengkap</span>
                            <span class="text-error">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">badge</span>
                            <input class="w-full pl-10 pr-3 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="full-name" name="name" value="{{ old('name') }}" placeholder="Sesuai KTP / Kartu Identitas" type="text" required=""/>
                        </div>
                        <p class="font-label-sm text-[11px] text-on-surface-variant">Sesuai kartu identitas resmi yang berlaku.</p>
                    </div>

                    <!-- Email Card (Persis Screenshot) -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm space-y-1.5">
                        <label class="font-label-md text-xs font-bold text-on-surface flex items-center space-x-1" for="email-address">
                            <span>Email Aktif</span>
                            <span class="text-error">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">alternate_email</span>
                            <input class="w-full pl-10 pr-3 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="email-address" name="email" value="{{ old('email') }}" placeholder="nama@email.com" type="email" required=""/>
                        </div>
                        <p class="font-label-sm text-[11px] text-on-surface-variant">Untuk verifikasi akun &amp; notifikasi transaksi.</p>
                    </div>

                    <!-- WhatsApp Card (Persis Screenshot with +62 |) -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm space-y-1.5">
                        <label class="font-label-md text-xs font-bold text-on-surface flex items-center space-x-1" for="phone-number">
                            <span>Nomor WhatsApp</span>
                            <span class="text-error">*</span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">call</span>
                            <div class="absolute left-10 flex items-center pointer-events-none">
                                <span class="font-label-md text-xs text-on-surface-variant pl-0.5 font-bold">+62</span>
                                <span class="h-4 w-px bg-outline-variant mx-2"></span>
                            </div>
                            <input class="w-full pl-24 pr-3 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="phone-number" name="phone" value="{{ old('phone') }}" placeholder="812-3456-7890" type="tel" required=""/>
                        </div>
                        <p class="font-label-sm text-[11px] text-on-surface-variant">Wajib &bull; untuk notifikasi status pesanan &amp; pengiriman.</p>
                    </div>

                    <!-- Campus ID Card (Persis Screenshot) -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm space-y-1.5 transition-all" id="civitas-id-card">
                        <label class="font-label-md text-xs text-on-surface flex items-center justify-between" for="academic-id">
                            <span class="flex items-center space-x-1">
                                <span class="font-bold">NIM (Mahasiswa) atau NIP (Pegawai/Dosen)</span>
                                <span class="text-error" id="academic-id-required">*</span>
                            </span>
                            <span class="font-label-sm text-[11px] text-secondary font-bold flex items-center gap-1" id="badge-academic-civitas">
                                <span class="w-1.5 h-1.5 rounded-full bg-error inline-block"></span>
                                Civitas UNWAHAS
                            </span>
                        </label>
                        <div class="relative flex items-center">
                            <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">fingerprint</span>
                            <input class="w-full pl-10 pr-3 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="academic-id" name="campus_id" value="{{ old('campus_id') }}" placeholder="Masukkan NIM atau NIP resmi Anda" type="text"/>
                        </div>
                        <p class="font-label-sm text-[11px] text-on-surface-variant">Wajib diisi civitas akademika untuk mengaktifkan fitur SSO &amp; hak istimewa harga khusus.</p>
                    </div>

                    <!-- Password Card (Persis Screenshot) -->
                    <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm space-y-3">
                        <div class="space-y-1.5">
                            <label class="font-label-md text-xs font-bold text-on-surface flex items-center space-x-1" for="password-mobile">
                                <span>Kata Sandi</span>
                                <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">lock</span>
                                <input class="w-full pl-10 pr-10 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="password-mobile" name="password" placeholder="Minimal 8 karakter kombinasi" type="password" required=""/>
                                <button class="absolute right-3 text-on-surface-variant hover:text-on-surface flex items-center cursor-pointer" onclick="togglePassVisibility('password-mobile', 'icon-pwd')" type="button" aria-label="Tampilkan kata sandi">
                                    <span class="material-symbols-outlined text-lg" id="icon-pwd">visibility_off</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="font-label-md text-xs font-bold text-on-surface flex items-center space-x-1" for="password-confirm-mobile">
                                <span>Konfirmasi Kata Sandi</span>
                                <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined text-on-surface-variant absolute left-3 pointer-events-none text-lg">lock_reset</span>
                                <input class="w-full pl-10 pr-10 py-2.5 bg-surface-container-low rounded-lg text-xs text-on-surface placeholder:text-on-surface-variant/60 focus:bg-surface-container-lowest focus:outline-none transition-colors border-0" id="password-confirm-mobile" name="password_confirmation" placeholder="Ulangi kata sandi Anda" type="password" required=""/>
                                <button class="absolute right-3 text-on-surface-variant hover:text-on-surface flex items-center cursor-pointer" onclick="togglePassVisibility('password-confirm-mobile', 'icon-pwd-confirm')" type="button" aria-label="Tampilkan konfirmasi kata sandi">
                                    <span class="material-symbols-outlined text-lg" id="icon-pwd-confirm">visibility_off</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms Checkbox Card (Persis Screenshot) -->
                    <div class="bg-surface-container-lowest p-3.5 rounded-xl shadow-sm flex items-start space-x-3">
                        <input class="mt-1 w-4 h-4 rounded text-secondary bg-surface-container-low focus:ring-0 cursor-pointer accent-primary-container" id="terms-check" required="" type="checkbox"/>
                        <label class="font-body-sm text-xs text-on-surface leading-tight cursor-pointer select-none" for="terms-check">
                            Saya menyetujui <a class="text-secondary font-bold underline" href="javascript:void(0)" onclick="alert('Syarat & Ketentuan Layanan berlaku untuk seluruh transaksi resmi Buana Group.')">Syarat &amp; Ketentuan</a> serta <a class="text-secondary font-bold underline" href="javascript:void(0)" onclick="alert('Kebijakan Privasi menjamin keamanan data pengguna sesuai UU PDP No. 27 Tahun 2022.')">Kebijakan Privasi</a> pengelolaan akun Buana Group.
                        </label>
                    </div>

                    <!-- Submit Button Mobile (Persis Screenshot) -->
                    <div class="pt-1">
                        <button class="w-full py-3.5 px-4 bg-primary-container hover:bg-primary text-on-primary rounded-xl font-bold text-sm flex items-center justify-center space-x-2 shadow-md active:scale-[0.99] transition-transform duration-100 cursor-pointer" type="submit">
                            <span class="material-symbols-outlined text-lg">person_add</span>
                            <span>Daftar Sekarang</span>
                        </button>
                    </div>
                </form>

                <!-- Footer Switch to Login (Persis Screenshot) -->
                <div class="text-center py-3.5 bg-surface-container-lowest rounded-xl shadow-sm">
                    <p class="font-body-md text-xs text-on-surface-variant">
                        Sudah punya akun?
                        <a class="text-secondary font-bold inline-flex items-center ml-1" href="{{ route('login') }}">
                            <span>Masuk di sini</span>
                            <span class="material-symbols-outlined text-sm ml-0.5">arrow_forward</span>
                        </a>
                    </p>
                </div>

                <!-- Security & Institutional Assurance Badge (Persis Screenshot) -->
                <div class="flex items-center justify-center space-x-2 py-2 text-on-surface-variant/70 text-xs">
                    <span class="material-symbols-outlined text-sm">shield</span>
                    <span>Sistem Terintegrasi Universitas Wahid Hasyim Semarang</span>
                </div>

            </div>
        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- SCRIPT LOGIKA ANTARMUKA & INTERAKSI REGISTRASI                           -->
    <!-- ========================================================================= -->
    <script>
        // 1. Toggle Password Visibility
        function togglePassVisibility(inputId, target) {
            const input = document.getElementById(inputId);
            if (!input) return;

            let icon = null;
            if (typeof target === 'string') {
                icon = document.getElementById(target);
            } else if (target && target.querySelector) {
                icon = target.querySelector('.material-symbols-outlined');
            }

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.textContent = 'visibility_off';
            } else {
                input.type = 'password';
                if (icon) icon.textContent = 'visibility';
            }
        }

        // 2. Handler Pilihan Peran Desktop (Persis Screenshot)
        function selectDesktopRole(role) {
            const btnUnwahas = document.getElementById('desk-role-unwahas');
            const btnUmum = document.getElementById('desk-role-umum');
            const btnInstansi = document.getElementById('desk-role-instansi');

            const dotUnwahas = document.getElementById('desk-dot-unwahas');
            const dotUmum = document.getElementById('desk-dot-umum');
            const dotInstansi = document.getElementById('desk-dot-instansi');

            const titleUnwahas = document.getElementById('desk-title-unwahas');
            const titleUmum = document.getElementById('desk-title-umum');
            const titleInstansi = document.getElementById('desk-title-instansi');

            const subUnwahas = document.getElementById('desk-sub-unwahas');
            const subUmum = document.getElementById('desk-sub-umum');
            const subInstansi = document.getElementById('desk-sub-instansi');

            const iconBoxUnwahas = document.getElementById('desk-icon-box-unwahas');
            const iconBoxUmum = document.getElementById('desk-icon-box-umum');
            const iconBoxInstansi = document.getElementById('desk-icon-box-instansi');

            const banner = document.getElementById('civitas-verification-box');
            const bannerTitle = document.getElementById('civitas-box-title');
            const bannerDesc = document.getElementById('civitas-box-desc');
            const bannerIcon = document.getElementById('civitas-box-icon');

            const civitasFieldGroup = document.getElementById('civitasFieldGroup');
            const labelNimNip = document.getElementById('label-nim-nip');
            const badgeNim = document.getElementById('badge-nim-desktop');
            const reqStar = document.getElementById('req-star-desktop');
            const inputNim = document.getElementById('reg-nim-nip');
            const hiddenRole = document.getElementById('desktop-role-input');

            if (hiddenRole) hiddenRole.value = role;

            // Reset all 3 to unselected style
            [btnUnwahas, btnUmum, btnInstansi].forEach(b => {
                if (b) b.className = 'p-3 rounded-xl border border-surface-container-high bg-surface-container-lowest text-on-surface text-left transition-all h-full flex flex-col justify-between hover:border-outline cursor-pointer';
            });
            [dotUnwahas, dotUmum, dotInstansi].forEach(d => { if (d) d.classList.add('hidden'); });
            [titleUnwahas, titleUmum, titleInstansi].forEach(t => { if (t) t.className = 'font-label-md text-sm font-bold block leading-tight text-on-surface'; });
            [subUnwahas, subUmum, subInstansi].forEach(s => { if (s) s.className = 'text-[11px] block mt-0.5 text-on-surface-variant'; });
            [iconBoxUnwahas, iconBoxUmum, iconBoxInstansi].forEach(i => { if (i) i.className = 'w-7 h-7 rounded-lg bg-surface-container text-on-surface-variant flex items-center justify-center'; });

            if (role === 'unwahas' || role === 'civitas') {
                if (btnUnwahas) btnUnwahas.className = 'p-3 rounded-xl border border-primary bg-primary text-on-primary text-left transition-all h-full flex flex-col justify-between shadow-sm cursor-pointer';
                if (dotUnwahas) dotUnwahas.classList.remove('hidden');
                if (titleUnwahas) titleUnwahas.className = 'font-label-md text-sm font-bold block leading-tight text-white';
                if (subUnwahas) subUnwahas.className = 'text-[11px] block mt-0.5 text-on-primary-container';
                if (iconBoxUnwahas) iconBoxUnwahas.className = 'w-7 h-7 rounded-lg bg-white/15 text-white flex items-center justify-center';

                if (banner) banner.classList.remove('hidden');
                if (bannerTitle) bannerTitle.textContent = 'Fasilitas Khusus Civitas UNWAHAS';
                if (bannerDesc) bannerDesc.textContent = 'Otomatis mendeteksi tarif khusus internal, fasilitas SPJ terpusat terintegrasi fakultas, dan verifikasi akun instan via portal akademik.';
                if (bannerIcon) bannerIcon.textContent = 'verified_user';

                if (civitasFieldGroup) civitasFieldGroup.classList.remove('hidden');
                if (labelNimNip) labelNimNip.textContent = 'NIM / NIP';
                if (badgeNim) { badgeNim.textContent = 'Civitas Verification'; badgeNim.className = 'inline-flex items-center gap-1 text-[11px] text-primary font-bold bg-[#d8e3fb] text-[#005e3a] px-2.5 py-0.5 rounded-full border border-primary/20'; }
                if (reqStar) reqStar.style.display = 'inline';
                if (inputNim) { inputNim.placeholder = 'Contoh: 21102001 (NIM) atau 198501... (NIP)'; inputNim.disabled = false; }
            } else if (role === 'umum') {
                if (btnUmum) btnUmum.className = 'p-3 rounded-xl border border-primary bg-primary text-on-primary text-left transition-all h-full flex flex-col justify-between shadow-sm cursor-pointer';
                if (dotUmum) dotUmum.classList.remove('hidden');
                if (titleUmum) titleUmum.className = 'font-label-md text-sm font-bold block leading-tight text-white';
                if (subUmum) subUmum.className = 'text-[11px] block mt-0.5 text-on-primary-container';
                if (iconBoxUmum) iconBoxUmum.className = 'w-7 h-7 rounded-lg bg-white/15 text-white flex items-center justify-center';

                if (banner) banner.classList.remove('hidden');
                if (bannerTitle) bannerTitle.textContent = 'Akun Pelanggan Umum (B2C)';
                if (bannerDesc) bannerDesc.textContent = 'Pemesanan Air Mineral galon/botol, Snack Box katering, tiket bus wisata, dan seragam dapat dilakukan bebas tanpa syarat khusus.';
                if (bannerIcon) bannerIcon.textContent = 'person';

                if (civitasFieldGroup) civitasFieldGroup.classList.remove('hidden');
                if (labelNimNip) labelNimNip.textContent = 'Nomor Identitas (KTP / Paspor)';
                if (badgeNim) { badgeNim.textContent = 'Opsional'; badgeNim.className = 'inline-flex items-center gap-1 text-[11px] text-on-surface-variant font-medium bg-surface-container px-2 py-0.5 rounded-full'; }
                if (reqStar) reqStar.style.display = 'none';
                if (inputNim) { inputNim.placeholder = 'Bisa dikosongkan untuk pendaftaran umum'; inputNim.disabled = false; }
            } else if (role === 'instansi' || role === 'mitra') {
                if (btnInstansi) btnInstansi.className = 'p-3 rounded-xl border border-primary bg-primary text-on-primary text-left transition-all h-full flex flex-col justify-between shadow-sm cursor-pointer';
                if (dotInstansi) dotInstansi.classList.remove('hidden');
                if (titleInstansi) titleInstansi.className = 'font-label-md text-sm font-bold block leading-tight text-white';
                if (subInstansi) subInstansi.className = 'text-[11px] block mt-0.5 text-on-primary-container';
                if (iconBoxInstansi) iconBoxInstansi.className = 'w-7 h-7 rounded-lg bg-white/15 text-white flex items-center justify-center';

                if (banner) banner.classList.remove('hidden');
                if (bannerTitle) bannerTitle.textContent = 'Kemitraan Instansi & Kantor (B2B)';
                if (bannerDesc) bannerDesc.textContent = 'Nikmati fasilitas pengadaan tempo, faktur pajak resmi, penagihan invoice kantor, dan diskon volume pesanan besar.';
                if (bannerIcon) bannerIcon.textContent = 'storefront';

                if (civitasFieldGroup) civitasFieldGroup.classList.remove('hidden');
                if (labelNimNip) labelNimNip.textContent = 'ID Mitra / NPWP Perusahaan';
                if (badgeNim) { badgeNim.textContent = 'ID Mitra'; badgeNim.className = 'inline-flex items-center gap-1 text-[11px] text-primary font-bold bg-[#d8e3fb] text-[#005e3a] px-2 py-0.5 rounded-full border border-primary/20'; }
                if (reqStar) reqStar.style.display = 'none';
                if (inputNim) { inputNim.placeholder = 'Nomor Pokok Perusahaan / ID Instansi Mitra'; inputNim.disabled = false; }
            }
        }

        // 3. Handler Pilihan Peran Mobile (Persis Screenshot)
        function selectRoleOption(role) {
            const btnCivitas = document.getElementById('role-civitas');
            const btnUmum = document.getElementById('role-umum');
            const btnMitra = document.getElementById('role-mitra');

            const iconCivitas = document.getElementById('role-icon-civitas');
            const iconUmum = document.getElementById('role-icon-umum');
            const iconMitra = document.getElementById('role-icon-mitra');

            const banner = document.getElementById('role-banner');
            const bannerIcon = document.getElementById('role-banner-icon');
            const bannerText = document.getElementById('role-banner-text');

            const civitasCard = document.getElementById('civitas-id-card');
            const reqStar = document.getElementById('academic-id-required');
            const badgeCivitas = document.getElementById('badge-academic-civitas');
            const inputId = document.getElementById('academic-id');
            const hiddenRole = document.getElementById('mobile-role-input');

            // Reset Styles
            [btnCivitas, btnUmum, btnMitra].forEach(b => {
                if (b) {
                    b.className = 'w-full p-2.5 rounded-xl border border-transparent bg-surface-container-low hover:bg-surface-container text-left flex items-center justify-between transition-all duration-200 cursor-pointer';
                }
            });

            if (iconCivitas) { iconCivitas.textContent = 'radio_button_unchecked'; iconCivitas.className = 'material-symbols-outlined text-on-surface-variant/40 text-xl'; }
            if (iconUmum) { iconUmum.textContent = 'radio_button_unchecked'; iconUmum.className = 'material-symbols-outlined text-on-surface-variant/40 text-xl'; }
            if (iconMitra) { iconMitra.textContent = 'radio_button_unchecked'; iconMitra.className = 'material-symbols-outlined text-on-surface-variant/40 text-xl'; }

            if (hiddenRole) hiddenRole.value = role;

            if (role === 'civitas' || role === 'unwahas') {
                if (btnCivitas) btnCivitas.className = 'w-full p-2.5 rounded-xl border border-secondary bg-secondary-fixed/20 text-left flex items-center justify-between transition-all duration-200 cursor-pointer';
                if (iconCivitas) { iconCivitas.textContent = 'check_circle'; iconCivitas.className = 'material-symbols-outlined text-primary text-xl'; }
                
                if (banner) banner.className = 'mt-3 p-3 bg-secondary-fixed/20 rounded-lg flex items-start space-x-2.5 transition-all';
                if (bannerIcon) { bannerIcon.textContent = 'verified_user'; bannerIcon.className = 'material-symbols-outlined text-secondary text-sm shrink-0 mt-0.5'; }
                if (bannerText) bannerText.textContent = 'Khusus civitas UNWAHAS: Dapatkan harga khusus internal, fasilitas SPJ terpusat, & integrasi akun akademik.';
                
                if (civitasCard) civitasCard.classList.remove('hidden');
                if (reqStar) reqStar.style.display = 'inline';
                if (badgeCivitas) badgeCivitas.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-error inline-block"></span> Civitas UNWAHAS';
                if (inputId) { inputId.placeholder = 'Masukkan NIM atau NIP resmi Anda'; inputId.disabled = false; }
            } else if (role === 'umum') {
                if (btnUmum) btnUmum.className = 'w-full p-2.5 rounded-xl border border-secondary bg-secondary-fixed/20 text-left flex items-center justify-between transition-all duration-200 cursor-pointer';
                if (iconUmum) { iconUmum.textContent = 'check_circle'; iconUmum.className = 'material-symbols-outlined text-primary text-xl'; }
                
                if (banner) banner.className = 'mt-3 p-3 bg-surface-container rounded-lg flex items-start space-x-2.5 transition-all';
                if (bannerIcon) { bannerIcon.textContent = 'info'; bannerIcon.className = 'material-symbols-outlined text-outline text-sm shrink-0 mt-0.5'; }
                if (bannerText) bannerText.textContent = 'Akun Umum dapat memesan Katering, Air Mineral Kemasan, Tiket Wisata, dan Konveksi untuk kebutuhan personal maupun organisasi.';
                
                if (civitasCard) civitasCard.classList.remove('hidden');
                if (reqStar) reqStar.style.display = 'none';
                if (badgeCivitas) badgeCivitas.textContent = 'Opsional';
                if (inputId) { inputId.placeholder = 'Bisa dikosongkan untuk pendaftaran umum'; inputId.disabled = false; }
            } else if (role === 'mitra') {
                if (btnMitra) btnMitra.className = 'w-full p-2.5 rounded-xl border border-secondary bg-secondary-fixed/20 text-left flex items-center justify-between transition-all duration-200 cursor-pointer';
                if (iconMitra) { iconMitra.textContent = 'check_circle'; iconMitra.className = 'material-symbols-outlined text-primary text-xl'; }
                
                if (banner) banner.className = 'mt-3 p-3 bg-secondary-fixed/20 rounded-lg flex items-start space-x-2.5 transition-all';
                if (bannerIcon) { bannerIcon.textContent = 'corporate_fare'; bannerIcon.className = 'material-symbols-outlined text-secondary text-sm shrink-0 mt-0.5'; }
                if (bannerText) bannerText.textContent = 'Akun Mitra / Instansi: Nikmati fasilitas tempo invoice perusahaan, diskon kuantiti besar, dan layanan kontrak berkala.';
                
                if (civitasCard) civitasCard.classList.remove('hidden');
                if (reqStar) reqStar.style.display = 'none';
                if (badgeCivitas) badgeCivitas.textContent = 'ID Mitra';
                if (inputId) { inputId.placeholder = 'Nomor Pokok Perusahaan / ID Instansi Mitra'; inputId.disabled = false; }
            }
        }
    </script>
</body>
</html>
