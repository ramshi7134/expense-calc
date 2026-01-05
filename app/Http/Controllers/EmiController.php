<?php

namespace App\Http\Controllers;

use App\Models\EmiPlan;
use App\Models\EmiInstallment;
use App\Models\Category;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $emiPlans = Auth::user()->emiPlans()->with('installments')->latest()->get();
        return view('emis.index', compact('emiPlans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('emis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01',
            'months' => 'required|integer|min:1',
            'start_month' => 'required|integer|min:1|max:12',
            'start_year' => 'required|integer|min:' . (date('Y') - 5), // Allow up to 5 years in the past
            'interest_rate' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $plan = Auth::user()->emiPlans()->create($request->all());

            $totalAmount = $plan->total_amount;
            $numberOfMonths = $plan->months;

            // For now, we'll handle simple, equal installments without interest calculation.
            // A more complex interest calculation could be added later.
            $monthlyInstallment = round($totalAmount / $numberOfMonths, 2);
            $totalCalculated = $monthlyInstallment * ($numberOfMonths - 1);
            $lastInstallment = $totalAmount - $totalCalculated;

            $startDate = Carbon::create($plan->start_year, $plan->start_month, 1);

            for ($i = 0; $i < $numberOfMonths; $i++) {
                $currentDate = $startDate->copy()->addMonths($i);
                $amount = ($i === $numberOfMonths - 1) ? $lastInstallment : $monthlyInstallment;

                EmiInstallment::create([
                    'emi_plan_id' => $plan->id,
                    'month' => $currentDate->month,
                    'year' => $currentDate->year,
                    'amount' => $amount,
                    'due_date' => $currentDate->endOfMonth(), // Or a specific day like ->day(5)
                    'status' => 'pending',
                ]);
            }
        });

        return redirect()->route('emis.index')->with('success', 'EMI Plan created successfully and installments generated.');
    }

    /**
     * Display the specified resource.
     */
    public function show(EmiPlan $emi)
    {
        $emi->load('installments');
        return view('emis.show', compact('emi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EmiPlan $emi)
    {
        // Editing a plan after creation can be complex if installments are paid.
        // For now, we will redirect back with a message.
        return redirect()->route('emis.index')->with('info', 'Editing EMI plans is not supported yet.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, EmiPlan $emi)
    {
        // Placeholder
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(EmiPlan $emi)
    {
        // Ensure user is authorized to delete this plan
        if ($emi->user_id !== Auth::id()) {
            abort(403);
        }

        $emi->delete(); // Associated installments will be deleted by the DB cascade rule

        return redirect()->route('emis.index')->with('success', 'EMI Plan deleted successfully.');
    }

    /**
     * Mark an installment as paid.
     */
    public function pay(Request $request, EmiInstallment $installment)
    {
        // Eager load the plan with its user to authorize
        $installment->load('emiPlan.user');

        // Authorize: ensure the installment belongs to the authenticated user
        if ($installment->emiPlan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Prevent double payment
        if ($installment->status === 'paid') {
            return redirect()->back()->with('error', 'This installment has already been paid.');
        }

        DB::transaction(function () use ($installment) {
            // 1. Update the installment status
            $installment->status = 'paid';
            $installment->paid_date = Carbon::now();
            $installment->save();

            // 2. Find or create the 'EMI' expense category
            $emiCategory = Category::firstOrCreate(
                ['name' => 'EMI'],
                ['user_id' => Auth::id()] // Associate with user if creating
            );

            // 3. Create a corresponding expense record
            Expense::create([
                'user_id' => Auth::id(),
                'category_id' => $emiCategory->id,
                'amount' => $installment->amount,
                'description' => 'EMI Payment: ' . $installment->emiPlan->name . ' (Installment #' . $installment->id . ')',
                'date' => $installment->paid_date,
            ]);
        });

        return redirect()->route('emis.show', $installment->emiPlan)->with('success', 'Installment paid and expense recorded successfully.');
    }
}
