@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Category</h1>
        <form action="{{ route('categories.update', $category) }}" method="POST" class="card bg-base-100 shadow-xl p-6">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full"
                    value="{{ $category->name }}" required>
            </div>
            <div class="mb-4">
                <label for="limit" class="block font-semibold mb-1">Monthly Limit (AED)</label>
                <input type="number" step="0.01" name="limit" id="limit" class="input input-bordered w-full"
                    value="{{ $category->budgets()->whereMonth('month', now()->month)->whereYear('year', now()->year)->first()?->limit ?? '' }}"
                    placeholder="Enter monthly limit">
            </div>
            <button type="submit" class="btn btn-primary">Update Category</button>
            <a href="{{ route('categories.index') }}" class="ml-4 btn btn-ghost">Cancel</a>
        </form>
    </div>
@endsection
