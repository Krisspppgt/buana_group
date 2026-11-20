<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta content="web_blank" name="shell-type"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
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
                font-family: 'Plus Jakarta Sans', sans-serif;
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
                        "on-error-container": "#93000a",
                        "surface-variant": "#d8e3fb",
                        "tertiary": "#323d35",
                        "surface-container-highest": "#d8e3fb",
                        "on-tertiary-fixed": "#131e17",
                        "primary-container": "#005e3a",
                        "secondary-container": "#6cf8bb",
                        "surface-container-high": "#dee8ff",
                        "on-tertiary-fixed-variant": "#3e4a41",
                        "on-secondary-fixed-variant": "#005236",
                        "on-background": "#111c2d",
                        "surface-dim": "#cfdaf2",
                        "secondary": "#006c49",
                        "inverse-primary": "#89d7a9",
                        "error": "#ba1a1a",
                        "on-tertiary": "#ffffff",
                        "error-container": "#ffdad6",
                        "on-surface": "#111c2d",
                        "on-primary-fixed-variant": "#005232",
                        "outline": "#6f7a71",
                        "secondary-fixed": "#6ffbbe",
                        "primary-fixed": "#a4f3c4",
                        "surface-container-lowest": "#ffffff",
                        "on-error": "#ffffff",
                        "secondary-fixed-dim": "#4edea3",
                        "surface": "#f9f9ff",
                        "on-tertiary-container": "#bbc8bd",
                        "surface-container": "#e7eeff",
                        "tertiary-container": "#48544b",
                        "outline-variant": "#bfc9c0",
                        "tertiary-fixed": "#d9e6da",
                        "on-surface-variant": "#3f4942",
                        "tertiary-fixed-dim": "#bdcabe",
                        "on-secondary-container": "#00714d",
                        "inverse-on-surface": "#ecf1ff",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed": "#002113",
                        "on-primary": "#ffffff",
                        "surface-tint": "#196b46",
                        "on-primary-fixed": "#002111",
                        "primary-fixed-dim": "#89d7a9",
                        "primary": "#004429",
                        "inverse-surface": "#263143",
                        "surface-bright": "#f9f9ff",
                        "on-primary-container": "#87d5a7",
                        "surface-container-low": "#f0f3ff",
                        "background": "#f9f9ff"
                    },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                    spacing: {
                        "space-md": "1rem",
                        "space-sm": "0.5rem",
                        "margin": "1rem",
                        "gutter": "1rem",
                        "space-lg": "1.25rem",
                        "space-xs": "0.25rem",
                        "space-xl": "1.75rem"
                    },
                    fontFamily: {
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "body-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "title-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "title-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    fontSize: {
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "label-md": ["13px", { lineHeight: "18px", fontWeight: "600" }],
                        "headline-sm": ["18px", { lineHeight: "24px", fontWeight: "700" }],
                        "body-sm": ["12px", { lineHeight: "16px", fontWeight: "400" }],
                        "headline-md": ["22px", { lineHeight: "30px", fontWeight: "700" }],
                        "title-md": ["16px", { lineHeight: "22px", fontWeight: "600" }],
                        "headline-lg": ["30px", { lineHeight: "38px", fontWeight: "700" }],
                        "title-sm": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "label-sm": ["11px", { lineHeight: "14px", fontWeight: "500" }]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-background font-body-md text-on-surface antialiased">

    <!-- ========================================================================= -->
    <!-- 1. TAMPILAN WEB (Desktop & Layar Lebar >= lg) - PERSIS DESAIN USER         -->
    <!-- ========================================================================= -->
    <div class="hidden lg:flex min-h-screen w-full flex-col lg:flex-row">
        <!-- LEFT ASIDE (40%) -->
        <aside class="w-full lg:w-[40%] bg-gradient-to-b from-primary to-primary-container text-on-primary flex flex-col justify-between p-8 lg:p-12 relative overflow-hidden select-none">
            <div class="absolute -right-16 -top-16 w-80 h-80 rounded-full bg-secondary-container opacity-5 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-64 h-64 rounded-full bg-primary-fixed opacity-10 blur-2xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-surface-container-lowest flex items-center justify-center shadow-[0_1px_8px_rgba(0,0,0,0.06)]">
                        <span class="material-symbols-outlined text-primary text-2xl">domain</span>
                    </div>
                    <div>
                        <span class="font-headline-sm text-headline-sm text-on-primary tracking-tight block">Buana Group</span>
                        <span class="font-label-sm text-label-sm text-on-primary-container tracking-wide uppercase">UNWAHAS &amp; Enterprise</span>
                    </div>
                </div>
            </div>
            <div class="relative z-10 my-auto py-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-surface-container-lowest/10 backdrop-blur-md text-on-primary font-label-sm text-label-sm mb-6">
                    <span class="w-2 h-2 rounded-full bg-secondary-container"></span>
                    Akses Terpadu Sivitas &amp; Mitra
                </div>
                <h1 class="font-headline-lg text-headline-lg text-on-primary mb-4 leading-tight">
                    Gabung Ekosistem Layanan Buana Group
                </h1>
                <p class="font-body-lg text-body-lg text-on-primary-container max-w-md">
                    Satu akun untuk seluruh integrasi pengadaan, katering, transportasi kampus, dan layanan komersial terpercaya.
                </p>
            </div>
            <div class="relative z-10 pt-6">
                <p class="font-label-sm text-label-sm text-on-primary-container">
                    © PT Buana Sejahtera Semarang. Hak Cipta Dilindungi.
                </p>
            </div>
        </aside>

        <!-- RIGHT MAIN (60%) -->
        <main class="w-full lg:w-[60%] bg-surface flex items-center justify-center p-6 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-[500px] mx-auto py-4">
                <!-- Top Segmented Header & Switcher -->
                <div class="flex items-center justify-between mb-6">
                    <div class="bg-surface-container-low p-1 rounded-xl flex items-center gap-1 w-full max-w-[210px] shadow-sm">
                        <a href="{{ route('login') }}" class="flex-1 py-1.5 px-3 rounded-lg text-center font-label-md text-label-md text-on-surface-variant hover:text-on-surface transition-colors" type="button">
                            Masuk
                        </a>
                        <button class="flex-1 py-1.5 px-3 rounded-lg text-center font-label-md text-label-md bg-surface-container-lowest text-primary shadow-sm cursor-default" type="button">
                            Daftar
                        </button>
                    </div>
                </div>

                <!-- Heading -->
                <div class="mb-5">
                    <h2 class="font-headline-md text-headline-md text-on-surface tracking-tight mb-1">Buat Akun Baru</h2>
                    <p class="font-body-md text-body-md text-on-surface-variant" id="desktop-subtitle">Pilih kategori kamu untuk memulai.</p>
                </div>

                <!-- Category Selector: 3 Cards in 1 Row -->
                <div class="grid grid-cols-3 gap-2.5 mb-6" id="categoryGroupDesktop">
                    <!-- Card 1: Civitas UNWAHAS -->
                    <button id="cat-card-desktop-unwahas" onclick="setCategory('unwahas')" class="relative text-left p-3 rounded-xl bg-primary-fixed/20 shadow-sm transition-all flex flex-col justify-between h-[106px] group focus:outline-none ring-2 ring-primary cursor-pointer" type="button">
                        <div class="flex items-center justify-between w-full mb-1">
                            <div class="w-7 h-7 rounded-lg bg-surface-container-lowest flex items-center justify-center text-primary shadow-sm">
                                <span class="material-symbols-outlined text-lg">school</span>
                            </div>
                            <span class="material-symbols-outlined text-primary text-base cat-check" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                        </div>
                        <div>
                            <p class="font-title-sm text-title-sm text-primary font-bold leading-tight cat-title">Civitas UNWAHAS</p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mt-0.5 line-clamp-1">Mahasiswa, dosen &amp; staf</p>
                        </div>
                    </button>

                    <!-- Card 2: Masyarakat Umum -->
                    <button id="cat-card-desktop-umum" onclick="setCategory('umum')" class="relative text-left p-3 rounded-xl bg-surface-container-lowest shadow-sm hover:bg-surface-container-low transition-all flex flex-col justify-between h-[106px] group focus:outline-none cursor-pointer" type="button">
                        <div class="flex items-center justify-between w-full mb-1">
                            <div class="w-7 h-7 rounded-lg bg-surface-container-low flex items-center justify-center text-on-surface-variant group-hover:text-primary">
                                <span class="material-symbols-outlined text-lg">groups</span>
                            </div>
                            <span class="w-4 h-4 rounded-full bg-surface-container-high inline-block cat-dot"></span>
                        </div>
                        <div>
                            <p class="font-title-sm text-title-sm text-on-surface font-semibold leading-tight cat-title">Masyarakat Umum</p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mt-0.5 line-clamp-1">Alumni, keluarga &amp; umum</p>
                        </div>
                    </button>

                    <!-- Card 3: Mitra / Instansi -->
                    <button id="cat-card-desktop-mitra" onclick="setCategory('mitra')" class="relative text-left p-3 rounded-xl bg-surface-container-lowest shadow-sm hover:bg-surface-container-low transition-all flex flex-col justify-between h-[106px] group focus:outline-none cursor-pointer" type="button">
                        <div class="flex items-center justify-between w-full mb-1">
                            <div class="w-7 h-7 rounded-lg bg-surface-container-low flex items-center justify-center text-on-surface-variant group-hover:text-primary">
                                <span class="material-symbols-outlined text-lg">corporate_fare</span>
                            </div>
                            <span class="w-4 h-4 rounded-full bg-surface-container-high inline-block cat-dot"></span>
                        </div>
                        <div>
                            <p class="font-title-sm text-title-sm text-on-surface font-semibold leading-tight cat-title">Mitra / Instansi</p>
                            <p class="font-label-sm text-label-sm text-on-surface-variant mt-0.5 line-clamp-1">Sekolah &amp; perusahaan</p>
                        </div>
                    </button>
                </div>

                <!-- Server Errors Alert -->
                @if ($errors->any())
                    <div class="p-3.5 mb-4 rounded-xl bg-error-container/40 border border-error/30 text-error flex items-start gap-2.5 text-xs">
                        <span class="material-symbols-outlined text-lg flex-shrink-0 text-error">error</span>
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-error">Mohon lengkapi data dengan benar:</div>
                            <ul class="list-disc list-inside mt-0.5 space-y-0.5 text-on-error-container">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Dynamic Form Desktop -->
                <form id="desktop-reg-form" method="POST" action="{{ route('register') }}" class="flex flex-col gap-3.5" onsubmit="handleFormSubmit(this)">
                    @csrf
                    <input type="hidden" name="role_register" id="desktop-role-input" value="{{ old('role_register', 'unwahas') }}"/>

                    <!-- FORM VARIATION 1: UNWAHAS -->
                    <div id="desktop-section-unwahas" class="flex flex-col gap-3.5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                Nama Lengkap <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                                    <span class="material-symbols-outlined text-lg">person</span>
                                </div>
                                <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-10 pr-4 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary placeholder:text-outline border-0" 
                                    name="name" 
                                    id="desktop-unwahas-name"
                                    placeholder="Sesuai kartu identitas / KTM" 
                                    type="text" 
                                    value="{{ old('name') }}"/>
                            </div>
                        </div>

                        <!-- 2 Col: NIM/NIP & Fakultas -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    NIM / NIP <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                                        <span class="material-symbols-outlined text-lg">badge</span>
                                    </div>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-10 pr-3 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary placeholder:text-outline border-0" 
                                        name="campus_id" 
                                        id="desktop-unwahas-campus"
                                        placeholder="Contoh: 21010118" 
                                        type="text" 
                                        value="{{ old('campus_id') }}"/>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    Fakultas / Unit <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <select name="faculty" class="w-full appearance-none bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-3.5 pr-8 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary cursor-pointer border-0">
                                        <option value="Teknik Industri">Teknik Industri</option>
                                        <option value="Teknologi Informasi">Teknologi Informasi</option>
                                        <option value="Ekonomi & Bisnis">Ekonomi &amp; Bisnis</option>
                                        <option value="Agama Islam">Agama Islam</option>
                                        <option value="Hukum">Hukum</option>
                                        <option value="Farmasi">Farmasi</option>
                                        <option value="Biro Administrasi (BAU/BAK)">Biro Administrasi (BAU/BAK)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-on-surface-variant">
                                        <span class="material-symbols-outlined text-lg">expand_more</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Col: Email Kampus & WhatsApp -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    Email Kampus <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-on-surface-variant">
                                        <span class="material-symbols-outlined text-lg">mail</span>
                                    </div>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-10 pr-3 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary placeholder:text-outline border-0" 
                                        name="email" 
                                        id="desktop-unwahas-email"
                                        placeholder="nama@unwahas.ac.id" 
                                        type="email" 
                                        value="{{ old('email') }}"/>
                                </div>
                                <p class="font-body-sm text-body-sm text-primary mt-1">Disarankan email @unwahas.ac.id</p>
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    No. WhatsApp <span class="text-error">*</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none font-label-md text-label-md text-on-surface-variant">
                                        +62
                                    </div>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-11 pr-3 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary placeholder:text-outline border-0" 
                                        name="phone" 
                                        id="desktop-unwahas-phone"
                                        placeholder="812-3456-7890" 
                                        type="tel" 
                                        value="{{ old('phone') }}"/>
                                </div>
                            </div>
                        </div>

                        <!-- Info Note Card -->
                        <div class="rounded-xl p-3 bg-surface-container-low flex items-start gap-2.5 shadow-sm mt-0.5">
                            <span class="material-symbols-outlined text-primary text-lg shrink-0 mt-0.5">schedule</span>
                            <p class="font-body-sm text-body-sm text-on-surface">
                                Akun diverifikasi admin sebelum fasilitas civitas aktif. Pastikan data NIM/NIP dan email sesuai arsip universitas.
                            </p>
                        </div>
                    </div>

                    <!-- FORM VARIATION 2: UMUM -->
                    <div id="desktop-section-umum" class="hidden flex flex-col gap-3.5">
                        <!-- Nama Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface flex items-center justify-between" for="desktop-umum-name">
                                <span>Nama Lengkap <span class="text-error">*</span></span>
                                <span class="font-label-sm text-label-sm text-on-surface-variant">Sesuai Kartu Identitas</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">badge</span>
                                <input class="w-full bg-surface-container-lowest text-on-surface rounded-xl pl-11 pr-4 py-2.5 font-body-md text-body-md shadow-sm placeholder:text-outline focus:outline-none focus:bg-surface-container-low transition-all border-0" 
                                    id="desktop-umum-name" 
                                    placeholder="Nama lengkap sesuai KTP" 
                                    type="text"/>
                            </div>
                        </div>

                        <!-- 2 Col: Email & WhatsApp -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface">Email Aktif <span class="text-error">*</span></label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">mail</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface rounded-xl pl-11 pr-4 py-2.5 font-body-md text-body-md shadow-sm placeholder:text-outline focus:outline-none focus:bg-surface-container-low transition-all border-0" 
                                        id="desktop-umum-email" 
                                        placeholder="nama@email.com" 
                                        type="email"/>
                                </div>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-md text-label-md text-on-surface">No. WhatsApp <span class="text-error">*</span></label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-on-surface-variant text-xl pointer-events-none">phone_iphone</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface rounded-xl pl-11 pr-4 py-2.5 font-body-md text-body-md shadow-sm placeholder:text-outline focus:outline-none focus:bg-surface-container-low transition-all border-0" 
                                        id="desktop-umum-phone" 
                                        placeholder="08xxxxxxxxxx" 
                                        type="tel"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FORM VARIATION 3: MITRA / INSTANSI -->
                    <div id="desktop-section-mitra" class="hidden flex flex-col gap-3.5">
                        <!-- Nama Instansi -->
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                Nama Instansi / Lembaga <span class="text-error">*</span>
                            </label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">apartment</span>
                                <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline/70 transition-all border-0" 
                                    id="desktop-mitra-company"
                                    name="company_name"
                                    placeholder="Contoh: SMA NU 1 Semarang / CV Mitra Berkah" 
                                    type="text"
                                    value="{{ old('company_name') }}"/>
                            </div>
                        </div>

                        <!-- 2 Col: PIC & Jabatan -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    Nama Lengkap PIC <span class="text-error">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">badge</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline/70 transition-all border-0" 
                                        id="desktop-mitra-pic"
                                        placeholder="Nama penanggung jawab" 
                                        type="text"/>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    Jabatan <span class="text-error">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">work</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline/70 transition-all border-0" 
                                        name="position"
                                        placeholder="Contoh: Kabag Pengadaan" 
                                        type="text"
                                        value="{{ old('position') }}"/>
                                </div>
                            </div>
                        </div>

                        <!-- 2 Col: Email Resmi & WhatsApp PIC -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    Email Resmi <span class="text-error">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">mail</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline/70 transition-all border-0" 
                                        id="desktop-mitra-email"
                                        placeholder="pengadaan@instansi.com" 
                                        type="email"/>
                                </div>
                            </div>
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                    No. WhatsApp PIC <span class="text-error">*</span>
                                </label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-3.5 text-outline text-[20px]">chat</span>
                                    <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md pl-11 pr-4 py-2.5 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary placeholder:text-outline/70 transition-all border-0" 
                                        id="desktop-mitra-phone"
                                        placeholder="08xxxxxxxxxx" 
                                        type="tel"/>
                                </div>
                            </div>
                        </div>

                        <!-- Notice Box Mitra B2B -->
                        <div class="p-3.5 rounded-xl bg-surface-container-low flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full bg-primary/10 flex items-center justify-center shrink-0 mt-0.5 text-primary">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant leading-relaxed">
                                <strong class="font-title-sm text-on-surface">Pemberitahuan Verifikasi:</strong> Akses katalog harga khusus &amp; penagihan tempo (B2B) dibuka penuh setelah identitas instansi divalidasi oleh administrator Buana Group (1x24 jam).
                            </p>
                        </div>
                    </div>

                    <!-- SHARED PASSWORD FIELDS (2 Columns) -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                Kata Sandi <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-3.5 pr-8 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary border-0" 
                                    id="desktop-pwd" 
                                    name="password" 
                                    placeholder="Min. 8 karakter, awal kapital &amp; angka" 
                                    type="password" 
                                    minlength="8"
                                    pattern="^[A-Z](?=.*\d).{7,}$"
                                    title="Minimal 8 karakter, huruf pertama harus kapital, dan harus mengandung angka"
                                    required/>
                                <button class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-on-surface-variant hover:text-on-surface cursor-pointer" type="button" onclick="togglePassVisibility('desktop-pwd', this)">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </button>
                            </div>
                            <p class="font-body-sm text-body-sm text-on-surface-variant mt-1">Min. 8 karakter, diawali huruf kapital &amp; ada angka.</p>
                        </div>
                        <div>
                            <label class="block font-label-md text-label-md text-on-surface mb-1.5">
                                Konfirmasi Sandi <span class="text-error">*</span>
                            </label>
                            <div class="relative">
                                <input class="w-full bg-surface-container-lowest text-on-surface font-body-md text-body-md rounded-xl pl-3.5 pr-8 py-2.5 shadow-sm focus:outline-none focus:bg-surface-container-lowest focus:ring-2 focus:ring-primary border-0" 
                                    id="desktop-pwd-conf" 
                                    name="password_confirmation" 
                                    placeholder="Ulangi kata sandi" 
                                    type="password" 
                                    required/>
                                <button class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-on-surface-variant hover:text-on-surface cursor-pointer" type="button" onclick="togglePassVisibility('desktop-pwd-conf', this)">
                                    <span class="material-symbols-outlined text-base">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Terms & Conditions Checkbox -->
                    <div class="flex items-start gap-2.5 pt-1">
                        <input checked="" class="mt-1 w-4 h-4 rounded text-primary focus:ring-primary accent-primary bg-surface-container-lowest cursor-pointer" id="desktop-terms" required="" type="checkbox"/>
                        <label class="font-body-sm text-body-sm text-on-surface-variant leading-snug cursor-pointer" for="desktop-terms">
                            Saya menyetujui <a class="text-primary font-semibold hover:underline" href="javascript:void(0)" onclick="alert('Syarat & Ketentuan Layanan resmi berlaku untuk seluruh unit usaha Buana Group.')">Syarat &amp; Ketentuan</a> serta <a class="text-primary font-semibold hover:underline" href="javascript:void(0)" onclick="alert('Kebijakan Privasi menjamin keamanan data pengguna sesuai UU PDP.')">Kebijakan Privasi</a> Buana Group.
                        </label>
                    </div>

                    <!-- Primary Action Button -->
                    <button class="w-full h-[50px] bg-primary text-on-primary font-title-sm text-title-sm rounded-xl font-bold flex items-center justify-center gap-2 shadow-md hover:bg-primary-container active:scale-[0.99] transition-all mt-2 cursor-pointer" id="desktop-submit-btn" type="submit">
                        <span id="desktop-submit-text">Daftar Sekarang</span>
                        <span class="material-symbols-outlined text-base" id="desktop-submit-arrow">arrow_forward</span>
                    </button>

                    <!-- Disabled Quiet Option: SSO Yayasan -->
                    <button class="w-full h-11 bg-surface-container-low text-outline font-label-md text-label-md rounded-xl flex items-center justify-center gap-2 cursor-not-allowed opacity-80" disabled="" type="button">
                        <span class="material-symbols-outlined text-base text-outline">key</span>
                        <span>Daftar dengan SSO Yayasan</span>
                        <span class="px-2 py-0.5 rounded-full bg-surface-container-high text-on-surface-variant font-label-sm text-label-sm">Segera hadir</span>
                    </button>
                </form>

                <!-- Bottom Assistance Link -->
                <div class="text-center mt-5 pt-3">
                    <a class="inline-flex items-center gap-1.5 font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20pendaftaran%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                        <span class="material-symbols-outlined text-sm text-secondary">support_agent</span>
                        <span>Butuh bantuan pendaftaran? Hubungi CS via WhatsApp</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    <!-- ========================================================================= -->
    <!-- 2. TAMPILAN MOBILE (HP & Tablet Kecil < lg) - PERSIS DESAIN MOBILE USER    -->
    <!-- ========================================================================= -->
    <div class="lg:hidden flex flex-col items-center min-h-screen pt-safe pb-safe bg-surface">
        <main class="flex flex-col relative w-full max-w-[390px] px-margin bg-surface min-h-screen">
            <div class="flex flex-col w-full pb-8">
                <!-- Header Title & Subtext -->
                <div class="pt-4 pb-3">
                    <h1 class="font-headline-md text-headline-md text-on-surface tracking-tight" id="mobile-header-title">Buat Akun Baru</h1>
                    <p class="font-body-md text-body-md text-outline mt-0.5" id="mobile-header-sub">Pilih kategori kamu untuk memulai.</p>
                </div>

                <!-- Segmented Tab Switcher (Masuk / Daftar) -->
                <div class="flex p-1 bg-surface-container rounded-xl mb-6 shadow-sm">
                    <a href="{{ route('login') }}" class="flex-1 py-2 text-center font-title-sm text-title-sm text-outline hover:text-on-surface transition-all rounded-lg" type="button">
                        Masuk
                    </a>
                    <button class="flex-1 py-2 text-center font-title-sm text-title-sm bg-surface-container-lowest text-primary shadow-sm rounded-lg flex items-center justify-center gap-1 cursor-default" type="button">
                        <span>Daftar</span>
                    </button>
                </div>

                <!-- Category Selection Section (3 Vertical Cards) -->
                <div class="flex flex-col gap-2.5 mb-6">
                    <span class="font-label-sm text-label-sm text-outline uppercase tracking-wider">Kategori Pendaftar</span>

                    <!-- Card 1: Civitas UNWAHAS -->
                    <div class="flex items-center justify-between p-3.5 bg-primary-fixed/30 rounded-xl cursor-pointer shadow-sm transition-all" id="cat-mobile-unwahas" onclick="setCategory('unwahas')">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-primary-fixed flex items-center justify-center text-primary shrink-0 shadow-sm">
                                <span class="material-symbols-outlined text-[22px]" style="font-variation-settings: 'FILL' 1;">school</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-title-sm text-title-sm text-primary font-bold">Civitas UNWAHAS</span>
                                <span class="font-body-sm text-body-sm text-on-surface-variant">Mahasiswa, dosen &amp; staf</span>
                            </div>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-on-primary shrink-0 shadow-sm mobile-check">
                            <span class="material-symbols-outlined text-[16px]">check</span>
                        </div>
                    </div>

                    <!-- Card 2: Masyarakat Umum -->
                    <div class="flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-xl cursor-pointer shadow-sm transition-all" id="cat-mobile-umum" onclick="setCategory('umum')">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-outline shrink-0">
                                <span class="material-symbols-outlined text-[22px]">person</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-title-sm text-title-sm text-on-surface">Masyarakat Umum</span>
                                <span class="font-body-sm text-body-sm text-outline">Alumni, keluarga &amp; komunitas</span>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full bg-surface-container shrink-0 mobile-check"></div>
                    </div>

                    <!-- Card 3: Mitra / Instansi -->
                    <div class="flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-xl cursor-pointer shadow-sm transition-all" id="cat-mobile-mitra" onclick="setCategory('mitra')">
                        <div class="flex items-center gap-3.5">
                            <div class="w-10 h-10 rounded-xl bg-surface-container flex items-center justify-center text-outline shrink-0">
                                <span class="material-symbols-outlined text-[22px]">domain</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-title-sm text-title-sm text-on-surface">Mitra / Instansi</span>
                                <span class="font-body-sm text-body-sm text-outline">Sekolah &amp; perusahaan</span>
                            </div>
                        </div>
                        <div class="w-5 h-5 rounded-full bg-surface-container shrink-0 mobile-check"></div>
                    </div>
                </div>

                <!-- Server Errors Alert Mobile -->
                @if ($errors->any())
                    <div class="p-3 mb-4 rounded-xl bg-error-container/40 border border-error/30 text-error flex items-start gap-2 text-xs">
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

                <!-- Dynamic Form Mobile -->
                <form id="mobile-reg-form" method="POST" action="{{ route('register') }}" class="flex flex-col gap-4" onsubmit="handleFormSubmit(this)">
                    @csrf
                    <input type="hidden" name="role_register" id="mobile-role-input" value="{{ old('role_register', 'unwahas') }}"/>

                    <!-- MOBILE FORM 1: UNWAHAS -->
                    <div id="mobile-section-unwahas" class="flex flex-col gap-4">
                        <!-- 1. Nama Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                Nama Lengkap <span class="text-error font-bold">*</span>
                            </label>
                            <input class="w-full h-12 px-4 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                id="mobile-unwahas-name"
                                name="name" 
                                placeholder="Nama lengkap sesuai KTP/KTM" 
                                type="text" 
                                value="{{ old('name') }}"/>
                        </div>

                        <!-- 2. NIM / NIP -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                NIM / NIP <span class="text-error font-bold">*</span>
                            </label>
                            <input class="w-full h-12 px-4 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                id="mobile-unwahas-campus"
                                name="campus_id" 
                                placeholder="Nomor Induk Mahasiswa / Pegawai" 
                                type="text" 
                                value="{{ old('campus_id') }}"/>
                        </div>

                        <!-- 3. Fakultas / Unit Kerja -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                Fakultas / Unit Kerja <span class="text-error font-bold">*</span>
                            </label>
                            <div class="relative w-full">
                                <select name="faculty" class="w-full h-12 px-4 pr-10 appearance-none rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none cursor-pointer focus:bg-surface-container-low transition-colors border-0">
                                    <option class="text-outline-variant" disabled="" selected="" value="">Pilih Fakultas atau Unit Kerja</option>
                                    <option value="FT">Fakultas Teknik</option>
                                    <option value="FTI">Fakultas Teknologi Informasi</option>
                                    <option value="FEB">Fakultas Ekonomika dan Bisnis</option>
                                    <option value="FAI">Fakultas Agama Islam</option>
                                    <option value="FHK">Fakultas Hukum</option>
                                    <option value="FFAR">Fakultas Farmasi</option>
                                    <option value="FKIP">Fakultas Keguruan dan Ilmu Pendidikan</option>
                                    <option value="FISIP">Fakultas Ilmu Sosial dan Ilmu Politik</option>
                                    <option value="FAPERTA">Fakultas Pertanian</option>
                                    <option value="UNIT">Unit Rektorat &amp; Lembaga Administrasi</option>
                                </select>
                                <span class="material-symbols-outlined absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-outline">
                                    expand_more
                                </span>
                            </div>
                        </div>

                        <!-- 4. Email -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                Email <span class="text-error font-bold">*</span>
                            </label>
                            <input class="w-full h-12 px-4 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                id="mobile-unwahas-email"
                                name="email" 
                                placeholder="nama@unwahas.ac.id" 
                                type="email" 
                                value="{{ old('email') }}"/>
                            <div class="flex items-center gap-1.5 px-1 mt-0.5 text-secondary">
                                <span class="material-symbols-outlined text-[15px] shrink-0" style="font-variation-settings: 'FILL' 1;">info</span>
                                <span class="font-label-sm text-label-sm">Disarankan email kampus @unwahas.ac.id</span>
                            </div>
                        </div>

                        <!-- 5. No. WhatsApp -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                No. WhatsApp <span class="text-error font-bold">*</span>
                            </label>
                            <div class="relative w-full flex items-center">
                                <div class="absolute left-3.5 flex items-center gap-1.5 pr-2.5 bg-surface-container-lowest text-on-surface-variant font-label-md text-label-md">
                                    <span>+62</span>
                                    <span class="w-1 h-3.5 bg-surface-container rounded-full"></span>
                                </div>
                                <input class="w-full h-12 pl-16 pr-4 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                    id="mobile-unwahas-phone"
                                    name="phone" 
                                    placeholder="08xxxxxxxxxx" 
                                    type="tel" 
                                    value="{{ old('phone') }}"/>
                            </div>
                        </div>

                        <!-- Info Note Card -->
                        <div class="flex items-start gap-2.5 p-3 rounded-xl bg-primary-fixed/20 text-on-surface-variant shadow-sm mt-0.5">
                            <span class="material-symbols-outlined text-secondary text-[20px] shrink-0 mt-0.5">schedule</span>
                            <p class="font-label-md text-label-md text-secondary leading-snug">
                                Akun diverifikasi admin sebelum fasilitas civitas aktif.
                            </p>
                        </div>
                    </div>

                    <!-- MOBILE FORM 2: UMUM -->
                    <div id="mobile-section-umum" class="hidden flex flex-col gap-4">
                        <!-- Field 1: Nama Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface flex items-center gap-1">
                                Nama Lengkap <span class="text-error">*</span>
                            </label>
                            <div class="flex items-center bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm focus-within:bg-surface-container-low transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2.5">badge</span>
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-umum-name" 
                                    placeholder="Nama lengkap sesuai KTP" 
                                    type="text"/>
                            </div>
                        </div>

                        <!-- Field 2: Email -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface flex items-center gap-1">
                                Email <span class="text-error">*</span>
                            </label>
                            <div class="flex items-center bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm focus-within:bg-surface-container-low transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2.5">mail</span>
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-umum-email" 
                                    placeholder="nama@email.com" 
                                    type="email"/>
                            </div>
                        </div>

                        <!-- Field 3: No. WhatsApp -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-md text-label-md text-on-surface flex items-center gap-1">
                                No. WhatsApp <span class="text-error">*</span>
                            </label>
                            <div class="flex items-center bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm focus-within:bg-surface-container-low transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant text-[20px] mr-2.5">chat</span>
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-umum-phone" 
                                    placeholder="08xxxxxxxxxx" 
                                    type="tel"/>
                            </div>
                        </div>
                    </div>

                    <!-- MOBILE FORM 3: MITRA / INSTANSI -->
                    <div id="mobile-section-mitra" class="hidden flex flex-col gap-4">
                        <!-- 1. Nama Instansi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface">
                                Nama Instansi <span class="text-error">*</span>
                            </label>
                            <div class="w-full bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm flex items-center">
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-mitra-company"
                                    name="company_name"
                                    placeholder="Contoh: SMA NU 1 Semarang / CV Mitra Berkah" 
                                    type="text"/>
                            </div>
                        </div>

                        <!-- 2. Nama Lengkap PIC -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface">
                                Nama Lengkap <span class="text-error">*</span>
                            </label>
                            <div class="w-full bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm flex items-center">
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-mitra-pic"
                                    placeholder="Nama lengkap PIC atau penanggung jawab" 
                                    type="text"/>
                            </div>
                        </div>

                        <!-- 3. Jabatan -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface">
                                Jabatan <span class="text-error">*</span>
                            </label>
                            <div class="w-full bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm flex items-center">
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    name="position"
                                    placeholder="Contoh: Kepala Bagian Pengadaan / Humas" 
                                    type="text"/>
                            </div>
                        </div>

                        <!-- 4. Email -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface">
                                Email <span class="text-error">*</span>
                            </label>
                            <div class="w-full bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm flex items-center">
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-mitra-email"
                                    placeholder="pengadaan@instansi.com" 
                                    type="email"/>
                            </div>
                        </div>

                        <!-- 5. No. WhatsApp -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface">
                                No. WhatsApp <span class="text-error">*</span>
                            </label>
                            <div class="w-full bg-surface-container-lowest rounded-xl px-3.5 py-3 shadow-sm flex items-center">
                                <input class="w-full bg-transparent font-body-md text-body-md text-on-surface placeholder:text-outline focus:outline-none border-0" 
                                    id="mobile-mitra-phone"
                                    placeholder="08xxxxxxxxxx" 
                                    type="tel"/>
                            </div>
                        </div>

                        <!-- Info Note Card -->
                        <div class="flex items-start gap-3 p-3.5 rounded-xl bg-primary-fixed/30 mt-1">
                            <span class="material-symbols-outlined text-primary text-[20px] shrink-0 mt-0.5" style="font-variation-settings: 'FILL' 1;">
                                schedule
                            </span>
                            <p class="font-label-md text-label-md text-primary font-medium leading-relaxed">
                                Akses layanan khusus mitra dibuka setelah disetujui admin.
                            </p>
                        </div>
                    </div>

                    <!-- SHARED PASSWORD FIELDS MOBILE -->
                    <div class="flex flex-col gap-4">
                        <!-- Kata Sandi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                Kata Sandi <span class="text-error font-bold">*</span>
                            </label>
                            <div class="relative w-full">
                                <input class="w-full h-12 px-4 pr-11 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                    id="mobile-pass-field" 
                                    name="password" 
                                    placeholder="Min. 8 karakter, awal kapital &amp; ada angka" 
                                    minlength="8"
                                    pattern="^[A-Z](?=.*\d).{7,}$"
                                    title="Minimal 8 karakter, huruf pertama harus kapital, dan harus mengandung angka"
                                    required="" 
                                    type="password"/>
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface p-1 flex items-center justify-center cursor-pointer" onclick="togglePassVisibility('mobile-pass-field', this)" type="button">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                            <p class="font-label-sm text-label-sm text-on-surface-variant px-1">Min. 8 karakter, huruf pertama kapital &amp; wajib mengandung angka.</p>
                        </div>

                        <!-- Konfirmasi Kata Sandi -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-title-sm text-title-sm text-on-surface flex items-center gap-1">
                                Konfirmasi Kata Sandi <span class="text-error font-bold">*</span>
                            </label>
                            <div class="relative w-full">
                                <input class="w-full h-12 px-4 pr-11 rounded-xl bg-surface-container-lowest text-on-surface font-body-md text-body-md shadow-sm outline-none placeholder:text-outline-variant focus:bg-surface-container-low transition-colors border-0" 
                                    id="mobile-confirm-field" 
                                    name="password_confirmation" 
                                    placeholder="Ulangi kata sandi" 
                                    required="" 
                                    type="password"/>
                                <button class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-on-surface p-1 flex items-center justify-center cursor-pointer" onclick="togglePassVisibility('mobile-confirm-field', this)" type="button">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Checkbox Agreement Mobile -->
                    <div class="flex items-start gap-3 mt-1 px-0.5">
                        <label class="relative flex items-center cursor-pointer mt-0.5">
                            <input checked="" class="sr-only peer" id="mobile-terms-check" required="" type="checkbox"/>
                            <div class="w-5 h-5 bg-surface-container rounded-md peer-checked:bg-primary flex items-center justify-center transition-all shadow-sm">
                                <span class="material-symbols-outlined text-on-primary text-[16px] scale-0 peer-checked:scale-100 transition-transform">check</span>
                            </div>
                        </label>
                        <label class="font-body-sm text-body-sm text-on-surface-variant cursor-pointer leading-tight select-none" for="mobile-terms-check">
                            Saya menyetujui <span class="font-title-sm text-primary underline">Syarat &amp; Ketentuan</span> dan <span class="font-title-sm text-primary underline">Kebijakan Privasi</span>
                        </label>
                    </div>

                    <!-- Primary Action Button Mobile -->
                    <button class="w-full h-[50px] bg-primary-container text-on-primary rounded-xl font-headline-sm text-headline-sm flex items-center justify-center gap-2 shadow-md hover:bg-primary active:scale-[0.99] transition-all mt-2 cursor-pointer" id="mobile-submit-btn" type="submit">
                        <span id="mobile-submit-text">Daftar Sekarang</span>
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>

                    <!-- Quiet Option: SSO Yayasan (Disabled) -->
                    <div class="flex items-center justify-center gap-2 py-2 mt-1">
                        <span class="font-body-sm text-body-sm text-outline">Daftar dengan SSO Yayasan</span>
                        <span class="px-2 py-0.5 rounded-full bg-surface-container text-outline text-[11px] font-label-sm font-semibold tracking-wide">
                            Segera hadir
                        </span>
                    </div>

                    <!-- Contact Support Footer Mobile -->
                    <div class="flex items-center justify-center gap-1.5 pt-4 text-on-surface-variant font-body-sm text-body-sm">
                        <span>Butuh bantuan?</span>
                        <a class="flex items-center gap-1 text-secondary font-title-sm text-title-sm hover:underline" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20pendaftaran%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                            <span class="material-symbols-outlined text-[17px] text-secondary" style="font-variation-settings: 'FILL' 1;">chat</span>
                            <span>Hubungi CS via WhatsApp</span>
                        </a>
                    </div>
                </form>
            </div>
        </main>
    </div>

    <!-- JavaScript Interactivity -->
    <script>
        let currentCategory = 'unwahas';

        function setCategory(type) {
            currentCategory = type;

            // 1. Update Hidden Input Values
            const deskInput = document.getElementById('desktop-role-input');
            const mobInput = document.getElementById('mobile-role-input');
            if (deskInput) deskInput.value = type;
            if (mobInput) mobInput.value = type;

            // 2. Desktop Card Selection Visuals
            const deskCats = ['unwahas', 'umum', 'mitra'];
            deskCats.forEach(c => {
                const btn = document.getElementById('cat-card-desktop-' + c);
                if (!btn) return;
                const title = btn.querySelector('.cat-title');
                const indicator = btn.querySelector('.cat-check, .cat-dot');

                if (c === type) {
                    btn.className = 'relative text-left p-3 rounded-xl bg-primary-fixed/20 shadow-sm transition-all flex flex-col justify-between h-[106px] group focus:outline-none ring-2 ring-primary cursor-pointer';
                    if (title) title.className = 'font-title-sm text-title-sm text-primary font-bold leading-tight cat-title';
                    if (indicator) {
                        indicator.outerHTML = '<span class="material-symbols-outlined text-primary text-base cat-check" style="font-variation-settings: \'FILL\' 1;">check_circle</span>';
                    }
                } else {
                    btn.className = 'relative text-left p-3 rounded-xl bg-surface-container-lowest shadow-sm hover:bg-surface-container-low transition-all flex flex-col justify-between h-[106px] group focus:outline-none cursor-pointer';
                    if (title) title.className = 'font-title-sm text-title-sm text-on-surface font-semibold leading-tight cat-title';
                    if (indicator) {
                        indicator.outerHTML = '<span class="w-4 h-4 rounded-full bg-surface-container-high inline-block cat-dot"></span>';
                    }
                }
            });

            // 3. Desktop Sections Switch
            const dUnwahas = document.getElementById('desktop-section-unwahas');
            const dUmum = document.getElementById('desktop-section-umum');
            const dMitra = document.getElementById('desktop-section-mitra');

            if (dUnwahas && dUmum && dMitra) {
                dUnwahas.classList.toggle('hidden', type !== 'unwahas');
                dUmum.classList.toggle('hidden', type !== 'umum');
                dMitra.classList.toggle('hidden', type !== 'mitra');
                dUnwahas.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'unwahas'); });
                dUmum.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'umum'); });
                dMitra.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'mitra'); });
            }

            // 4. Mobile Card Selection Visuals
            deskCats.forEach(c => {
                const el = document.getElementById('cat-mobile-' + c);
                if (!el) return;
                const title = el.querySelector('div.flex-col > span:first-child');
                const checkContainer = el.querySelector('.mobile-check');

                if (c === type) {
                    el.className = 'flex items-center justify-between p-3.5 bg-primary-fixed/30 rounded-xl cursor-pointer shadow-sm transition-all';
                    if (title) title.className = 'font-title-sm text-title-sm text-primary font-bold';
                    if (checkContainer) {
                        checkContainer.className = 'w-6 h-6 rounded-full bg-primary flex items-center justify-center text-on-primary shrink-0 shadow-sm mobile-check';
                        checkContainer.innerHTML = '<span class="material-symbols-outlined text-[16px]">check</span>';
                    }
                } else {
                    el.className = 'flex items-center justify-between p-3.5 bg-surface-container-lowest rounded-xl cursor-pointer shadow-sm transition-all';
                    if (title) title.className = 'font-title-sm text-title-sm text-on-surface';
                    if (checkContainer) {
                        checkContainer.className = 'w-5 h-5 rounded-full bg-surface-container shrink-0 mobile-check';
                        checkContainer.innerHTML = '';
                    }
                }
            });

            // 5. Mobile Sections Switch
            const mUnwahas = document.getElementById('mobile-section-unwahas');
            const mUmum = document.getElementById('mobile-section-umum');
            const mMitra = document.getElementById('mobile-section-mitra');

            if (mUnwahas && mUmum && mMitra) {
                mUnwahas.classList.toggle('hidden', type !== 'unwahas');
                mUmum.classList.toggle('hidden', type !== 'umum');
                mMitra.classList.toggle('hidden', type !== 'mitra');
                mUnwahas.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'unwahas'); });
                mUmum.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'umum'); });
                mMitra.querySelectorAll('input, select').forEach(el => { el.disabled = (type !== 'mitra'); });
            }

            // 6. Header subtext update
            const dSub = document.getElementById('desktop-subtitle');
            const mSub = document.getElementById('mobile-header-sub');
            let subText = 'Pilih kategori kamu untuk memulai.';
            if (type === 'unwahas') subText = 'Khusus mahasiswa, dosen & staf civitas akademika UNWAHAS.';
            else if (type === 'umum') subText = 'Layanan mandiri untuk alumni, keluarga & pelanggan umum.';
            else if (type === 'mitra') subText = 'Pengadaan resmi instansi sekolah, yayasan, dan perusahaan rekanan.';

            if (dSub) dSub.innerText = subText;
            if (mSub) mSub.innerText = subText;
        }

        // Form Submit Handler: sync inputs from the active section into the form submission
        function handleFormSubmit(form) {
            const isDesktop = form.id === 'desktop-reg-form';
            const prefix = isDesktop ? 'desktop-' : 'mobile-';

            // Gather inputs depending on currentCategory
            if (currentCategory === 'unwahas') {
                const name = document.getElementById(prefix + 'unwahas-name');
                const email = document.getElementById(prefix + 'unwahas-email');
                const phone = document.getElementById(prefix + 'unwahas-phone');
                const campus = document.getElementById(prefix + 'unwahas-campus');
                if (name) name.name = 'name';
                if (email) email.name = 'email';
                if (phone) phone.name = 'phone';
                if (campus) campus.name = 'campus_id';
            } else if (currentCategory === 'umum') {
                const name = document.getElementById(prefix + 'umum-name');
                const email = document.getElementById(prefix + 'umum-email');
                const phone = document.getElementById(prefix + 'umum-phone');
                if (name) name.name = 'name';
                if (email) email.name = 'email';
                if (phone) phone.name = 'phone';
            } else if (currentCategory === 'mitra') {
                const pic = document.getElementById(prefix + 'mitra-pic');
                const email = document.getElementById(prefix + 'mitra-email');
                const phone = document.getElementById(prefix + 'mitra-phone');
                if (pic) pic.name = 'name';
                if (email) email.name = 'email';
                if (phone) phone.name = 'phone';
            }

            // Spinner / Loading state
            const btn = isDesktop ? document.getElementById('desktop-submit-btn') : document.getElementById('mobile-submit-btn');
            const text = isDesktop ? document.getElementById('desktop-submit-text') : document.getElementById('mobile-submit-text');
            if (btn && text) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                text.innerText = 'Mendaftarkan...';
            }
        }

        // Toggle password visibility
        function togglePassVisibility(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('.material-symbols-outlined') || btn;
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }

        // Initialize with old input category if any
        document.addEventListener('DOMContentLoaded', function() {
            const initialCat = "{{ old('role_register', 'unwahas') }}";
            setCategory(initialCat);
        });
    </script>
</body>
</html>
