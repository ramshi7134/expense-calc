@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">{{ $emi->name }}</h1>
                <p class="mb-0 text-muted">EMI Plan Details</p>
            </div>
            <a href="{{ route('emis.index') }}" class="btn btn-outline-secondary">Back to All Plans</a>
        </div>

        <!-- Plan Summary -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Plan Summary</h6>
            </div>
            <div class="card-body">
                @php
                    $totalPaid = $emi->installments->where('status', 'paid')->sum('amount');
                    $totalPending = $emi->total_amount - $totalPaid;
                    $percentagePaid = $emi->total_amount > 0 ? ($totalPaid / $emi->total_amount) * 100 : 0;
                @endphp
                <div class="row">
                    <div class="col-md-3">
                        <strong>Total Amount:</strong><br> AED {{ number_format($emi->total_amount, 2) }}
                    </div>
                    <div class="col-md-3">
                        <strong>Duration:</strong><br> {{ $emi->months }} months
                    </div>
                    <div class="col-md-3">
                        <strong>Paid:</strong><br> <span class="text-success">AED {{ number_format($totalPaid, 2) }}</span>
                    </div>
                    <div class="col-md-3">
                        <strong>Pending:</strong><br> <span class="text-danger">AED
                            {{ number_format($totalPending, 2) }}</span>
                    </div>
                </div>
                <div class="mt-3">
                    <h4 class="small font-weight-bold">Completion <span
                            class="float-end">{{ round($percentagePaid) }}%</span></h4>
                    <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentagePaid }}%"
                            aria-valuenow="{{ $percentagePaid }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Installment Schedule -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Installment Schedule</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Due Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Paid On</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($emi->installments->sortBy('due_date') as $installment)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $installment->due_date->format('d M, Y') }}</td>
                                    <td>AED {{ number_format($installment->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $installment->status == 'paid' ? 'success' : ($installment->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($installment->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $installment->paid_date ? $installment->paid_date->format('d M, Y') : 'N/A' }}
                                    </td>
                                    <td>
                                        @if ($installment->status == 'pending')
                                            <form action="{{ route('emis.pay', $installment) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to mark this installment as paid?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">Pay Now</button>
                                            </form>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Paid</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
