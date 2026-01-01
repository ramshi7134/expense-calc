@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-100">

        <div class="max-w-7xl mx-auto px-6 py-10">

            <!-- ================= HEADER ================= -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 mb-12">

                <h1 class="text-3xl font-extrabold text-gray-900">
                    Monthly Expense Dashboard
                </h1>

                <div class="flex items-center gap-4">
                    <!-- Month Selector -->
                    <select onchange="const [m, y] = this.value.split('-'); window.location = `?month=${m}&year=${y}`"
                        class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-gray-800 shadow-sm">
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

            <!-- ================= SUMMARY CARDS ================= -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">

                <!-- Total Expenses -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Total Expenses
                    </p>
                    <p class="text-4xl font-extrabold text-red-500">
                        AED {{ number_format($totalExpenses, 2) }}
                    </p>
                </div>

                <!-- Remaining Budget -->
                <div class="bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg">
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Remaining Budget
                    </p>
                    <p class="text-4xl font-extrabold {{ $remainingBudget >= 0 ? 'text-emerald-500' : 'text-red-500' }}">
                        AED {{ number_format($remainingBudget, 2) }}
                    </p>
                </div>
            </div>

            <!-- ================= CATEGORY SUMMARY ================= -->
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                Category Summary
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                @forelse ($categorySummary as $category)
                    <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-md hover:shadow-xl transition">

                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $category['name'] }}
                            </h3>
                            <span class="text-sm text-gray-500">
                                {{ $category['percentage'] }}%
                            </span>
                        </div>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            AED {{ number_format($category['spent'], 2) }}
                            <span class="text-gray-400">
                                / AED {{ number_format($category['limit'], 2) }}
                            </span>
                        </p>

                        <!-- Progress -->
                        <div class="w-full bg-gray-200 dark:bg-gray-800 rounded-full h-3 overflow-hidden mb-4">
                            <div class="h-3 rounded-full"
                                style="width: {{ min($category['percentage'], 100) }}%;
                                   background: linear-gradient(90deg,#3b82f6,#2563eb);">
                            </div>
                        </div>

                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Remaining:
                            <span class="font-semibold text-gray-900 dark:text-white">
                                AED {{ number_format($category['remaining'], 2) }}
                            </span>
                        </p>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-gray-900 rounded-2xl p-10 shadow text-center">
                        <p class="text-gray-500 mb-4">
                            No budget set for any category.
                        </p>
                        <a href="{{ route('categories.index') }}"
                            class="inline-block px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Set Budget
                        </a>
                    </div>
                @endforelse

            </div>

            <!-- ================= CHART ================= -->
            <div class="mt-20 bg-white dark:bg-gray-900 rounded-2xl p-8 shadow-lg">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">
                    Category-wise Expenses
                </h2>

                <div id="expenseChart"></div>
            </div>

        </div>
    </div>

    <!-- ================= APEX CHART ================= -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new ApexCharts(document.querySelector("#expenseChart"), {
                chart: {
                    type: 'donut',
                    height: 360
                },
                series: @json(collect($categorySummary)->pluck('spent')),
                labels: @json(collect($categorySummary)->pluck('name')),
                colors: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6'],
                legend: {
                    position: 'bottom',
                    labels: {
                        colors: '#9ca3af'
                    }
                }
            }).render();
        });
    </script>
@endsection
