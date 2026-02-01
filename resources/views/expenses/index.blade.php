@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Your Expenses</h1>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Expense
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Date</th>
                                <th>Amount</th>
                                <th>Category</th>
                                <th>Payment Type</th>
                                <th>Note</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->date->format('d M, Y') }}</td>
                                    <td>{{ format_currency($expense->amount) }}</td>
                                    <td>{{ $expense->category->name }}</td>
                                    <td>{{ $expense->paymentType ? $expense->paymentType->name : '-' }}</td>
                                    <td>{{ Str::limit($expense->note, 40) }}</td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this expense?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No expenses recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
