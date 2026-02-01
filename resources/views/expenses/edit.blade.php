@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h3 mb-3">Edit Expense</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('expenses.update', $expense) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="amount" class="form-label">Amount ({{ auth()->user()->currency }})</label>
                                    <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                                        value="{{ $expense->amount }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="payment_type_id" class="form-label">Payment Type</label>
                                    <select name="payment_type_id" id="payment_type_id" class="form-select">
                                        <option value="">Select a payment type</option>
                                        @foreach ($paymentTypes as $type)
                                            <option value="{{ $type->id }}" @selected($expense->payment_type_id == $type->id)>
                                                {{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select name="category_id" id="category_id" class="form-select" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" @selected($expense->category_id == $category->id)>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control"
                                    value="{{ $expense->date->format('Y-m-d') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="note" class="form-label">Note (optional)</label>
                                <textarea name="note" id="note" class="form-control" rows="3">{{ $expense->note }}</textarea>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('expenses.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Expense</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
