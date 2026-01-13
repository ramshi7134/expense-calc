@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h4 mb-3">Review Receipt</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3">
                    <img src="{{ asset('storage/' . $receipt->image_path) }}" class="img-fluid" alt="receipt">
                </div>
                <div class="card p-3">
                    <h5>OCR Raw Text</h5>
                    <pre style="white-space:pre-wrap">{{ $receipt->ocr_text ?? 'Processing...' }}</pre>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    @if ($receipt->status === 'processing')
                        <div class="alert alert-info">OCR is processing. Please wait or refresh this page in a few seconds.
                        </div>
                    @elseif($receipt->status === 'failed')
                        <div class="alert alert-warning">OCR failed. You can still enter data manually below.</div>
                    @endif

                    <form action="{{ route('scan.confirm', $receipt->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input name="amount" type="number" step="0.01" class="form-control"
                                value="{{ old('amount', $receipt->extracted_amount) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input name="date" type="date" class="form-control"
                                value="{{ old('date', optional($receipt->extracted_date)->format('Y-m-d')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">-- Select --</option>
                                @foreach ($categories as $c)
                                    <option {{ old('category', $receipt->extracted_category) == $c ? 'selected' : '' }}>
                                        {{ $c }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Merchant</label>
                            <input name="merchant" class="form-control" value="{{ old('merchant', $receipt->merchant) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Note</label>
                            <textarea name="note" class="form-control">{{ old('note') }}</textarea>
                        </div>

                        <button class="btn btn-success">Save Expense</button>
                        <a href="{{ route('scan.create') }}" class="btn btn-secondary">Scan Another</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
