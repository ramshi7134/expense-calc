<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\EmiPlan;
use Carbon\Carbon;

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

        // EMI Summary
        $activeEmiPlans = EmiPlan::where('user_id', $user->id)
            ->whereHas('installments', function ($query) {
                $query->where('status', 'pending');
            })
            ->with('installments')
            ->get();

        $totalEmiOutstanding = $activeEmiPlans->sum(function ($plan) {
            return $plan->installments->where('status', 'pending')->sum('amount');
        });

        // Calculate this month's EMI due
        $currentMonthEmiDue = \App\Models\EmiInstallment::where('month', $month)
            ->where('year', $year)
            ->where('status', '!=', 'paid')
            ->whereHas('emiPlan', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->sum('amount');

        // New: Calculate next month's EMI due
        $nextMonthDate = Carbon::create($year, $month, 1)->addMonth();
        $nextMonthEmiDue = \App\Models\EmiInstallment::where('month', $nextMonthDate->month)
            ->where('year', $nextMonthDate->year)
            ->where('status', '!=', 'paid')
            ->whereHas('emiPlan', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->sum('amount');

        return view('dashboard.index', compact(
            'totalExpenses',
            'totalBudget',
            'remainingBudget',
            'categorySummary',
            'month',
            'year',
            'totalEmiOutstanding',
            'currentMonthEmiDue',
            'nextMonthEmiDue'
        ));
    }
}
