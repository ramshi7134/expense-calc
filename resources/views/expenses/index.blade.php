@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl font-bold">Expenses</h1>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">Add Expense</a>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full rounded-xl shadow-xl">
                <thead>
                    <tr>
                        <th>Amount</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Note</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ number_format($expense->amount, 2) }} AED</td>
                            <td>{{ $expense->category->name }}</td>
                            <td>{{ $expense->date }}</td>
                            <td>{{ $expense->note }}</td>
                            <td class="flex gap-2">
                                <a href="{{ route('expenses.edit', $expense) }}"
                                    class="btn btn-xs btn-outline btn-info">Edit</a>
                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                    onsubmit="return confirm('Delete this expense?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline btn-error">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No expenses found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
