@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Your Payment Types</h1>
            <a href="{{ route('payment-types.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Payment Type
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Card Number</th>
                                <th>Statement Day</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentTypes as $paymentType)
                                <tr>
                                    <td>{{ $paymentType->name }}</td>
                                    <td><span
                                            class="badge bg-{{ $paymentType->type == 'credit' ? 'success' : 'info' }}">{{ ucfirst($paymentType->type) }}</span>
                                    </td>
                                    <td>{{ $paymentType->last_four_digits ? '**** ' . $paymentType->last_four_digits : '-' }}
                                    </td>
                                    <td>{{ $paymentType->statement_day ? 'Every month on the ' . $paymentType->statement_day : '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="{{ route('payment-types.edit', $paymentType) }}"
                                                class="btn btn-sm btn-outline-primary me-2">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('payment-types.destroy', $paymentType) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this payment type?')">
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
                                    <td colspan="5" class="text-center text-muted py-4">You haven't added any payment
                                        types yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
