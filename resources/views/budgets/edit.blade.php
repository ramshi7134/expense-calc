@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow">
                    <div class="card-header">
                        <h5 class="m-0 font-weight-bold text-primary">Edit Budget</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('budgets.update', $budget) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">Select a Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $budget->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="limit" class="form-label">Limit</label>
                                <input type="number" class="form-control @error('limit') is-invalid @enderror"
                                    id="limit" name="limit" value="{{ old('limit', $budget->limit) }}" required
                                    step="0.01">
                                @error('limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="month" class="form-label">Month</label>
                                    <select class="form-select @error('month') is-invalid @enderror" id="month"
                                        name="month" required>
                                        @for ($m = 1; $m <= 12; $m++)
                                            <option value="{{ $m }}"
                                                {{ old('month', $budget->month) == $m ? 'selected' : '' }}>
                                                {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                                        @endfor
                                    </select>
                                    @error('month')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="year" class="form-label">Year</label>
                                    <select class="form-select @error('year') is-invalid @enderror" id="year"
                                        name="year" required>
                                        @for ($y = date('Y'); $y <= date('Y') + 5; $y++)
                                            <option value="{{ $y }}"
                                                {{ old('year', $budget->year) == $y ? 'selected' : '' }}>
                                                {{ $y }}</option>
                                        @endfor
                                    </select>
                                    @error('year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('budgets.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Update Budget</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
