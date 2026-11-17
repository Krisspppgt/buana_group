<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Modul Buana Tour Travel: Paket Perjalanan
        Schema::create('tour_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('category', 30); // wisata, study_tour, ziarah, umrah
            $table->date('departure_date');
            $table->string('duration', 30); // contoh: "4 Hari 3 Malam"
            $table->unsignedInteger('price');
            $table->unsignedInteger('price_civitas')->nullable();
            $table->boolean('allow_installment')->default(false);
            $table->unsignedInteger('min_dp')->default(0);
            $table->unsignedSmallInteger('quota')->default(40);
            $table->unsignedSmallInteger('booked')->default(0);
            $table->text('itinerary')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Modul Buana Tour Travel: Booking & Pendaftaran Rombongan
        Schema::create('tour_bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 14)->unique(); // contoh: TR2611170001
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tour_package_id');
            $table->string('coordinator_name', 50);
            $table->string('coordinator_phone', 15);
            $table->unsignedSmallInteger('total_pax')->default(1);
            $table->string('payment_scheme', 20)->default('cash'); // cash, cicilan
            $table->unsignedTinyInteger('installment_tenor')->default(1); // 1, 3, 6, 12 bulan
            $table->unsignedInteger('paid_amount')->default(0);
            $table->unsignedInteger('total_amount');
            $table->text('members_data')->nullable(); // JSON data NIK/Paspor rombongan
            $table->string('manifest_file', 120)->nullable();
            $table->unsignedTinyInteger('status')->default(0); // 0: Diajukan, 1: Cicilan Aktif, 2: Lunas/Siap Berangkat, 3: Selesai, 9: Batal
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('tour_package_id')->references('id')->on('tour_packages')->cascadeOnDelete();
        });

        // 3. Modul Buana Ticketing: Event Internal/Eksternal Kampus
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('organizer', 60);
            $table->dateTime('event_date');
            $table->string('location', 100);
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('price_civitas')->default(0);
            $table->unsignedSmallInteger('quota')->default(100);
            $table->unsignedSmallInteger('booked')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. Modul Buana Ticketing: E-Boarding Pass & Tiket Digital dengan QR Code
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 16)->unique(); // contoh: TIX2611170001
            $table->unsignedInteger('user_id');
            $table->string('category', 20); // transport, event
            $table->string('title', 100); // Rute Transport atau Nama Event
            $table->string('passenger_name', 50);
            $table->string('passenger_id', 30); // NIK/KTP/NIM
            $table->dateTime('schedule_at');
            $table->string('seat_info', 50); // Gerbong/Seat atau VIP Section
            $table->string('qr_payload', 100);
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('status')->default(0); // 0: Valid/Aktif, 1: Sudah Di-scan (Check-in), 2: Batal
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('events');
        Schema::dropIfExists('tour_bookings');
        Schema::dropIfExists('tour_packages');
    }
};
