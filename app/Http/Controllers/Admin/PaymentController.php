<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['paymentMethod', 'items']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('payer_name', 'like', "%{$search}%")
                    ->orWhere('payer_email', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Payment method filter
        if ($request->filled('payment_method')) {
            $query->where('payment_method_id', $request->payment_method);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Get payments with pagination
        $payments = $query->latest('created_at')->paginate(15)->withQueryString();

        // Get statistics
        $stats = [
            'pending' => Payment::pending()->count(),
            'paid' => Payment::paid()->count(),
            'partial' => Payment::byStatus('partial')->count(),
            'overdue' => Payment::overdue()->count(),
            'total_amount' => Payment::paid()->sum('total_amount'),
        ];

        // Get payment methods for filter
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('admin.payments.index', compact('payments', 'stats', 'paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('admin.payments.create', compact('paymentMethods'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'payer_type' => 'required|string|in:assessee,organization,individual',
            'payer_name' => 'required|string|max:200',
            'payer_email' => 'nullable|email|max:100',
            'payer_phone' => 'nullable|string|max:20',
            'payer_address' => 'nullable|string',
            'payable_type' => 'required|string|in:event,assessment,certificate_renewal,other',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string',
            'discount_code' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'required|numeric|min:0',
            'admin_fee' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|string',
            'items.*.item_name' => 'required|string|max:200',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Generate payment number
            $paymentNumber = Payment::generatePaymentNumber();

            // Create payment
            $payment = Payment::create([
                'payment_number' => $paymentNumber,
                'payer_type' => $validated['payer_type'],
                'payer_name' => $validated['payer_name'],
                'payer_email' => $validated['payer_email'] ?? null,
                'payer_phone' => $validated['payer_phone'] ?? null,
                'payer_address' => $validated['payer_address'] ?? null,
                'payable_type' => $validated['payable_type'],
                'payable_id' => 0, // Will be set when linked to actual entity
                'payment_method_id' => $validated['payment_method_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $validated['subtotal'],
                'admin_fee' => $validated['admin_fee'],
                'discount_amount' => $validated['discount_amount'],
                'discount_code' => $validated['discount_code'] ?? null,
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'tax_amount' => $validated['tax_amount'],
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'paid_amount' => 0,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create payment items
            foreach ($validated['items'] as $itemData) {
                PaymentItem::createWithCalculation([
                    'payment_id' => $payment->id,
                    'item_type' => $itemData['item_type'],
                    'item_name' => $itemData['item_name'],
                    'description' => $itemData['description'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                    'discount_amount' => 0,
                    'tax_percentage' => $validated['tax_percentage'] ?? 0,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create payment: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        $payment->load(['paymentMethod', 'items', 'statusHistory' => function ($query) {
            $query->recent();
        }]);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        // Only allow editing pending payments
        if (!$payment->isPending()) {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Only pending payments can be edited.');
        }

        $payment->load('items');
        $paymentMethods = PaymentMethod::active()->ordered()->get();

        return view('admin.payments.edit', compact('payment', 'paymentMethods'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        // Only allow updating pending payments
        if (!$payment->isPending()) {
            return redirect()->route('admin.payments.show', $payment)
                ->with('error', 'Only pending payments can be updated.');
        }

        $validated = $request->validate([
            'payer_type' => 'required|string|in:assessee,organization,individual',
            'payer_name' => 'required|string|max:200',
            'payer_email' => 'nullable|email|max:100',
            'payer_phone' => 'nullable|string|max:20',
            'payer_address' => 'nullable|string',
            'payable_type' => 'required|string|in:event,assessment,certificate_renewal,other',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'notes' => 'nullable|string',
            'discount_code' => 'nullable|string|max:50',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'subtotal' => 'required|numeric|min:0',
            'admin_fee' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'tax_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:payment_items,id',
            'items.*.item_type' => 'required|string',
            'items.*.item_name' => 'required|string|max:200',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'deleted_items' => 'nullable|array',
            'deleted_items.*' => 'exists:payment_items,id',
        ]);

        DB::beginTransaction();
        try {
            // Update payment
            $payment->update([
                'payer_type' => $validated['payer_type'],
                'payer_name' => $validated['payer_name'],
                'payer_email' => $validated['payer_email'] ?? null,
                'payer_phone' => $validated['payer_phone'] ?? null,
                'payer_address' => $validated['payer_address'] ?? null,
                'payable_type' => $validated['payable_type'],
                'payment_method_id' => $validated['payment_method_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $validated['subtotal'],
                'admin_fee' => $validated['admin_fee'],
                'discount_amount' => $validated['discount_amount'],
                'discount_code' => $validated['discount_code'] ?? null,
                'discount_percentage' => $validated['discount_percentage'] ?? 0,
                'tax_amount' => $validated['tax_amount'],
                'tax_percentage' => $validated['tax_percentage'] ?? 0,
                'total_amount' => $validated['total_amount'],
                'notes' => $validated['notes'] ?? null,
            ]);

            // Delete removed items
            if (!empty($validated['deleted_items'])) {
                PaymentItem::whereIn('id', $validated['deleted_items'])
                    ->where('payment_id', $payment->id)
                    ->delete();
            }

            // Update or create items
            foreach ($validated['items'] as $itemData) {
                if (!empty($itemData['id'])) {
                    // Update existing item
                    $item = PaymentItem::find($itemData['id']);
                    if ($item && $item->payment_id === $payment->id) {
                        $item->updateWithCalculation([
                            'item_type' => $itemData['item_type'],
                            'item_name' => $itemData['item_name'],
                            'description' => $itemData['description'] ?? null,
                            'quantity' => $itemData['quantity'],
                            'unit_price' => $itemData['unit_price'],
                            'tax_percentage' => $validated['tax_percentage'] ?? 0,
                        ]);
                    }
                } else {
                    // Create new item
                    PaymentItem::createWithCalculation([
                        'payment_id' => $payment->id,
                        'item_type' => $itemData['item_type'],
                        'item_name' => $itemData['item_name'],
                        'description' => $itemData['description'] ?? null,
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'discount_amount' => 0,
                        'tax_percentage' => $validated['tax_percentage'] ?? 0,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update payment: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        // Only allow deleting pending payments
        if (!$payment->isPending()) {
            return back()->with('error', 'Only pending payments can be deleted.');
        }

        try {
            $payment->delete();

            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }

    /**
     * Mark payment as paid
     */
    public function markAsPaid(Request $request, Payment $payment)
    {
        if (!$payment->isPending() && !$payment->isPartial()) {
            return back()->with('error', 'Only pending or partial payments can be marked as paid.');
        }

        $validated = $request->validate([
            'transaction_id' => 'nullable|string|max:100',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $payment->markAsPaid(
                $validated['transaction_id'] ?? null,
                null
            );

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment marked as paid successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to mark payment as paid: ' . $e->getMessage());
        }
    }

    /**
     * Cancel payment
     */
    public function cancel(Request $request, Payment $payment)
    {
        if (!$payment->isPending()) {
            return back()->with('error', 'Only pending payments can be cancelled.');
        }

        $validated = $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        try {
            $payment->cancel($validated['reason'] ?? 'Payment cancelled by admin');

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment cancelled successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel payment: ' . $e->getMessage());
        }
    }

    /**
     * Verify payment proof
     */
    public function verify(Payment $payment)
    {
        if ($payment->isVerified()) {
            return back()->with('error', 'Payment proof is already verified.');
        }

        if (!$payment->hasProof()) {
            return back()->with('error', 'No payment proof to verify.');
        }

        try {
            $payment->markAsVerified(auth()->id());

            // If payment is pending and verified, mark as paid
            if ($payment->isPending()) {
                $payment->markAsPaid();
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Payment proof verified successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to verify payment proof: ' . $e->getMessage());
        }
    }
}
