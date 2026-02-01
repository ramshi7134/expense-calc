<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentTypes = Auth::user()->paymentTypes;
        return view('payment-types.index', compact('paymentTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:debit,credit',
            'statement_day' => 'nullable|integer|min:1|max:31',
            'last_four_digits' => 'nullable|digits:4',
        ]);

        Auth::user()->paymentTypes()->create($request->only('name', 'type', 'statement_day', 'last_four_digits'));

        return redirect()->route('payment-types.index')->with('success', 'Payment type added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentType $paymentType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentType $paymentType)
    {
        $this->authorize('update', $paymentType);
        return view('payment-types.edit', compact('paymentType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentType $paymentType)
    {
        $this->authorize('update', $paymentType);

        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:debit,credit',
            'statement_day' => 'nullable|integer|min:1|max:31',
            'last_four_digits' => 'nullable|digits:4',
        ]);

        $paymentType->update($request->only('name', 'type', 'statement_day', 'last_four_digits'));

        return redirect()->route('payment-types.index')->with('success', 'Payment type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentType $paymentType)
    {
        $this->authorize('delete', $paymentType);
        $paymentType->delete();
        return redirect()->route('payment-types.index')->with('success', 'Payment type deleted successfully.');
    }
}
