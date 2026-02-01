<?php

namespace App\Providers;

use App\Models\Budget;
use App\Policies\BudgetPolicy;
use App\Models\Receipt;
use App\Policies\ReceiptPolicy;
use App\Models\PaymentType;
use App\Policies\PaymentTypePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Budget::class => BudgetPolicy::class,
        Receipt::class => ReceiptPolicy::class,
        PaymentType::class => PaymentTypePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
