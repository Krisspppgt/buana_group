<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FoodServiceController;
use App\Http\Controllers\KonveksiController;
use App\Http\Controllers\TicketingController;
use App\Http\Controllers\TourTravelController;
use App\Http\Controllers\WaterOrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:6,1');

    Route::get('/daftar', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/daftar', [AuthController::class, 'register'])->middleware('throttle:6,1');
});

Route::middleware('auth')->group(function () {
    Route::get('/', fn () => view('home'))->name('home');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
