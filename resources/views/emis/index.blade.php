@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">EMI Plans</h1>
            <a href="{{ route('emis.create') }}" class="btn btn-primary">Add New EMI Plan</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @forelse ($emiPlans as $plan)
            @php
                $totalPaid = $plan->installments->where('status', 'paid')->sum('amount');
                $totalPending = $plan->total_amount - $totalPaid;
                $percentagePaid = $plan->total_amount > 0 ? ($totalPaid / $plan->total_amount) * 100 : 0;
            @endphp
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $plan->name }}</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end shadow animated--fade-in"
                            aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('emis.show', $plan) }}">View Details</a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('emis.destroy', $plan) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this entire EMI plan and its history?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">Delete Plan</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Total Amount:</strong> AED {{ number_format($plan->total_amount, 2) }}
                            </p>
                            <p class="mb-1"><strong>Duration:</strong> {{ $plan->months }} months</p>
                            <p class="mb-0"><strong>Start Date:</strong>
                                {{ date('F Y', mktime(0, 0, 0, $plan->start_month, 1, $plan->start_year)) }}</p>
                        </div>
                        <div class="col-md-4">
                            <p class="mb-1"><strong>Paid:</strong> <span class="text-success">AED
                                    {{ number_format($totalPaid, 2) }}</span></p>
                            <p class="mb-0"><strong>Pending:</strong> <span class="text-danger">AED
                                    {{ number_format($totalPending, 2) }}</span></p>
                        </div>
                        <div class="col-md-4">
                            <h4 class="small font-weight-bold">Completion <span
                                    class="float-end">{{ round($percentagePaid) }}%</span></h4>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $percentagePaid }}%" aria-valuenow="{{ $percentagePaid }}"
                                    aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow text-center p-5">
                <p class="lead">You haven't created any EMI plans yet.</p>
                <a href="{{ route('emis.create') }}" class="btn btn-primary mt-3">Create Your First EMI Plan</a>
            </div>
        @endforelse
    </div>
@endsection
