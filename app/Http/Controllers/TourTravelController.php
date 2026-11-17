<?php

namespace App\Http\Controllers;

use App\Models\TourBooking;
use App\Models\TourPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TourTravelController extends Controller
{
    /**
     * Menampilkan etalase paket wisata, study tour, ziarah wali, dan paket Umrah.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $category = $request->query('category');

        $packages = TourPackage::where('is_active', true)
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest('id')
            ->get();

        return view('tour.index', compact('packages', 'user', 'category'));
    }

    /**
     * Menampilkan detail satu paket tour & formulir booking / pendaftaran rombongan.
     */
    public function showPackage(Request $request, int $id): View
    {
        $user = $request->user();
        $package = TourPackage::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        return view('tour.package_detail', compact('package', 'user'));
    }

    /**
     * Menyimpan pemesanan paket perjalanan (pendaftaran rombongan & cicilan).
     */
    public function storeBooking(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'tour_package_id' => ['required', 'exists:tour_packages,id'],
            'coordinator_name' => ['required', 'string', 'max:50'],
            'coordinator_phone' => ['required', 'regex:/^08\d{8,12}$/'],
            'total_pax' => ['required', 'integer', 'min:1', 'max:100'],
            'payment_scheme' => ['required', 'in:cash,cicilan'],
            'installment_tenor' => ['nullable', 'integer', 'in:1,3,6,12'],
            'member_names' => ['nullable', 'array'],
            'member_ids' => ['nullable', 'array'],
            'manifest_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,xlsx,csv', 'max:5120'],
        ]);

        $package = TourPackage::findOrFail($data['tour_package_id']);
        $pax = (int) $data['total_pax'];
        $unitPrice = $package->priceFor($user);
        $totalAmount = $unitPrice * $pax;

        // Susun data anggota rombongan
        $members = [];
        if (! empty($data['member_names'])) {
            foreach ($data['member_names'] as $idx => $name) {
                if (! empty($name)) {
                    $members[] = [
                        'name' => $name,
                        'id_number' => $data['member_ids'][$idx] ?? '-',
                    ];
                }
            }
        }

        // Upload manifest massal jika ada
        $manifestPath = null;
        if ($request->hasFile('manifest_file')) {
            $manifestPath = $request->file('manifest_file')->store('manifests', 'public');
        }

        // Hitung nominal DP jika skema cicilan dipilih
        $paidAmount = 0;
        $tenor = 1;
        if ($data['payment_scheme'] === 'cicilan') {
            $tenor = (int) ($data['installment_tenor'] ?? 6);
            $paidAmount = $package->min_dp * $pax; // Setoran DP awal
        } else {
            $paidAmount = $totalAmount; // Pembayaran penuh
        }

        $booking = DB::transaction(function () use ($user, $package, $data, $pax, $totalAmount, $paidAmount, $tenor, $members, $manifestPath) {
            $booking = TourBooking::create([
                'code' => TourBooking::newCode(),
                'user_id' => $user->id,
                'tour_package_id' => $package->id,
                'coordinator_name' => $data['coordinator_name'],
                'coordinator_phone' => $data['coordinator_phone'],
                'total_pax' => $pax,
                'payment_scheme' => $data['payment_scheme'],
                'installment_tenor' => $tenor,
                'paid_amount' => $paidAmount,
                'total_amount' => $totalAmount,
                'members_data' => $members,
                'manifest_file' => $manifestPath,
                'status' => $data['payment_scheme'] === 'cicilan' ? 1 : 2, // 1: Cicilan Aktif, 2: Lunas
            ]);

            $package->increment('booked', $pax);

            return $booking;
        });

        return redirect()->route('tour.bookings.show', $booking->code)
            ->with('success', 'Pendaftaran rombongan berhasil diajukan! Bukti pemesanan telah dibuat.');
    }

    /**
     * Menampilkan detail pendaftaran rombongan & riwayat cicilan.
     */
    public function showBooking(Request $request, string $code): View
    {
        $user = $request->user();
        $booking = TourBooking::with(['tourPackage', 'user'])
            ->where('code', $code)
            ->when(! $user->isAdmin(), fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        return view('tour.booking_detail', compact('booking'));
    }

    /**
     * Riwayat pendaftaran tour & travel pengguna.
     */
    public function history(Request $request): View
    {
        $user = $request->user();
        $bookings = TourBooking::with('tourPackage')
            ->where('user_id', $user->id)
            ->latest('id')
            ->paginate(10);

        return view('tour.history', compact('bookings'));
    }
}
