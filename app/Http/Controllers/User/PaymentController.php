<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ReservasiHotel;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric',
            'first_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'reservasi_id' => 'required|numeric'
        ]);

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Prepare transaction details
        $transactionDetails = [
            'order_id' => 'RSV-' . $request->reservasi_id . '-' . time(),
            'gross_amount' => $request->amount,
        ];

        $itemDetails = [
            [
                'id' => 'HOTEL-' . $request->reservasi_id,
                'price' => $request->amount,
                'quantity' => 1,
                'name' => 'Hotel Reservation'
            ],
        ];

        $customerDetails = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        $transactionData = [
            'transaction_details' => $transactionDetails,
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
            'callbacks' => [
                'finish' => route('booking.index')
            ]
        ];

        try {
            $snapToken = Snap::getSnapToken($transactionData);
            return response()->json([
                'token' => $snapToken,
                'redirect_url' => route('booking.index'),
                'reservasi_id' => $request->reservasi_id
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans payment error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses pembayaran.'], 500);
        }
    }

    public function notification(Request $request)
    {
        try {
            $transaction = $request->all();
            
            // Extract order ID and get reservation ID
            $orderId = $transaction['order_id'];
            preg_match('/RSV-(\d+)-/', $orderId, $matches);
            $reservasiId = $matches[1] ?? null;

            if (!$reservasiId) {
                Log::error('Invalid order ID format: ' . $orderId);
                return response()->json(['error' => 'Invalid order ID'], 400);
            }

            $reservasiHotel = ReservasiHotel::find($reservasiId);
            if (!$reservasiHotel) {
                Log::error('Reservation not found: ' . $reservasiId);
                return response()->json(['error' => 'Reservation not found'], 404);
            }

            $status = $transaction['transaction_status'];
            switch ($status) {
                case 'capture':
                case 'settlement':
                    $reservasiHotel->update([
                        'status' => 'di bayar'
                    ]);
                    Session::flash('success', 'Pembayaran berhasil! Reservasi Anda telah dikonfirmasi.');
                    break;
                    
                case 'pending':
                    $reservasiHotel->update([
                        'status' => 'pending'
                    ]);
                    break;
                    
                case 'deny':
                case 'cancel':
                case 'expire':
                    $reservasiHotel->update([
                        'status' => 'cancel'
                    ]);
                    break;
                    
                default:
                    Log::error('Unknown transaction status: ' . $status);
                    break;
            }

            return response()->json([
                'success' => true,
                'status' => $status,
                'message' => 'Status pembayaran berhasil diperbarui'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Payment notification error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat memproses notifikasi pembayaran'], 500);
        }
    }

    // Add method to manually update status (optional, for testing)
    public function updateStatus($reservasiId)
    {
        try {
            $reservasi = ReservasiHotel::findOrFail($reservasiId);
            $reservasi->update(['status' => 'di bayar']);
            
            return redirect()->route('booking.index')
                ->with('success', 'Status pembayaran berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Manual status update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status pembayaran');
        }
    }
}