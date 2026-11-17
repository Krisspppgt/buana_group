<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketingController extends Controller
{
    /**
     * Menampilkan agregator tiket transportasi dan penjualan tiket event kampus.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Data rute agregator transportasi (KAI, Pesawat, Bus, Kapal)
        $transportRoutes = [
            [
                'id' => 'TRN-01',
                'mode' => 'kereta',
                'mode_name' => 'Kereta Api (KAI)',
                'icon' => '🚆',
                'title' => 'KA Argo Sindoro (Eksekutif)',
                'route' => 'Semarang Tawang (SMT) ➔ Gambir Jakarta (GMR)',
                'schedule' => '07:00 - 12:45 WIB',
                'date' => now()->addDays(3)->format('d M Y'),
                'price' => 380000,
                'seat_default' => 'Eks 3 / 8A',
            ],
            [
                'id' => 'TRN-02',
                'mode' => 'kereta',
                'mode_name' => 'Kereta Api (KAI)',
                'icon' => '🚆',
                'title' => 'KA Kaligung (Ekonomi Plus)',
                'route' => 'Semarang Poncol (SMC) ➔ Tegal / Cirebon',
                'schedule' => '08:45 - 11:00 WIB',
                'date' => now()->addDays(2)->format('d M Y'),
                'price' => 95000,
                'seat_default' => 'Eko 2 / 14D',
            ],
            [
                'id' => 'TRN-03',
                'mode' => 'pesawat',
                'mode_name' => 'Pesawat Terbang',
                'icon' => '✈️',
                'title' => 'Garuda Indonesia GA-234',
                'route' => 'Ahmad Yani Semarang (SRG) ➔ Soekarno Hatta (CGK)',
                'schedule' => '09:15 - 10:20 WIB',
                'date' => now()->addDays(5)->format('d M Y'),
                'price' => 980000,
                'seat_default' => 'Seat 14C',
            ],
            [
                'id' => 'TRN-04',
                'mode' => 'bus',
                'mode_name' => 'Bus AKAP Antarkota',
                'icon' => '🚌',
                'title' => 'PO Haryanto Executive Super',
                'route' => 'Terminal Sukun Semarang ➔ Pulo Gebang Jakarta',
                'schedule' => '19:00 - 02:30 WIB',
                'date' => now()->addDays(2)->format('d M Y'),
                'price' => 220000,
                'seat_default' => 'Seat 06',
            ],
            [
                'id' => 'TRN-05',
                'mode' => 'kapal',
                'mode_name' => 'Kapal Laut (PELNI)',
                'icon' => '🚢',
                'title' => 'KM Kelimutu Tanjung Emas',
                'route' => 'Tanjung Emas Semarang ➔ Kumai Kalimantan Tengah',
                'schedule' => '22:00 - 20:00 (+1 Hari)',
                'date' => now()->addDays(7)->format('d M Y'),
                'price' => 245000,
                'seat_default' => 'Dek 3 / Bed 42',
            ],
        ];

        // Tiket Event Internal / Eksternal Yayasan Wahid Hasyim
        $events = Event::where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->get();

        return view('ticketing.index', compact('transportRoutes', 'events', 'user'));
    }

    /**
     * Pemesanan tiket transportasi (KAI / Pesawat / Bus / Kapal).
     */
    public function orderTransport(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'price' => ['required', 'integer', 'min:0'],
            'seat_info' => ['required', 'string', 'max:50'],
            'passenger_name' => ['required', 'string', 'max:50'],
            'passenger_id' => ['required', 'string', 'max:30'],
            'schedule_at' => ['required', 'date'],
        ]);

        $ticketCode = Ticket::newCode();
        $qrPayload = 'BUANA-TRN-'.strtoupper(Str::random(8)).'-'.$ticketCode;

        $ticket = Ticket::create([
            'code' => $ticketCode,
            'user_id' => $user->id,
            'category' => 'transport',
            'title' => $data['title'],
            'passenger_name' => $data['passenger_name'],
            'passenger_id' => $data['passenger_id'],
            'schedule_at' => $data['schedule_at'],
            'seat_info' => $data['seat_info'],
            'qr_payload' => $qrPayload,
            'price' => (int) $data['price'],
            'status' => 0, // Aktif
        ]);

        return redirect()->route('ticketing.show', $ticket->code)
            ->with('success', 'Tiket transportasi berhasil diterbitkan! E-Boarding Pass siap digunakan.');
    }

    /**
     * Pemesanan tiket event Yayasan Wahid Hasyim.
     */
    public function orderEvent(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'event_id' => ['required', 'exists:events,id'],
            'passenger_name' => ['required', 'string', 'max:50'],
            'passenger_id' => ['required', 'string', 'max:30'],
        ]);

        $event = Event::findOrFail($data['event_id']);
        $price = $event->priceFor($user);

        $ticketCode = Ticket::newCode();
        $qrPayload = 'BUANA-EVT-'.strtoupper(Str::random(8)).'-'.$ticketCode;

        $ticket = Ticket::create([
            'code' => $ticketCode,
            'user_id' => $user->id,
            'category' => 'event',
            'title' => $event->title,
            'passenger_name' => $data['passenger_name'],
            'passenger_id' => $data['passenger_id'],
            'schedule_at' => $event->event_date,
            'seat_info' => 'Area Event: '.$event->location,
            'qr_payload' => $qrPayload,
            'price' => $price,
            'status' => 0, // Aktif
        ]);

        $event->increment('booked');

        return redirect()->route('ticketing.show', $ticket->code)
            ->with('success', 'Tiket acara berhasil dipesan! Tunjukkan E-Ticket QR Code saat masuk venue.');
    }

    /**
     * Menampilkan E-Boarding Pass digital dengan QR Code siap scan.
     */
    public function show(Request $request, string $code): View
    {
        $user = $request->user();

        $ticket = Ticket::with('user')
            ->where('code', $code)
            ->when(! $user->isAdmin() && ! $user->isStaff(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('ticketing.boarding_pass', compact('ticket'));
    }

    /**
     * Scan check-in oleh petugas gate / scanner keberangkatan.
     */
    public function scan(Request $request, string $code): RedirectResponse
    {
        $user = $request->user();

        // Hanya Operator / Admin / Superadmin yang boleh scan validasi
        if (! $user->isAdmin() && ! $user->isStaff()) {
            abort(403, 'Hanya petugas scanner/gate yang memiliki akses validasi tiket.');
        }

        $ticket = Ticket::where('code', $code)->firstOrFail();

        if ($ticket->status === 1) {
            return back()->with('warning', 'PERINGATAN: Tiket ini SUDAH PERNAH di-scan sebelumnya pada '.$ticket->checked_in_at?->format('H:i').' WIB!');
        }

        $ticket->update([
            'status' => 1,
            'checked_in_at' => now(),
        ]);

        return back()->with('success', 'VALIDASI BERHASIL! E-Boarding Pass sah. Silakan masuk.');
    }

    /**
     * Riwayat tiket & boarding pass pengguna.
     */
    public function history(Request $request): View
    {
        $user = $request->user();
        $tickets = Ticket::where('user_id', $user->id)
            ->latest('id')
            ->paginate(10);

        return view('ticketing.history', compact('tickets'));
    }
}
