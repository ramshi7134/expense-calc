<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $expenses = \App\Models\Expense::with(['category', 'paymentType'])
            ->where('user_id', $user->id)
            ->orderByDesc('date')
            ->get();
        return view('expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $categories = \App\Models\Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();
        $paymentTypes = $user->paymentTypes;
        return view('expenses.create', compact('categories', 'paymentTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);
        $validated['user_id'] = $user->id;
        \App\Models\Expense::create($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $expense = \App\Models\Expense::where('user_id', $user->id)->findOrFail($id);
        $categories = \App\Models\Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();
        $paymentTypes = $user->paymentTypes;
        return view('expenses.edit', compact('expense', 'categories', 'paymentTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = auth()->user();
        $expense = \App\Models\Expense::where('user_id', $user->id)->findOrFail($id);
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_type_id' => 'nullable|exists:payment_types,id',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);
        $expense->update($validated);
        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = auth()->user();
        $expense = \App\Models\Expense::where('user_id', $user->id)->findOrFail($id);
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
