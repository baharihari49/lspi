<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PaymentMethod::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->inactive();
            }
        }

        // Get payment methods with pagination
        $paymentMethods = $query->ordered()->paginate(15)->withQueryString();

        // Get statistics
        $stats = [
            'total' => PaymentMethod::count(),
            'active' => PaymentMethod::active()->count(),
            'inactive' => PaymentMethod::inactive()->count(),
            'bank_transfer' => PaymentMethod::byCategory('bank_transfer')->count(),
            'e_wallet' => PaymentMethod::byCategory('e_wallet')->count(),
        ];

        // Get unique categories for filter
        $categories = PaymentMethod::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.payment-methods.index', compact('paymentMethods', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.payment-methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:payment_methods,code',
            'name' => 'required|string|max:100',
            'category' => 'required|string|in:bank_transfer,e_wallet,qris,virtual_account,credit_card,debit_card,cash,other',
            'description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'provider_name' => 'nullable|string|max:100',
            'gateway_code' => 'nullable|string|max:50',
            'admin_fee_percentage' => 'required|numeric|min:0|max:100',
            'admin_fee_fixed' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0|gte:min_amount',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions' => 'nullable|string',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'requires_manual_verification' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('payment-methods', 'public');
            $validated['logo_url'] = Storage::url($logoPath);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_manual_verification'] = $request->has('requires_manual_verification');

        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.show', compact('paymentMethod'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:payment_methods,code,' . $paymentMethod->id,
            'name' => 'required|string|max:100',
            'category' => 'required|string|in:bank_transfer,e_wallet,qris,virtual_account,credit_card,debit_card,cash,other',
            'description' => 'nullable|string',
            'bank_name' => 'nullable|string|max:100',
            'account_number' => 'nullable|string|max:50',
            'account_holder_name' => 'nullable|string|max:100',
            'provider_name' => 'nullable|string|max:100',
            'gateway_code' => 'nullable|string|max:50',
            'admin_fee_percentage' => 'required|numeric|min:0|max:100',
            'admin_fee_fixed' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0|gte:min_amount',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
            'instructions' => 'nullable|string',
            'display_order' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'requires_manual_verification' => 'boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($paymentMethod->logo_url) {
                $oldPath = str_replace('/storage/', '', $paymentMethod->logo_url);
                Storage::disk('public')->delete($oldPath);
            }

            $logoPath = $request->file('logo')->store('payment-methods', 'public');
            $validated['logo_url'] = Storage::url($logoPath);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['requires_manual_verification'] = $request->has('requires_manual_verification');

        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        // Check if payment method is being used
        if ($paymentMethod->payments()->count() > 0) {
            return back()->with('error', 'Cannot delete payment method that has been used in transactions.');
        }

        // Delete logo if exists
        if ($paymentMethod->logo_url) {
            $logoPath = str_replace('/storage/', '', $paymentMethod->logo_url);
            Storage::disk('public')->delete($logoPath);
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Payment method deleted successfully.');
    }

    /**
     * Toggle active status
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active
        ]);

        $status = $paymentMethod->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Payment method {$status} successfully.");
    }
}
