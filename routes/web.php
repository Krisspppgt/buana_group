<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodServiceController;
use App\Http\Controllers\KonveksiController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\TicketingController;
use App\Http\Controllers\TourTravelController;
use App\Http\Controllers\WaterOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::get('/register', [AuthController::class, 'showRegister']);
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:6,1');
    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:6,1');

    // Lupa Kata Sandi + OTP (WhatsApp & Gmail)
    Route::get('/lupa-sandi', [PasswordResetController::class, 'showRequestForm'])->name('password.request');
    Route::post('/lupa-sandi', [PasswordResetController::class, 'sendOtp'])->name('password.otp.send')->middleware('throttle:6,1');
    Route::get('/lupa-sandi/verifikasi', [PasswordResetController::class, 'showVerifyForm'])->name('password.verify');
    Route::post('/lupa-sandi/kirim-ulang', [PasswordResetController::class, 'resendOtp'])->name('password.otp.resend')->middleware('throttle:6,1');
    Route::post('/lupa-sandi/reset', [PasswordResetController::class, 'resetPassword'])->name('password.reset')->middleware('throttle:6,1');
});

// Demo / Preview Mockup Slide 8 (Dapat diakses langsung)
Route::get('/mockup-slide', fn () => view('mockup-showcase'))->name('mockup.showcase');

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('home'))->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Alur Pemesanan Kampus Buana Group (Sesuai Mockup Slide 8)
    Route::get('/pesanan/rincian', fn () => view('order.rincian'))->name('order.campus.rincian');
    Route::post('/pesanan/ajukan', function (Request $request) {
        $qty = (int) $request->input('qty', 40);
        $price = 15000;
        session()->put('campus_order', [
            'code' => 'BG-261114-001',
            'package_name' => 'Paket Rapat Fakultas',
            'package_subtitle' => 'Snack box + air mineral',
            'qty' => $qty,
            'price' => $price,
            'total' => $qty * $price,
            'schedule' => $request->input('schedule', '16 Nov 2026 · 09.00 WIB'),
            'location' => $request->input('location', 'Fakultas Farmasi UNWAHAS'),
            'status_label' => 'Menunggu persetujuan',
            'created_at' => '14 Nov 2026 · 10.24 WIB',
        ]);

        return redirect()->route('order.campus.detail');
    })->name('order.campus.store');
    Route::get('/pesanan/detail', function () {
        $order = session('campus_order', [
            'code' => 'BG-261114-001',
            'package_name' => 'Paket Rapat Fakultas',
            'package_subtitle' => 'Snack box + air mineral',
            'qty' => 40,
            'price' => 15000,
            'total' => 600000,
            'schedule' => '16 Nov 2026 · 09.00 WIB',
            'location' => 'Fakultas Farmasi UNWAHAS',
            'status_label' => 'Menunggu persetujuan',
            'created_at' => '14 Nov 2026 · 10.24 WIB',
        ]);

        return view('order.detail', compact('order'));
    })->name('order.campus.detail');

    // Tampilan Presentasi 3 Layar Berjajar (Slide 8 Presentation Mode)
    Route::get('/mockup-slide', fn () => view('mockup-showcase'))->name('mockup.showcase');

    // Modul Unit 1: Air Mineral Unwahas
    Route::prefix('air-mineral')->name('water.')->group(function () {
        Route::get('/', [WaterOrderController::class, 'index'])->name('index');
        Route::post('/', [WaterOrderController::class, 'store'])->name('store');
        Route::get('/riwayat', [WaterOrderController::class, 'history'])->name('history');
        Route::get('/lacak/{code}', [WaterOrderController::class, 'show'])->name('show');
    });

    // Modul Unit 2: Buana Food Services
    Route::prefix('food-services')->name('food.')->group(function () {
        Route::get('/', [FoodServiceController::class, 'index'])->name('index');
        Route::post('/', [FoodServiceController::class, 'store'])->name('store');
        Route::get('/riwayat', [FoodServiceController::class, 'history'])->name('history');
        Route::get('/pesanan/{code}', [FoodServiceController::class, 'show'])->name('show');
    });

    // Modul Unit 3: Buana Tour & Travel
    Route::prefix('tour-travel')->name('tour.')->group(function () {
        Route::get('/', [TourTravelController::class, 'index'])->name('index');
        Route::get('/paket/{id}', [TourTravelController::class, 'showPackage'])->name('package.show');
        Route::post('/booking', [TourTravelController::class, 'storeBooking'])->name('bookings.store');
        Route::get('/booking/{code}', [TourTravelController::class, 'showBooking'])->name('bookings.show');
        Route::get('/riwayat', [TourTravelController::class, 'history'])->name('history');
    });

    // Modul Unit 4: Buana Ticketing
    Route::prefix('ticketing')->name('ticketing.')->group(function () {
        Route::get('/', [TicketingController::class, 'index'])->name('index');
        Route::post('/pesan-transport', [TicketingController::class, 'orderTransport'])->name('order.transport');
        Route::post('/pesan-event', [TicketingController::class, 'orderEvent'])->name('order.event');
        Route::get('/riwayat', [TicketingController::class, 'history'])->name('history');
        Route::get('/boarding-pass/{code}', [TicketingController::class, 'show'])->name('show');
        Route::post('/scan/{code}', [TicketingController::class, 'scan'])->name('scan');
    });

    // Modul Unit 5: Buana Konveksi
    Route::prefix('konveksi')->name('konveksi.')->group(function () {
        Route::get('/', [KonveksiController::class, 'index'])->name('index');
        Route::post('/kuotasi', [KonveksiController::class, 'storeQuote'])->name('quotes.store');
        Route::get('/kuotasi', [KonveksiController::class, 'quotes'])->name('quotes');
        Route::get('/kuotasi/{id}', [KonveksiController::class, 'showQuote'])->name('quotes.show');
        Route::get('/tracking/{code}', [KonveksiController::class, 'tracking'])->name('tracking');
    });
});
