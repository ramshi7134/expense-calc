@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Expense</h1>
        <form action="{{ route('expenses.store') }}" method="POST" class="max-w-lg bg-white p-6 rounded shadow">
            @csrf
            <div class="mb-4">
                <label for="amount" class="block font-semibold mb-1">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" class="w-full border rounded px-3 py-2"
                    required>
            </div>
            <div class="mb-4">
                <label for="amount" class="block font-semibold mb-1">Amount (AED)</label>
                <input type="number" step="0.01" name="amount" id="amount" class="input input-bordered w-full"
                    required>
            </div>
            <div class="mb-4">
                <label for="category_id" class="block font-semibold mb-1">Category</label>
                <select name="category_id" id="category_id" class="w-full border rounded px-3 py-2" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="date" class="block font-semibold mb-1">Date</label>
                <input type="date" name="date" id="date" class="w-full border rounded px-3 py-2"
                    value="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-4">
                <label for="note" class="block font-semibold mb-1">Note (optional)</label>
                <input type="text" name="note" id="note" class="w-full border rounded px-3 py-2">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Add Expense</button>
            <a href="{{ route('expenses.index') }}" class="ml-4 text-gray-600">Cancel</a>
        </form>
    </div>
@endsection
