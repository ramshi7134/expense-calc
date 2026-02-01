@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h3 mb-3">Add New Payment Type</h1>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('payment-types.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Payment Type Name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="e.g., Personal Credit Card" required>
                            </div>
                            <div class="mb-3">
                                <label for="type" class="form-label">Type</label>
                                <select name="type" id="type" class="form-select">
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="statement_day" class="form-label">Statement Day of Month (optional)</label>
                                <input type="number" name="statement_day" id="statement_day" class="form-control"
                                    min="1" max="31" placeholder="e.g., 2 for the 2nd of every month">
                                <div class="form-text">For credit cards, enter the day your statement is generated.</div>
                            </div>
                            <div class="mb-3">
                                <label for="last_four_digits" class="form-label">Last 4 Digits of Card (optional)</label>
                                <input type="text" name="last_four_digits" id="last_four_digits" class="form-control"
                                    maxlength="4" placeholder="e.g., 1234">
                                <div class="form-text">For credit/debit cards, enter the last four digits of the card
                                    number.
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('payment-types.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Add Payment Type</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
