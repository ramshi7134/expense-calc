@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Expense Categories</h1>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">Add Category</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full rounded-xl shadow-xl">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $category)
                        <tr>
                            <td>
                                <div>{{ $category->name }}</div>
                                @php
                                    $budget = $category
                                        ->budgets()
                                        ->where('month', now()->month)
                                        ->where('year', now()->year)
                                        ->first();
                                @endphp
                                <div class="text-xs text-gray-500">
                                    Monthly Limit: {{ $budget ? number_format($budget->limit, 2) . ' AED' : 'Not set' }}
                                </div>
                            </td>
                            <td class="flex gap-2">
                                <a href="{{ route('categories.edit', $category) }}"
                                    class="btn btn-xs btn-outline btn-info">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST"
                                    onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline btn-error">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
