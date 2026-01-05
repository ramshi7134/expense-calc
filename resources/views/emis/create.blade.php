@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="m-0 font-weight-bold text-primary">Create New EMI Plan</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('emis.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Plan Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="total_amount" class="form-label">Total Amount (AED)</label>
                                    <input type="number" class="form-control @error('total_amount') is-invalid @enderror"
                                        id="total_amount" name="total_amount" value="{{ old('total_amount') }}" required
                                        step="0.01">
                                    @error('total_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="months" class="form-label">Number of Months</label>
                                    <input type="number" class="form-control @error('months') is-invalid @enderror"
                                        id="months" name="months" value="{{ old('months') }}" required>
                                    @error('months')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_month" class="form-label">Start Month</label>
                                    <select class="form-select @error('start_month') is-invalid @enderror" id="start_month"
                                        name="start_month" required>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ old('start_month', date('m')) == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endfor
                                    </select>
                                    @error('start_month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="start_year" class="form-label">Start Year</label>
                                    <select class="form-select @error('start_year') is-invalid @enderror" id="start_year"
                                        name="start_year" required>
                                        @for ($y = date('Y') + 1; $y >= date('Y') - 5; $y--)
                                            <option value="{{ $y }}"
                                                {{ old('start_year', date('Y')) == $y ? 'selected' : '' }}>
                                                {{ $y }}</option>
                                        @endfor
                                    </select>
                                    @error('start_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="interest_rate" class="form-label">Interest Rate (%) <small
                                        class="text-muted">(Optional)</small></label>
                                <input type="number" class="form-control @error('interest_rate') is-invalid @enderror"
                                    id="interest_rate" name="interest_rate" value="{{ old('interest_rate', 0) }}"
                                    step="0.01">
                                @error('interest_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description <small
                                        class="text-muted">(Optional)</small></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('emis.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Create Plan & Generate Installments</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
