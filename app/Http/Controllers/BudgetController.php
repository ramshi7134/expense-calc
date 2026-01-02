<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BudgetController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $budgets = Auth::user()->budgets()
            ->with('category')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->groupBy(['year', 'month']);

        return view('budgets.index', compact('budgets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('budgets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
                Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('user_id', Auth::id())
                        ->where('month', $request->month)
                        ->where('year', $request->year);
                }),
            ],
            'limit' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:' . date('Y'),
        ], [
            'category_id.unique' => 'A budget for this category already exists for the selected month and year.'
        ]);

        Auth::user()->budgets()->create($request->all());

        return redirect()->route('budgets.index')->with('success', 'Budget created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budget)
    {
        $this->authorize('update', $budget);
        $categories = Category::all();
        return view('budgets.edit', compact('budget', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Budget $budget)
    {
        $this->authorize('update', $budget);

        $request->validate([
            'category_id' => [
                'required',
                'exists:categories,id',
                Rule::unique('budgets')->where(function ($query) use ($request, $budget) {
                    return $query->where('user_id', Auth::id())
                        ->where('month', $request->month)
                        ->where('year', $request->year)
                        ->where('id', '!=', $budget->id);
                }),
            ],
            'limit' => 'required|numeric|min:0',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:' . date('Y'),
        ], [
            'category_id.unique' => 'A budget for this category already exists for the selected month and year.'
        ]);

        $budget->update($request->all());

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Budget $budget)
    {
        $this->authorize('delete', $budget);
        $budget->delete();
        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully.');
    }
}