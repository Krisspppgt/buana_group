<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Lupa Kata Sandi - Buana Group | Universitas Wahid Hasyim</title>

    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; font-family: 'Plus Jakarta Sans', sans-serif; }
        }
        ::-webkit-scrollbar { display: none; }
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
                        "secondary": "#006c49",
                        "secondary-container": "#6cf8bb",
                        "secondary-fixed": "#6ffbbe",
                        "on-secondary": "#ffffff",
                        "surface": "#f9f9ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f0f3ff",
                        "surface-container": "#e7eeff",
                        "surface-container-high": "#dee8ff",
                        "surface-container-highest": "#d8e3fb",
                        "surface-variant": "#d8e3fb",
                        "on-surface": "#111c2d",
                        "on-surface-variant": "#3f4942",
                        "on-background": "#111c2d",
                        "outline": "#6f7a71",
                        "outline-variant": "#bfc9c0",
                        "error": "#ba1a1a",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a"
                    }
                }
            }
        };
    </script>
</head>
<body class="bg-surface text-on-surface min-h-screen antialiased">
    <div class="w-full min-h-screen flex flex-col lg:flex-row">
        <!-- LEFT PANEL (Desktop 40%) -->
        <aside class="hidden lg:flex lg:w-[40%] bg-gradient-to-b from-primary to-primary-container text-on-primary p-12 lg:p-16 flex-col justify-between relative overflow-hidden select-none">
            <div class="absolute -right-24 -top-24 w-96 h-96 rounded-full bg-secondary-container/10 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 bottom-12 w-80 h-80 rounded-full bg-secondary/15 blur-3xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col gap-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-xl bg-surface-container-lowest/15 backdrop-blur-md flex items-center justify-center text-primary-fixed shadow-sm">
                        <span class="material-symbols-outlined text-[26px]">lock_reset</span>
                    </div>
                    <div>
                        <div class="text-[18px] font-bold tracking-wide text-on-primary">BUANA GROUP</div>
                        <div class="text-[11px] font-medium tracking-wider uppercase text-on-primary-container/90">Pemulihan Akses Akun</div>
                    </div>
                </div>
                <p class="text-[11px] text-on-primary-container/80 pl-14">
                    PT Buana Sejahtera Semarang • Yayasan Wahid Hasyim
                </p>
            </div>

            <div class="relative z-10 my-auto max-w-lg">
                <h1 class="text-[30px] font-bold leading-tight tracking-tight text-on-primary mb-5">
                    Reset Kata Sandi dengan Kode OTP
                </h1>
                <p class="text-[16px] text-on-primary-container/90 leading-relaxed max-w-md">
                    Pilih pengiriman kode verifikasi 6 digit melalui WhatsApp atau Gmail terdaftar untuk mengatur ulang kata sandi Anda dengan aman.
                </p>
            </div>

            <div class="relative z-10">
                <p class="text-[12px] text-on-primary-container/75">© PT Buana Sejahtera Semarang</p>
            </div>
        </aside>

        <!-- MOBILE HEADER -->
        <header class="lg:hidden w-full bg-gradient-to-b from-primary to-primary-container px-6 pt-5 pb-6 text-on-primary shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-surface-container-lowest/15 flex items-center justify-center text-primary-fixed">
                    <span class="material-symbols-outlined text-[22px]">lock_reset</span>
                </div>
                <div>
                    <div class="text-[18px] font-bold tracking-tight">Buana Group</div>
                    <p class="text-[11px] text-primary-fixed-dim/90">Pemulihan Kata Sandi via OTP</p>
                </div>
            </div>
        </header>

        <!-- RIGHT PANEL / MAIN FORM -->
        <main class="w-full lg:w-[60%] bg-surface-container-lowest flex-1 flex items-center justify-center p-6 md:p-12 lg:p-16">
            <div class="w-full max-w-[440px] flex flex-col gap-6">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-secondary hover:text-primary transition-colors w-fit">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Halaman Masuk</span>
                </a>

                <div class="flex flex-col gap-1.5">
                    <h2 class="text-[22px] font-bold text-on-background">Lupa Kata Sandi?</h2>
                    <p class="text-[14px] text-on-surface-variant">
                        Masukkan akun terdaftar Anda, lalu pilih saluran pengiriman kode OTP (WhatsApp atau Gmail).
                    </p>
                </div>

                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2.5 text-xs">
                        <span class="material-symbols-outlined text-lg shrink-0">error</span>
                        <div class="flex-1">
                            @foreach ($errors->all() as $error)
                                <p class="font-medium text-on-error-container">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.otp.send') }}" class="flex flex-col gap-5" onsubmit="handleOtpRequestSubmit()">
                    @csrf

                    <!-- Identifier Input -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[14px] font-semibold text-on-background" for="account">
                            Email / No. WhatsApp / NIM / NIP
                        </label>
                        <input
                            id="account"
                            name="account"
                            type="text"
                            value="{{ old('account') }}"
                            placeholder="contoh: nama@gmail.com atau 0812xxxx"
                            class="w-full h-[48px] px-4 rounded-xl text-[14px] bg-surface-container-lowest text-on-surface shadow-sm focus:outline-none transition-all placeholder:text-outline/60 ring-1 @error('account') ring-2 ring-error @else ring-surface-variant focus:ring-2 focus:ring-secondary @enderror"
                            required
                            autofocus
                        />
                    </div>

                    <!-- Channel Selector: WhatsApp vs Gmail -->
                    <div class="flex flex-col gap-2">
                        <label class="text-[14px] font-semibold text-on-background">
                            Kirim Kode OTP Melalui
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <!-- Option 1: WhatsApp -->
                            <label class="cursor-pointer">
                                <input type="radio" name="channel" value="whatsapp" class="peer sr-only" {{ old('channel', 'whatsapp') === 'whatsapp' ? 'checked' : '' }}/>
                                <div class="p-3.5 rounded-xl bg-surface-container-low/60 ring-1 ring-surface-variant peer-checked:bg-primary-fixed/25 peer-checked:ring-2 peer-checked:ring-primary transition-all flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#25D366]/15 text-[#128C7E] flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">chat</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[14px] font-bold text-on-surface">WhatsApp</div>
                                        <div class="text-[11px] text-on-surface-variant">Kirim ke nomor WA terdaftar</div>
                                    </div>
                                </div>
                            </label>

                            <!-- Option 2: Gmail -->
                            <label class="cursor-pointer">
                                <input type="radio" name="channel" value="email" class="peer sr-only" {{ old('channel') === 'email' ? 'checked' : '' }}/>
                                <div class="p-3.5 rounded-xl bg-surface-container-low/60 ring-1 ring-surface-variant peer-checked:bg-primary-fixed/25 peer-checked:ring-2 peer-checked:ring-primary transition-all flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-[#EA4335]/15 text-[#C5221F] flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[22px]">mail</span>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-[14px] font-bold text-on-surface">Gmail / Email</div>
                                        <div class="text-[11px] text-on-surface-variant">Kirim ke email terdaftar</div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button id="btn-send-otp" type="submit" class="w-full h-[52px] bg-secondary hover:bg-primary active:scale-[0.99] text-on-secondary font-bold text-[15px] rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btn-send-text">Kirim Kode OTP</span>
                        <span class="material-symbols-outlined text-[18px]" id="btn-send-icon">send</span>
                    </button>
                </form>

                <div class="pt-2 text-center">
                    <p class="text-[12px] text-on-surface-variant">
                        Butuh bantuan?
                        <a class="font-semibold text-secondary hover:underline inline-flex items-center gap-1 ml-0.5" href="https://wa.me/6281234567890?text=Halo%20Helpdesk%2C%20saya%20butuh%20bantuan%20reset%20kata%20sandi%20akun%20Buana%20Group." rel="noopener noreferrer" target="_blank">
                            <span>Hubungi CS via WhatsApp</span>
                            <span class="material-symbols-outlined text-[14px]">arrow_outward</span>
                        </a>
                    </p>
                </div>
            </div>
        </main>
    </div>

    <script>
        function handleOtpRequestSubmit() {
            const btn = document.getElementById('btn-send-otp');
            const text = document.getElementById('btn-send-text');
            if (btn && text) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                text.innerText = 'Mengirim OTP...';
            }
        }
    </script>
</body>
</html>
