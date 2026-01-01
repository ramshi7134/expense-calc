@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Category Limit</h1>
        <form action="{{ route('budgets.update', $budget) }}" method="POST" class="card bg-base-100 shadow-xl p-6">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">Category</label>
                <input type="text" class="input input-bordered w-full" value="{{ $budget->category->name }}" disabled>
            </div>
            <div class="mb-4">
                <label for="limit" class="block font-semibold mb-1">Monthly Limit (AED)</label>
                <input type="number" step="0.01" name="limit" id="limit" class="input input-bordered w-full"
                    value="{{ $budget->limit }}" required>
            </div>
            <div class="mb-4">
                <label for="month" class="block font-semibold mb-1">Month</label>
                <input type="text" class="input input-bordered w-full"
                    value="{{ date('F', mktime(0, 0, 0, $budget->month, 1)) }}" disabled>
            </div>
            <div class="mb-4">
                <label for="year" class="block font-semibold mb-1">Year</label>
                <input type="text" class="input input-bordered w-full" value="{{ $budget->year }}" disabled>
            </div>
            <button type="submit" class="btn btn-primary">Update Limit</button>
            <a href="{{ route('budgets.index') }}" class="ml-4 btn btn-ghost">Cancel</a>
        </form>
    </div>
@endsection
