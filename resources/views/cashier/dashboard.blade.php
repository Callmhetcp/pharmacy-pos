@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold">
                <i class="fas fa-cash-register text-primary"></i>
                Cashier Dashboard
            </h3>
            <small class="text-muted">
                Welcome back, {{ auth()->user()->name }}
            </small>
        </div>

        <a href="{{ route('sales.index') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Sale
        </a>
    </div>

    <div class="row">

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0">
                <div class="card-body">

                    <h6 class="text-muted">
                        Today's Sales
                    </h6>

                    <h2 class="fw-bold text-success">
                        ₦{{ number_format($todaySales,2) }}
                    </h2>

                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow border-0">
                <div class="card-body">

                    <h6 class="text-muted">
                        Transactions Today
                    </h6>

                    <h2 class="fw-bold">
                        {{ $todayTransactions }}
                    </h2>

                </div>
            </div>
        </div>

    </div>

    <div class="card shadow">

        <div class="card-header">
            Recent Sales
        </div>

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-light">

                <tr>

                    <th>Receipt</th>

                    <th>Customer</th>

                    <th>Total</th>

                    <th>Date</th>

                </tr>

                </thead>

                <tbody>

                @forelse($recentSales as $sale)

                    <tr>

                        <td>{{ $sale->receipt_number }}</td>

                        <td>{{ $sale->customer->name ?? 'Walk-in Customer' }}</td>

                        <td>₦{{ number_format($sale->total_amount,2) }}</td>

                        <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center py-4">
                            No sales found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection