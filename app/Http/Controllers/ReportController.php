<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $categories = \App\Models\Category::all();
        return view('reports.index', compact('categories'));
    }

    public function filter(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $categoryId = $request->input('category_id');

        $query = \App\Models\Expense::with('category')
            ->whereBetween('date', [$startDate, $endDate]);

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $expenses = $query->latest()->get();
        $total = $expenses->sum('amount');
        $categories = \App\Models\Category::all();

        return view('reports.index', compact('expenses', 'total', 'categories', 'startDate', 'endDate', 'categoryId'));
    }
}
