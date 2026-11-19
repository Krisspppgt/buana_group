<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover" name="viewport"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>Verifikasi OTP &amp; Sandi Baru - Buana Group</title>

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
                        "on-secondary": "#ffffff",
                        "surface": "#f9f9ff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f0f3ff",
                        "surface-container": "#e7eeff",
                        "surface-variant": "#d8e3fb",
                        "on-surface": "#111c2d",
                        "on-surface-variant": "#3f4942",
                        "on-background": "#111c2d",
                        "outline": "#6f7a71",
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
                        <span class="material-symbols-outlined text-[26px]">verified_user</span>
                    </div>
                    <div>
                        <div class="text-[18px] font-bold tracking-wide text-on-primary">BUANA GROUP</div>
                        <div class="text-[11px] font-medium tracking-wider uppercase text-on-primary-container/90">Verifikasi Keamanan</div>
                    </div>
                </div>
                <p class="text-[11px] text-on-primary-container/80 pl-14">
                    PT Buana Sejahtera Semarang • Yayasan Wahid Hasyim
                </p>
            </div>

            <div class="relative z-10 my-auto max-w-lg">
                <h1 class="text-[30px] font-bold leading-tight tracking-tight text-on-primary mb-5">
                    Buat Kata Sandi Baru yang Kuat
                </h1>
                <p class="text-[16px] text-on-primary-container/90 leading-relaxed max-w-md">
                    Masukkan 6 digit kode OTP yang Anda terima, lalu buat kata sandi baru minimal 8 karakter, diawali huruf kapital, dan mengandung angka.
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
                    <span class="material-symbols-outlined text-[22px]">verified_user</span>
                </div>
                <div>
                    <div class="text-[18px] font-bold tracking-tight">Verifikasi OTP</div>
                    <p class="text-[11px] text-primary-fixed-dim/90">Atur Ulang Kata Sandi Buana Group</p>
                </div>
            </div>
        </header>

        <!-- RIGHT PANEL / MAIN FORM -->
        <main class="w-full lg:w-[60%] bg-surface-container-lowest flex-1 flex items-center justify-center p-6 md:p-12 lg:p-16">
            <div class="w-full max-w-[440px] flex flex-col gap-5">
                <a href="{{ route('password.request') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-secondary hover:text-primary transition-colors w-fit">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Ganti Saluran / Akun</span>
                </a>

                <div class="flex flex-col gap-1.5">
                    <h2 class="text-[22px] font-bold text-on-background">Masukkan Kode OTP</h2>
                    <p class="text-[14px] text-on-surface-variant">
                        Kode 6 digit dikirim via <strong>{{ $channel === 'whatsapp' ? 'WhatsApp' : 'Gmail' }}</strong> ke <span class="font-semibold text-primary">{{ $maskedTarget }}</span>.
                    </p>
                </div>

                @if (session('status'))
                    <div class="p-3.5 rounded-xl bg-primary-fixed/30 border border-secondary/40 text-primary flex items-start gap-2.5 text-xs">
                        <span class="material-symbols-outlined text-lg shrink-0 text-secondary">check_circle</span>
                        <span class="font-medium leading-relaxed">{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="p-3.5 rounded-xl bg-error-container/50 border border-error/30 text-error flex items-start gap-2.5 text-xs">
                        <span class="material-symbols-outlined text-lg shrink-0">error</span>
                        <div class="flex-1 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <p class="font-medium text-on-error-container">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.reset') }}" class="flex flex-col gap-4" onsubmit="handleResetSubmit()">
                    @csrf

                    <!-- 6-digit OTP Input -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[14px] font-semibold text-on-background" for="otp">
                            Kode OTP (6 Digit)
                        </label>
                        <input
                            id="otp"
                            name="otp"
                            type="text"
                            inputmode="numeric"
                            maxlength="6"
                            pattern="\d{6}"
                            value="{{ old('otp') }}"
                            placeholder="••••••"
                            class="w-full h-[52px] px-4 rounded-xl text-[20px] font-bold tracking-[0.45em] text-center bg-surface-container-low text-primary shadow-sm focus:outline-none focus:bg-surface-container-lowest transition-all placeholder:text-outline/50 ring-1 @error('otp') ring-2 ring-error @else ring-surface-variant focus:ring-2 focus:ring-secondary @enderror"
                            required
                            autofocus
                        />
                    </div>

                    <!-- New Password -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[14px] font-semibold text-on-background" for="new-password">
                            Kata Sandi Baru
                        </label>
                        <div class="relative flex items-center">
                            <input
                                id="new-password"
                                name="password"
                                type="password"
                                minlength="8"
                                pattern="^[A-Z](?=.*\d).{7,}$"
                                title="Minimal 8 karakter, huruf pertama harus kapital, dan harus mengandung angka"
                                placeholder="Min. 8 karakter, awal kapital &amp; angka"
                                class="w-full h-[48px] pl-4 pr-12 rounded-xl text-[14px] bg-surface-container-lowest text-on-surface shadow-sm focus:outline-none transition-all placeholder:text-outline/60 ring-1 @error('password') ring-2 ring-error @else ring-surface-variant focus:ring-2 focus:ring-secondary @enderror"
                                required
                            />
                            <button type="button" onclick="togglePass('new-password', 'eye-1')" class="absolute right-3.5 p-1 text-outline hover:text-on-surface cursor-pointer">
                                <span class="material-symbols-outlined text-[20px]" id="eye-1">visibility</span>
                            </button>
                        </div>
                        <p class="text-[11px] text-on-surface-variant">Minimal 8 karakter, diawali huruf kapital, dan wajib mengandung angka.</p>
                    </div>

                    <!-- Confirm New Password -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[14px] font-semibold text-on-background" for="confirm-password">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <div class="relative flex items-center">
                            <input
                                id="confirm-password"
                                name="password_confirmation"
                                type="password"
                                minlength="8"
                                placeholder="Ulangi kata sandi baru"
                                class="w-full h-[48px] pl-4 pr-12 rounded-xl text-[14px] bg-surface-container-lowest text-on-surface shadow-sm focus:outline-none transition-all placeholder:text-outline/60 ring-1 ring-surface-variant focus:ring-2 focus:ring-secondary"
                                required
                            />
                            <button type="button" onclick="togglePass('confirm-password', 'eye-2')" class="absolute right-3.5 p-1 text-outline hover:text-on-surface cursor-pointer">
                                <span class="material-symbols-outlined text-[20px]" id="eye-2">visibility</span>
                            </button>
                        </div>
                    </div>

                    <button id="btn-reset" type="submit" class="w-full h-[52px] mt-1 bg-secondary hover:bg-primary active:scale-[0.99] text-on-secondary font-bold text-[15px] rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btn-reset-text">Simpan Kata Sandi Baru</span>
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </button>
                </form>

                <!-- Resend OTP Form -->
                <div class="flex items-center justify-between pt-2 border-t border-surface-container-high text-[13px]">
                    <span class="text-on-surface-variant">Tidak menerima kode?</span>
                    <form method="POST" action="{{ route('password.otp.resend') }}">
                        @csrf
                        <button
                            id="btn-resend"
                            type="submit"
                            class="font-bold text-secondary hover:text-primary disabled:text-outline disabled:cursor-not-allowed cursor-pointer transition-colors"
                            {{ $cooldownRemaining > 0 ? 'disabled' : '' }}
                        >
                            @if ($cooldownRemaining > 0)
                                Kirim Ulang (<span id="cooldown-sec">{{ $cooldownRemaining }}</span>d)
                            @else
                                Kirim Ulang OTP
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function togglePass(inputId, iconId) {
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

        function handleResetSubmit() {
            const btn = document.getElementById('btn-reset');
            const text = document.getElementById('btn-reset-text');
            if (btn && text) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                text.innerText = 'Memproses...';
            }
        }

        // Cooldown countdown timer for Resend OTP
        let remaining = {{ (int) $cooldownRemaining }};
        const resendBtn = document.getElementById('btn-resend');
        const secSpan = document.getElementById('cooldown-sec');

        if (remaining > 0 && resendBtn && secSpan) {
            const timer = setInterval(() => {
                remaining -= 1;
                if (remaining <= 0) {
                    clearInterval(timer);
                    resendBtn.disabled = false;
                    resendBtn.innerText = 'Kirim Ulang OTP';
                } else {
                    secSpan.innerText = remaining;
                }
            }, 1000);
        }
    </script>
</body>
</html>
