@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="h4 mb-3">Scan Expense</h1>

        <div class="card p-4">
            <form action="{{ route('scan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Upload Receipt Image</label>
                    <input accept="image/*" capture="environment" type="file" name="image" class="form-control" required>
                    <small class="form-text text-muted">You can take a photo or upload an existing image. Max 10MB.</small>
                </div>

                <button class="btn btn-primary">Scan Receipt</button>
            </form>
        </div>
    </div>
@endsection
