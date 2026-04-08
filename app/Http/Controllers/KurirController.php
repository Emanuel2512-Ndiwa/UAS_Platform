<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KurirController extends Controller
{
    /** Dashboard Kurir */
    public function dashboard()
    {
        $orders = Transaction::where('kurir_id', Auth::id())
            ->whereIn('service_status', ['selesai_cuci', 'diantar'])
            ->with('user', 'service')
            ->latest()
            ->get();

        return view('dashboard.kurir', compact('orders'));
    }

    /**
     * Kurir ambil order yang sudah selesai cuci
     * Status: selesai_cuci -> diantar
     */
    public function ambilOrder(Transaction $transaction)
    {
        if ($transaction->service_status !== 'selesai_cuci') {
            return back()->withErrors(['error' => 'Order belum siap untuk diantarkan.']);
        }

        $transaction->update([
            'service_status' => 'diantar',
            'kurir_id'       => Auth::id(),
        ]);

        return back()->with('success', "Order {$transaction->order_id} sedang diantarkan.");
    }

    /**
     * Kurir selesaikan pengantaran
     * Status: diantar -> selesai_total
     */
    public function selesaikanOrder(Transaction $transaction)
    {
        if ($transaction->kurir_id !== Auth::id()) {
            abort(403, 'Anda bukan kurir untuk order ini.');
        }

        $transaction->update(['service_status' => 'selesai_total']);

        return back()->with('success', "Order {$transaction->order_id} telah selesai diserahkan.");
    }

    /**
     * Kurir update koordinat GPS secara realtime
     * Body: { latitude, longitude }
     * Server hitung distance & eta berdasarkan koordinat tujuan pelanggan
     */
    public function updateLocation(Request $request)
    {
        $validated = $request->validate([
            'latitude'  => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $kurir = Auth::user();
        $kurir->update([
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        // Cari order aktif yang sedang diantar oleh kurir ini
        $order = Transaction::where('kurir_id', $kurir->id)
            ->where('service_status', 'diantar')
            ->with('user')
            ->first();

        $distance  = null;
        $eta       = null;

        if ($order && $order->user) {
            $tujuanLat = $order->user->latitude;
            $tujuanLon = $order->user->longitude;

            if ($tujuanLat && $tujuanLon) {
                $distance = $this->haversineDistance(
                    $validated['latitude'], $validated['longitude'],
                    $tujuanLat, $tujuanLon
                );
                // Estimasi kecepatan rata-rata kurir motor: 30 km/jam
                $eta = round(($distance / 30) * 60); // dalam menit

                $order->update(['distance' => $distance, 'eta_minutes' => $eta]);
            }
        }

        return response()->json([
            'success'      => true,
            'kurir_lat'    => $kurir->latitude,
            'kurir_lng'    => $kurir->longitude,
            'distance_km'  => $distance,
            'eta_minutes'  => $eta,
        ]);
    }

    /**
     * Hitung jarak dua koordinat menggunakan rumus Haversine (km)
     */
    private function haversineDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
           + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return round($earthRadius * $c, 2);
    }
}
