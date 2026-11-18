<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta content="web_blank" name="shell-type"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Masuk - Buana Group | Universitas Wahid Hasyim</title>

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
                        "outline-variant": "#bfc9c0",
                        "on-background": "#111c2d",
                        "tertiary-container": "#48544b",
                        "tertiary-fixed": "#d9e6da",
                        "surface-container-lowest": "#ffffff",
                        "surface-dim": "#cfdaf2",
                        "secondary-container": "#6cf8bb",
                        "error-container": "#ffdad6",
                        "surface": "#f9f9ff",
                        "on-tertiary-fixed-variant": "#3e4a41",
                        "on-tertiary-container": "#bbc8bd",
                        "primary-container": "#005e3a",
                        "surface-container": "#e7eeff",
                        "on-primary-fixed-variant": "#005232",
                        "on-error": "#ffffff",
                        "surface-variant": "#d8e3fb",
                        "on-primary-container": "#87d5a7",
                        "on-secondary-fixed": "#002113",
                        "inverse-on-surface": "#ecf1ff",
                        "inverse-surface": "#263143",
                        "on-surface": "#111c2d",
                        "surface-bright": "#f9f9ff",
                        "background": "#f9f9ff",
                        "primary-fixed-dim": "#89d7a9",
                        "surface-container-low": "#f0f3ff",
                        "secondary": "#006c49",
                        "primary": "#004429",
                        "inverse-primary": "#89d7a9",
                        "on-error-container": "#93000a",
                        "on-tertiary-fixed": "#131e17",
                        "surface-tint": "#196b46",
                        "on-tertiary": "#ffffff",
                        "primary-fixed": "#a4f3c4",
                        "on-secondary-fixed-variant": "#005236",
                        "surface-container-high": "#dee8ff",
                        "secondary-fixed": "#6ffbbe",
                        "tertiary": "#323d35",
                        "surface-container-highest": "#d8e3fb",
                        "on-secondary": "#ffffff",
                        "on-surface-variant": "#3f4942",
                        "secondary-fixed-dim": "#4edea3",
                        "outline": "#6f7a71",
                        "on-primary-fixed": "#002111",
                        "error": "#ba1a1a",
                        "on-primary": "#ffffff",
                        "tertiary-fixed-dim": "#bdcabe",
                        "on-secondary-container": "#00714d"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "space-lg": "1.25rem",
                        "space-sm": "0.5rem",
                        "space-xs": "0.25rem",
                        "space-xl": "1.75rem",
                        "margin": "1rem",
                        "space-md": "1rem",
                        "gutter": "1rem"
                    },
                    fontFamily: {
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "label-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "title-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "title-md": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-sm": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    fontSize: {
                        "headline-md": ["22px", { lineHeight: "30px", fontWeight: "700" }],
                        "label-sm": ["11px", { lineHeight: "14px", fontWeight: "500" }],
                        "body-md": ["14px", { lineHeight: "20px", fontWeight: "400" }],
                        "headline-sm": ["18px", { lineHeight: "24px", fontWeight: "700" }],
                        "headline-lg": ["30px", { lineHeight: "38px", fontWeight: "700" }],
                        "title-sm": ["14px", { lineHeight: "20px", fontWeight: "600" }],
                        "title-md": ["16px", { lineHeight: "22px", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "24px", fontWeight: "400" }],
                        "body-sm": ["12px", { lineHeight: "16px", fontWeight: "400" }],
                        "label-md": ["13px", { lineHeight: "18px", fontWeight: "600" }]
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-surface font-body-md text-body-md text-on-surface min-h-screen antialiased selection:bg-secondary-container selection:text-on-secondary-container">

    <!-- ========================================================================= -->
    <!-- 1. DESKTOP VIEW (Layar Lebar >= lg) - Split 40% / 60%                     -->
    <!-- ========================================================================= -->
    <main class="hidden lg:flex w-full min-h-screen flex-col justify-center items-stretch bg-surface">
        <div class="flex flex-col w-full">
            <div class="w-full min-h-screen flex flex-col lg:flex-row bg-surface">
                <!-- LEFT PANEL (40% width on Desktop) -->
                <div class="lg:w-[40%] w-full bg-gradient-to-b from-primary to-primary-container text-on-primary p-8 lg:p-16 flex flex-col justify-between relative overflow-hidden select-none">
                    <!-- Soft ambient decorative glow -->
                    <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-secondary-container/10 blur-3xl pointer-events-none"></div>
                    <div class="absolute -left-20 bottom-12 w-80 h-80 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>
                    
                    <!-- Top Branding -->
                    <div class="relative z-10 flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-xl bg-surface-container-lowest/15 backdrop-blur-md flex items-center justify-center text-primary-fixed shadow-sm">
                                <span class="material-symbols-outlined text-[26px]">eco</span>
                            </div>
                            <div>
                                <div class="font-headline-sm text-headline-sm tracking-wide text-on-primary">BUANA GROUP</div>
                                <div class="font-label-sm text-label-sm tracking-wider uppercase text-on-primary-container/90">Portal Layanan Terpadu</div>
                            </div>
                        </div>
                        <p class="font-label-sm text-label-sm text-on-primary-container/80 pl-14">
                            PT Buana Sejahtera Semarang • Yayasan Wahid Hasyim
                        </p>
                    </div>

                    <!-- Center Typography Section -->
                    <div class="relative z-10 my-16 lg:my-0 max-w-lg">
                        <h1 class="font-headline-lg text-headline-lg leading-tight tracking-tight text-on-primary mb-5">
                            Satu Pintu untuk Semua Layanan Buana Group
                        </h1>
                        <p class="font-body-lg text-body-lg text-on-primary-container/90 leading-relaxed max-w-md">
                            Pusat pemesanan katering, air mineral higienis, transportasi wisata, konveksi seragam, dan logistik terintegrasi kampus.
                        </p>
                    </div>

                    <!-- Bottom Attribution -->
                    <div class="relative z-10">
                        <p class="font-body-sm text-body-sm text-on-primary-container/75">
                            © PT Buana Sejahtera Semarang
                        </p>
                    </div>
                </div>

                <!-- RIGHT PANEL (60% width on Desktop) -->
                <div class="lg:w-[60%] w-full bg-surface-container-lowest flex items-center justify-center p-6 md:p-12 lg:p-16">
                    <div class="w-full max-w-[420px] flex flex-col gap-6">
                        <!-- Header -->
                        <div class="flex flex-col gap-1.5">
                            <h2 class="font-headline-md text-headline-md text-on-background">
                                Selamat Datang
                            </h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">
                                Masuk untuk memesan layanan Buana Group.
                            </p>
                        </div>

                        <!-- Segmented Tab Switcher -->
                        <div class="w-full bg-surface-container-low p-1 rounded-xl flex items-center shadow-inner">
                            <button class="w-1/2 py-2 text-center font-title-sm text-title-sm rounded-lg bg-surface-container-lowest text-secondary shadow-sm transition-all duration-200 cursor-default" id="tab-masuk" type="button">
                                Masuk
                            </button>
                            <a href="{{ route('register') }}" class="w-1/2 py-2 text-center font-title-sm text-title-sm rounded-lg text-outline transition-all duration-200 hover:text-on-surface" id="tab-daftar">
                                Daftar
                            </a>
                        </div>

                        <!-- Status / Success Alert -->
                        @if (session('status') || session('success'))
                            <div class="p-3.5 rounded-xl bg-secondary-fixed/40 border border-secondary text-primary flex items-center gap-2.5 text-xs shadow-xs">
                                <span class="material-symbols-outlined text-lg flex-shrink-0 text-secondary">check_circle</span>
                                <span class="font-medium">{{ session('status') ?? session('success') }}</span>
                            </div>
                        @endif

                        <!-- Form Elements -->
                        <form class="flex flex-col gap-6" id="auth-form" method="POST" action="{{ route('login') }}" onsubmit="handleDesktopSubmit(event)">
                            @csrf

                            <!-- Field 1: Email / NIM / NIP / No. HP -->
                            <div class="flex flex-col gap-1.5" id="field-group-identifier">
                                <label class="font-title-sm text-title-sm text-on-background" for="input-identifier">
                                    Email / NIM / NIP / No. HP
                                </label>
                                <div class="relative flex items-center">
                                    <input class="w-full h-[48px] px-4 rounded-xl font-body-md text-body-md bg-surface-container-lowest text-on-surface shadow-sm focus:outline-none transition-all placeholder:text-outline/60 ring-1 @if($errors->has('account') || $errors->has('phone') || $errors->has('email')) ring-2 ring-error bg-error-container/10 @else ring-surface-variant focus:ring-2 focus:ring-secondary @endif" 
                                        id="input-identifier" 
                                        name="account" 
                                        placeholder="contoh: nama@email.com atau 0812xxxx" 
                                        type="text" 
                                        value="{{ old('account', old('phone', old('email'))) }}" 
                                        required/>
                                </div>
                                <!-- Context Helper Note -->
                                <div class="items-center gap-1.5 text-secondary pt-0.5 flex" id="identifier-helper">
                                    <span class="material-symbols-outlined text-[16px] text-secondary">info</span>
                                    <span class="font-body-sm text-body-sm text-secondary">Civitas UNWAHAS bisa masuk dengan NIM atau NIP.</span>
                                </div>
                                <!-- Error Container Identifier -->
                                @if ($errors->has('account') || $errors->has('phone') || $errors->has('email'))
                                    <div class="items-center gap-1.5 text-error pt-0.5 flex" id="identifier-error">
                                        <span class="material-symbols-outlined text-[16px] text-error">error</span>
                                        <span class="font-body-sm text-body-sm text-error">{{ $errors->first('account') ?: ($errors->first('phone') ?: $errors->first('email')) }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Field 2: Kata Sandi -->
                            <div class="flex flex-col gap-1.5" id="field-group-password">
                                <label class="font-title-sm text-title-sm text-on-background" for="input-password">
                                    Kata Sandi
                                </label>
                                <div class="relative flex items-center">
                                    <input class="w-full h-[48px] pl-4 pr-12 rounded-xl font-body-md text-body-md bg-surface-container-lowest text-on-surface shadow-sm focus:outline-none transition-all placeholder:text-outline/60 ring-1 @error('password') ring-2 ring-error bg-error-container/10 @else ring-surface-variant focus:ring-2 focus:ring-secondary @endif" 
                                        id="input-password" 
                                        name="password" 
                                        placeholder="Masukkan kata sandi Anda" 
                                        type="password" 
                                        required/>
                                    <button aria-label="Tampilkan atau sembunyikan kata sandi" class="absolute right-3.5 p-1 text-outline hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer" id="toggle-password" onclick="togglePasswordVisibility('input-password', 'eye-icon')" type="button">
                                        <span class="material-symbols-outlined text-[20px]" id="eye-icon">visibility</span>
                                    </button>
                                </div>
                                <div class="flex justify-between items-center pt-0.5">
                                    <!-- Error Container Password -->
                                    @error('password')
                                        <div class="items-center gap-1.5 text-error flex" id="password-error">
                                            <span class="material-symbols-outlined text-[16px] text-error">error</span>
                                            <span class="font-body-sm text-body-sm text-error">{{ $message }}</span>
                                        </div>
                                    @else
                                        <div></div>
                                    @enderror
                                    <!-- Forgot Password Link -->
                                    <a class="font-label-md text-label-md text-secondary hover:underline ml-auto" href="javascript:void(0)" onclick="showForgotNotice()">
                                        Lupa kata sandi?
                                    </a>
                                </div>
                            </div>

                            <!-- Primary Button (Masuk) -->
                            <button class="w-full h-[52px] bg-secondary hover:bg-primary active:scale-[0.99] text-on-secondary font-title-md text-title-md rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer font-bold" id="btn-submit" type="submit">
                                <span id="btn-text">Masuk</span>
                                <div class="items-center gap-2 hidden" id="btn-spinner">
                                    <svg class="animate-spin h-5 w-5 text-on-secondary" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" fill="currentColor"></path>
                                    </svg>
                                    <span>Memproses...</span>
                                </div>
                            </button>
                        </form>

                        <!-- Divider -->
                        <div class="relative flex items-center justify-center my-0.5">
                            <div class="w-full h-px bg-surface-container-high"></div>
                            <span class="absolute bg-surface-container-lowest px-4 font-body-sm text-body-sm text-outline">atau</span>
                        </div>

                        <!-- Secondary Button: SSO Yayasan (Disabled & Subordinate) -->
                        <button aria-disabled="true" class="w-full h-[48px] bg-surface-container-low text-outline cursor-not-allowed rounded-xl flex items-center justify-center gap-2.5 opacity-90 transition-none select-none shadow-none" disabled="" type="button">
                            <span class="material-symbols-outlined text-[20px] text-outline">domain</span>
                            <span class="font-title-sm text-title-sm text-outline">Masuk dengan SSO Yayasan</span>
                            <span class="bg-surface-container-highest text-on-surface-variant font-label-sm text-label-sm px-2.5 py-0.5 rounded-full ml-1 font-medium">
                                Segera hadir
                            </span>
                        </button>

                        <!-- Bottom Footer Support Text -->
                        <div class="pt-2 text-center">
                            <p class="font-body-sm text-body-sm text-on-surface-variant">
                                Butuh bantuan?
                                <a class="font-title-sm text-title-sm text-secondary hover:underline inline-flex items-center gap-1 ml-0.5" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20masuk%20ke%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                                    <span>Hubungi CS via WhatsApp</span>
                                    <span class="material-symbols-outlined text-[14px]">arrow_outward</span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- 2. MOBILE VIEW (Layar HP / Tablet < lg)                                   -->
    <!-- ========================================================================= -->
    <main class="lg:hidden flex-1 flex flex-col relative w-full bg-surface pt-safe pb-safe min-h-screen">
        <div class="flex flex-col w-full relative pb-16">
            <!-- Top Branded Header -->
            <header class="w-full bg-gradient-to-b from-primary to-primary-container px-6 pt-5 pb-6 text-on-primary flex flex-col justify-end shadow-sm">
                <div class="flex items-center gap-3">
                    <!-- Leaf Mark Logo Icon -->
                    <div class="w-10 h-10 rounded-xl bg-surface-container-lowest/15 backdrop-blur-sm flex items-center justify-center text-primary-fixed shadow-inner">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"></path>
                            <path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"></path>
                        </svg>
                    </div>
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-baseline gap-1.5">
                            <span class="font-headline-sm text-headline-sm tracking-tight text-surface-container-lowest">Buana Group</span>
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-secondary-fixed"></span>
                        </div>
                        <p class="font-label-sm text-label-sm text-primary-fixed-dim/90 truncate">
                            PT Buana Sejahtera Semarang • UNWAHAS
                        </p>
                    </div>
                </div>
            </header>

            <!-- Main Content Box -->
            <div class="w-full px-6 py-6 bg-surface-container-lowest flex flex-col gap-6">
                <!-- 1. Heading -->
                <div class="flex flex-col">
                    <h1 class="font-headline-md text-headline-md text-on-surface">Selamat Datang</h1>
                    <p class="font-body-md text-body-md text-on-surface-variant mt-1">Masuk untuk memesan layanan Buana Group.</p>
                </div>

                <!-- 2. Segmented Tab ( Masuk | Daftar ) -->
                <div class="w-full bg-surface-container-low p-1 rounded-xl flex items-center shadow-inner" id="authSegment">
                    <button class="flex-1 py-2 rounded-lg font-title-sm text-title-sm text-center transition-all bg-surface-container-lowest text-primary shadow-sm cursor-default" id="tabMasuk" type="button">
                        Masuk
                    </button>
                    <a href="{{ route('register') }}" class="flex-1 py-2 rounded-lg font-body-md text-body-md text-center transition-all text-outline hover:text-on-surface" id="tabDaftar">
                        Daftar
                    </a>
                </div>

                <!-- Status / Success Alert -->
                @if (session('status') || session('success'))
                    <div class="p-3.5 rounded-xl bg-secondary-fixed/40 border border-secondary text-primary flex items-center gap-2.5 text-xs shadow-xs">
                        <span class="material-symbols-outlined text-lg flex-shrink-0 text-secondary">check_circle</span>
                        <span class="font-medium">{{ session('status') ?? session('success') }}</span>
                    </div>
                @endif

                <!-- Auth Form Fields -->
                <form class="flex flex-col gap-5" id="loginForm" method="POST" action="{{ route('login') }}" onsubmit="handleMobileSubmit(event)">
                    @csrf

                    <!-- 3. Identifier Field -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-title-sm text-title-sm text-on-surface" for="identifierInput">
                            Email / NIM / NIP / No. HP
                        </label>
                        <div class="relative">
                            <input class="w-full py-3.5 px-4 rounded-xl text-body-lg font-body-lg bg-surface-container-lowest text-on-surface placeholder:text-outline-variant shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest" 
                                id="identifierInput" 
                                name="account" 
                                placeholder="contoh: nama@email.com atau 0812xxxx" 
                                style="box-shadow: 0 0 0 1px @if($errors->has('account') || $errors->has('phone') || $errors->has('email')) #ba1a1a @else #D9E2DC @endif;" 
                                type="text" 
                                value="{{ old('account', old('phone', old('email'))) }}" 
                                required/>
                        </div>
                        <!-- Helper message -->
                        <div class="flex items-center gap-1.5 mt-0.5 text-secondary">
                            <span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span>
                            <span class="font-body-sm text-body-sm font-medium">Civitas UNWAHAS bisa masuk dengan NIM atau NIP.</span>
                        </div>
                        <!-- Dynamic User Error Container -->
                        @if ($errors->has('account') || $errors->has('phone') || $errors->has('email'))
                            <div class="flex items-center gap-1.5 mt-1 text-error" id="userErrorMsg">
                                <span class="material-symbols-outlined text-[16px] shrink-0">error</span>
                                <span class="font-body-sm text-body-sm font-medium">{{ $errors->first('account') ?: ($errors->first('phone') ?: $errors->first('email')) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- 4. Kata Sandi Field -->
                    <div class="flex flex-col gap-1.5">
                        <label class="font-title-sm text-title-sm text-on-surface" for="passwordInput">
                            Kata Sandi
                        </label>
                        <div class="relative flex items-center">
                            <input class="w-full py-3.5 pl-4 pr-12 rounded-xl text-body-lg font-body-lg bg-surface-container-lowest text-on-surface placeholder:text-outline-variant shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-primary focus:bg-surface-container-lowest" 
                                id="passwordInput" 
                                name="password" 
                                placeholder="Masukkan kata sandi" 
                                style="box-shadow: 0 0 0 1px @error('password') #ba1a1a @else #D9E2DC @endif;" 
                                type="password" 
                                required/>
                            <button aria-label="Tampilkan / Sembunyikan Kata Sandi" class="absolute right-3.5 p-1 text-outline hover:text-on-surface flex items-center justify-center transition-colors cursor-pointer" onclick="togglePasswordVisibility('passwordInput', 'pwdEyeIcon')" type="button">
                                <span class="material-symbols-outlined text-[20px]" id="pwdEyeIcon">visibility</span>
                            </button>
                        </div>
                        <!-- Dynamic Password Error Container -->
                        @error('password')
                            <div class="flex items-center gap-1.5 mt-1 text-error" id="pwdErrorMsg">
                                <span class="material-symbols-outlined text-[16px] shrink-0">error</span>
                                <span class="font-body-sm text-body-sm font-medium">{{ $message }}</span>
                            </div>
                        @enderror
                        <!-- Lupa Kata Sandi Text Link -->
                        <div class="flex justify-end mt-1">
                            <a class="font-label-md text-label-md text-primary hover:underline" href="javascript:void(0)" onclick="showForgotNotice()">
                                Lupa kata sandi?
                            </a>
                        </div>
                    </div>

                    <!-- 5. Primary Action Button -->
                    <button class="w-full h-[52px] mt-1 bg-primary-container hover:bg-primary active:scale-[0.99] text-on-primary font-title-md text-title-md rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer font-bold" id="submitBtn" type="submit">
                        <span id="btnLabel">Masuk</span>
                        <svg class="hidden animate-spin h-5 w-5 text-on-primary" fill="none" id="btnSpinner" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" fill="currentColor"></path>
                        </svg>
                    </button>
                </form>

                <!-- 6. Divider -->
                <div class="relative flex items-center justify-center my-1">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full h-px bg-surface-dim"></div>
                    </div>
                    <div class="relative bg-surface-container-lowest px-3">
                        <span class="font-body-sm text-body-sm text-outline">atau</span>
                    </div>
                </div>

                <!-- 7. Secondary Button (SSO Yayasan - Subordinate & Disabled) -->
                <div class="flex flex-col">
                    <button class="w-full h-12 bg-surface-container-low text-outline/60 cursor-not-allowed rounded-xl flex items-center justify-center gap-2 px-3 opacity-90 shadow-[0_0_0_1px_rgba(217,226,220,0.7)]" disabled="" type="button">
                        <span class="material-symbols-outlined text-[18px] text-outline/50">domain</span>
                        <span class="font-title-sm text-title-sm text-outline/70">Masuk dengan SSO Yayasan</span>
                        <span class="font-label-sm text-label-sm bg-surface-dim/70 text-on-surface-variant px-2 py-0.5 rounded-full font-medium ml-1">
                            Segera hadir
                        </span>
                    </button>
                </div>

                <!-- 8. Footer CS Support -->
                <footer class="flex items-center justify-center gap-1.5 pt-3 text-center">
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Butuh bantuan?</span>
                    <a class="inline-flex items-center gap-1 font-body-sm text-body-sm font-semibold text-secondary hover:underline" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20masuk%20ke%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                        <!-- WhatsApp Minimal Glyph -->
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91C2.13 13.66 2.59 15.36 3.45 16.86L2.05 22L7.3 20.62C8.75 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.95 17.38 21.95 11.92C21.95 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2M12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.28 9.72 20.28 11.92C20.28 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11 19.76 7.85 19L7.55 18.83L4.43 19.65L5.26 16.61L5.06 16.29C4.24 14.99 3.8 13.47 3.8 11.91C3.81 7.37 7.5 3.67 12.05 3.67Z"></path>
                        </svg>
                        <span>Hubungi CS via WhatsApp</span>
                    </a>
                </footer>
            </div>
        </div>
    </main>

    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                if (icon) icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                if (icon) icon.innerText = 'visibility';
            }
        }

        // Handle desktop submit button loading state
        function handleDesktopSubmit(e) {
            const btn = document.getElementById('btn-submit');
            const btnText = document.getElementById('btn-text');
            const spinner = document.getElementById('btn-spinner');

            if (btn && btnText && spinner) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                btnText.classList.add('hidden');
                spinner.classList.remove('hidden');
                spinner.classList.add('flex');
            }
            // Allow form to submit naturally
        }

        // Handle mobile submit button loading state
        function handleMobileSubmit(e) {
            const btn = document.getElementById('submitBtn');
            const btnLabel = document.getElementById('btnLabel');
            const spinner = document.getElementById('btnSpinner');

            if (btn && btnLabel && spinner) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                btnLabel.innerText = 'Memproses...';
                spinner.classList.remove('hidden');
            }
            // Allow form to submit naturally
        }

        // Notification for forgot password
        function showForgotNotice() {
            alert('Untuk pemulihan kata sandi akun, silakan hubungi Helpdesk Layanan UNWAHAS via WhatsApp: 0812-3456-7890 dengan menyertakan Email terdaftar / No. HP / NIM / NIP.');
        }
    </script>
</body>
</html>
