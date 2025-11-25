<?php

namespace App\Http\Controllers\Assessee;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MyPaymentController extends Controller
{
    /**
     * Get the current user's assessee record
     */
    private function getAssessee()
    {
        $user = auth()->user();
        return $user->assessee ?? null;
    }

    /**
     * Display a listing of user's payments.
     */
    public function index(Request $request)
    {
        $userId = auth()->id();

        $query = Payment::where('payer_id', $userId);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->with(['paymentMethod'])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $stats = [
            'total' => Payment::where('payer_id', $userId)->count(),
            'pending' => Payment::where('payer_id', $userId)->where('status', 'pending')->count(),
            'paid' => Payment::where('payer_id', $userId)->where('status', 'paid')->count(),
            'total_paid' => Payment::where('payer_id', $userId)->where('status', 'paid')->sum('total_amount'),
        ];

        return view('assessee.my-payments.index', compact('payments', 'stats'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        if ($payment->payer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $payment->load(['paymentMethod', 'items', 'statusHistory']);

        return view('assessee.my-payments.show', compact('payment'));
    }

    /**
     * Show upload proof form.
     */
    public function uploadProof(Payment $payment)
    {
        if ($payment->payer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        if ($payment->status !== 'pending') {
            return redirect()->route('admin.my-payments.show', $payment)
                ->with('error', 'Pembayaran ini tidak memerlukan upload bukti.');
        }

        return view('assessee.my-payments.upload-proof', compact('payment'));
    }

    /**
     * Store payment proof.
     */
    public function storeProof(Request $request, Payment $payment)
    {
        if ($payment->payer_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pembayaran ini.');
        }

        $validated = $request->validate([
            'payment_proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Max 5MB
            'payment_date' => 'required|date',
            'sender_name' => 'required|string|max:255',
            'sender_bank' => 'required|string|max:100',
            'payer_notes' => 'nullable|string|max:500',
        ]);

        // Store file
        $file = $request->file('payment_proof');
        $path = $file->store('payment-proofs/' . auth()->id(), 'public');

        // Update payment
        $payment->update([
            'payment_proof_url' => Storage::url($path),
            'payment_proof_uploaded_at' => now(),
            'payment_date' => $validated['payment_date'],
            'sender_name' => $validated['sender_name'],
            'sender_bank' => $validated['sender_bank'],
            'payer_notes' => $validated['payer_notes'],
            'status' => 'pending_verification',
        ]);

        return redirect()->route('admin.my-payments.show', $payment)
            ->with('success', 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.');
    }
}
