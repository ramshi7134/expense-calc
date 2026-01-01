@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Set Monthly Budget</h1>
        <form action="{{ route('budgets.store') }}" method="POST" class="card bg-base-100 shadow-xl p-6">
            @csrf
            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">Category</label>
                <select name="category_id" id="category_id" class="input input-bordered w-full" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="limit" class="block font-semibold mb-1">Monthly Limit (AED)</label>
                <input type="number" step="0.01" name="limit" id="limit" class="input input-bordered w-full"
                    required>
            </div>
            <div class="mb-4">
                <label for="month" class="block font-semibold mb-1">Month</label>
                <select name="month" id="month" class="input input-bordered w-full" required>
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" @if ($m == now()->month) selected @endif>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endfor
                </select>
            </div>
            <div class="mb-4">
                <label for="year" class="block font-semibold mb-1">Year</label>
                <input type="number" name="year" id="year" class="input input-bordered w-full"
                    value="{{ now()->year }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Set Budget</button>
            <a href="{{ route('budgets.index') }}" class="ml-4 btn btn-ghost">Cancel</a>
        </form>
    </div>
@endsection
