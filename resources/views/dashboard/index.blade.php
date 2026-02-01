@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Monthly Expense Dashboard</h1>
            <!-- Month Selector -->
            <div class="col-md-3">
                <select onchange="const [m, y] = this.value.split('-'); window.location = `?month=${m}&year=${y}`"
                    class="form-select">
                    @for ($y = now()->year; $y >= 2020; $y--)
                        @for ($m = 12; $m >= 1; $m--)
                            @php
                                $selected = $m == $month && $y == $year ? 'selected' : '';
                                $monthName = date('F', mktime(0, 0, 0, $m, 1));
                            @endphp
                            <option value="{{ $m }}-{{ $y }}" {{ $selected }}>
                                {{ $monthName }} {{ $y }}
                            </option>
                        @endfor
                    @endfor
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Expenses</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ format_currency($totalExpenses) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Remaining Budget</div>
                                <div
                                    class="h5 mb-0 font-weight-bold {{ $remainingBudget >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ format_currency($remainingBudget) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-wallet fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total EMI Outstanding</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ format_currency($totalEmiOutstanding) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- EMI Summary Row -->
        <div class="row justify-content-center">
            <div class="col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    This Month's EMI Due</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ format_currency($currentMonthEmiDue) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Next Month's EMI Due</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ format_currency($nextMonthEmiDue) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Category Summary -->
        <h2 class="h4 mb-3 text-gray-800">Category Summary</h2>
        <div class="row">
            @forelse ($categorySummary as $category)
                @if ($category['limit'] > 0 || $category['spent'] > 0)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category['name'] }}</h5>
                                <p class="card-text">
                                    Spent: {{ format_currency($category['spent']) }} /
                                    <span class="text-muted">{{ format_currency($category['limit']) }}</span>
                                </p>
                                <div class="progress mb-2">
                                    <div class="progress-bar" role="progressbar"
                                        style="width: {{ $category['percentage'] }}%;"
                                        aria-valuenow="{{ $category['percentage'] }}" aria-valuemin="0"
                                        aria-valuemax="100">
                                        {{ round($category['percentage']) }}%
                                    </div>
                                </div>
                                <p class="card-text text-muted">
                                    Remaining: {{ format_currency($category['remaining']) }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            @empty
                <div class="col-12">
                    <div class="card shadow text-center p-4">
                        <p>You haven't set a budget for any category this month.</p>
                        <a href="{{ route('budgets.create') }}" class="btn btn-primary">Set Monthly Budget</a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Charts Row -->
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-lg-6 mb-4">
                @if (collect($categorySummary)->where('spent', '>', 0)->isNotEmpty())
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Category-wise Expenses</h6>
                        </div>
                        <div class="card-body">
                            <div id="expenseChart" style="height: 350px;"></div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Bar Chart -->
            <div class="col-lg-6 mb-4">
                @if (collect($categorySummary)->where('limit', '>', 0)->isNotEmpty())
                    <div class="card shadow h-100">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Budget vs. Spent</h6>
                        </div>
                        <div class="card-body">
                            <div id="budgetVsSpentChart" style="height: 350px;"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Credit Card Statements -->
        @if (!empty($creditCardStatements))
            <div class="row">
                <div class="col-12">
                    <h2 class="h4 mb-3 text-gray-800">Credit Card Statements Due This Month</h2>
                    <div class="row">
                        @foreach ($creditCardStatements as $statement)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card shadow-sm h-100">
                                    <div class="card-body">
                                        <h5 class="card-title fw-bold">{{ $statement['name'] }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">
                                            Statement for {{ $statement['start_date'] }} - {{ $statement['end_date'] }}
                                        </h6>
                                        <p class="card-text fs-4 fw-bold text-primary">
                                            {{ format_currency($statement['total']) }}
                                        </p>
                                    </div>
                                    <div class="card-footer text-muted">
                                        Statement Date: Every month on day {{ $statement['statement_day'] }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Donut Chart for Category-wise Expenses
                if (document.querySelector("#expenseChart")) {
                    var options = {
                        series: @json(collect($categorySummary)->where('spent', '>', 0)->pluck('spent')),
                        chart: {
                            type: 'donut',
                            height: 350
                        },
                        labels: @json(collect($categorySummary)->where('spent', '>', 0)->pluck('name')),
                        colors: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'],
                        dataLabels: {
                            enabled: true,
                            formatter: function(val, opts) {
                                return opts.w.globals.labels[opts.seriesIndex]
                            }
                        },
                        legend: {
                            position: 'bottom'
                        },
                    };

                    var chart = new ApexCharts(document.querySelector("#expenseChart"), options);
                    chart.render();
                }

                // Bar Chart for Budget vs. Spent
                if (document.querySelector("#budgetVsSpentChart")) {
                    const budgetData = @json(collect($categorySummary)->where('limit', '>', 0)->values()->all());

                    var budgetVsSpentOptions = {
                        series: [{
                            name: 'Budget',
                            data: budgetData.map(item => item.limit)
                        }, {
                            name: 'Spent',
                            data: budgetData.map(item => item.spent)
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: '55%',
                                endingShape: 'rounded'
                            },
                        },
                        dataLabels: {
                            enabled: false
                        },
                        stroke: {
                            show: true,
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: budgetData.map(item => item.name),
                        },
                        yaxis: {
                            title: {
                                text: '{{ auth()->user()->currency }}'
                            }
                        },
                        fill: {
                            opacity: 1
                        },
                        tooltip: {
                            y: {
                                formatter: function(val) {
                                    return "{{ auth()->user()->currency }} " + val
                                }
                            }
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left'
                        }
                    };

                    var budgetChart = new ApexCharts(document.querySelector("#budgetVsSpentChart"),
                        budgetVsSpentOptions);
                    budgetChart.render();
                }
            });
        </script>
    @endpush
@endsection
