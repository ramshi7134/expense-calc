<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Expense;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get month and year from request or default to current
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        // Get all categories for user (default + custom)
        $categories = Category::where(function($q) use ($user) {
            $q->whereNull('user_id')->orWhere('user_id', $user->id);
        })->get();

        // Get budgets for selected month
        $budgets = Budget::where('user_id', $user->id)
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        // Get expenses for selected month
        $expenses = Expense::where('user_id', $user->id)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get();

        $totalExpenses = $expenses->sum('amount');
        
        // Calculate remaining budget for all budgets (sum across categories)
        $totalBudget = $budgets->sum('limit');
        $remainingBudget = $totalBudget - $totalExpenses;

        // Category-wise summary with percentage
        $categorySummary = $categories->map(function($category) use ($expenses, $budgets) {
            $spent = $expenses->where('category_id', $category->id)->sum('amount');
            $budget = $budgets->where('category_id', $category->id)->first();
            $limit = $budget ? $budget->limit : 0;
            $percentage = $limit > 0 ? min(100, ($spent / $limit) * 100) : 0;
            
            return [
                'id' => $category->id,
                'name' => $category->name,
                'spent' => round($spent, 2),
                'limit' => round($limit, 2),
                'percentage' => round($percentage),
                'remaining' => round($limit - $spent, 2),
            ];
        })->filter(function($item) {
            // Filter out categories with no budget set
            return $item['limit'] > 0;
        });

        return view('dashboard.index', [
            'totalExpenses' => round($totalExpenses, 2),
            'totalBudget' => round($totalBudget, 2),
            'remainingBudget' => round($remainingBudget, 2),
            'categorySummary' => $categorySummary,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
