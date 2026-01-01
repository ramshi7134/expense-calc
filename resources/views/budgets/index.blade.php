@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Budgets</h1>
            <a href="{{ route('budgets.create') }}" class="btn btn-primary">Add New Budget</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($budgets as $year => $months)
            @foreach ($months as $month => $monthlyBudgets)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            {{ $year }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Limit</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyBudgets as $budget)
                                        <tr>
                                            <td>{{ $budget->category->name }}</td>
                                            <td>AED {{ number_format($budget->limit, 2) }}</td>
                                            <td>
                                                <a href="{{ route('budgets.edit', $budget) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('budgets.destroy', $budget) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to delete this budget?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        @empty
            <div class="card shadow text-center p-4">
                <p>You haven't set any budgets yet.</p>
                <a href="{{ route('budgets.create') }}" class="btn btn-primary">Set Your First Budget</a>
            </div>
        @endforelse
    </div>
@endsection
