@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">

                <i class="fas fa-cash-register text-success me-2"></i>

                Sales Report

            </h3>

            <p class="text-muted mb-0">

                View sales transactions and analytics.

            </p>

        </div>

    </div>

    

    {{-- Date Filter --}}
    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="mb-3">

                <a href="{{ route('reports.sales',['period'=>'today']) }}"
                    class="btn btn-primary btn-sm">

                    Today

                </a>

                <a href="{{ route('reports.sales',['period'=>'yesterday']) }}"
                    class="btn btn-secondary btn-sm">

                    Yesterday

                </a>

                <a href="{{ route('reports.sales',['period'=>'week']) }}"
                    class="btn btn-success btn-sm">

                    This Week

                </a>

                <a href="{{ route('reports.sales',['period'=>'month']) }}"
                    class="btn btn-warning btn-sm">

                    This Month

                </a>

                <a href="{{ route('reports.sales',['period'=>'year']) }}"
                    class="btn btn-dark btn-sm">

                    This Year

                </a>

            </div>

            <form method="GET" action="{{ route('reports.sales') }}">

                <div class="row">

                    <div class="col-md-5">

                        <label class="form-label">

                            From

                        </label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ $from }}">

                    </div>

                    <div class="col-md-5">

                        <label class="form-label">

                            To

                        </label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ $to }}">

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary w-100">

                            <i class="fas fa-search me-1"></i>

                            Generate

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Summary Cards --}}
    <div class="row mb-4">

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <i class="fas fa-coins fa-2x text-success mb-2"></i>

                    <h6>Total Sales</h6>

                    <h4 class="text-success">

                        ₦{{ number_format($totalSales,2) }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <i class="fas fa-money-bill-wave fa-2x text-primary mb-2"></i>

                    <h6>Total Paid</h6>

                    <h4 class="text-primary">

                        ₦{{ number_format($totalPaid,2) }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <i class="fas fa-exclamation-circle fa-2x text-danger mb-2"></i>

                    <h6>Balance Due</h6>

                    <h4 class="text-danger">

                        ₦{{ number_format($totalBalance,2) }}

                    </h4>

                </div>

            </div>

        </div>

        <div class="col-lg-3 col-md-6 mb-3">

            <div class="card shadow border-0 text-center">

                <div class="card-body">

                    <i class="fas fa-receipt fa-2x text-dark mb-2"></i>

                    <h6>Transactions</h6>

                    <h4>

                        {{ $transactions }}

                    </h4>

                </div>

            </div>

        </div>

    </div>

    {{-- Print --}}
    <div class="d-flex justify-content-end mb-3">

        <button onclick="window.print()"
                class="btn btn-outline-primary">

            <i class="fas fa-print me-1"></i>

            Print Report

        </button>

        <a href="{{ route('reports.sales.pdf') }}" class="btn btn-danger">

            <i class="fas fa-file-pdf"></i>

            PDF

        </a>

        <a href="#" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

    </div>

    {{-- Payment Summary --}}
    <div class="card shadow mb-4">

        <div class="card-header bg-primary text-white">

            <i class="fas fa-credit-card me-2"></i>

            Sales by Payment Method

        </div>

        <div class="card-body">

            <div class="row">

                @forelse($paymentMethods as $method)

                    <div class="col-md-4 mb-3">

                        <div class="border rounded text-center p-4 h-100">

                            <i class="fas fa-wallet fa-2x text-primary mb-3"></i>

                            <h6 class="text-muted">

                                {{ ucfirst($method->payment_method) }}

                            </h6>

                            <h4 class="text-success">

                                ₦{{ number_format($method->total,2) }}

                            </h4>

                        </div>

                    </div>

                @empty

                    <div class="col-12 text-center text-muted">

                        No payment records found.

                    </div>

                @endforelse

            </div>

        </div>

    </div>

    <div class="card shadow mb-4">

    <div class="card-header bg-success text-white">

        <i class="fas fa-user-tie me-2"></i>

        Sales by Cashier

    </div>

    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>Cashier</th>

                    <th class="text-center">Transactions</th>

                    <th class="text-end">Sales</th>

                </tr>

            </thead>

            <tbody>

                @forelse($cashierSales as $cashier)

                    <tr>

                        <td>

                            {{ optional($cashier->user)->name }}

                        </td>

                        <td class="text-center">

                            <span class="badge bg-primary">

                                {{ $cashier->transactions }}

                            </span>

                        </td>

                        <td class="text-end fw-bold text-success">

                            ₦{{ number_format($cashier->total_sales,2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="text-center">

                            No cashier records found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="card shadow mb-4">

    <div class="card-header bg-warning">

        <i class="fas fa-pills me-2"></i>

        Top 10 Selling Medicines

    </div>

    <div class="card-body table-responsive">

        <table class="table table-hover align-middle">

            <thead>

                <tr>

                    <th>#</th>

                    <th>Medicine</th>

                    <th class="text-center">Quantity Sold</th>

                    <th class="text-end">Revenue</th>

                </tr>

            </thead>

            <tbody>

                @forelse($topMedicines as $medicine)

                    <tr>

                        <td>

                            @if($loop->iteration == 1)

                                🥇

                            @elseif($loop->iteration == 2)

                                🥈

                            @elseif($loop->iteration == 3)

                                🥉

                            @else

                                {{ $loop->iteration }}

                            @endif

                        </td>

                        <td>

                            {{ optional($medicine->medicine)->name }}

                        </td>

                        <td class="text-center">

                            <span class="badge bg-success">

                                {{ $medicine->quantity_sold }}

                            </span>

                        </td>

                        <td class="text-end fw-bold text-primary">

                            ₦{{ number_format($medicine->revenue,2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4" class="text-center">

                            No medicine sales found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>
<div class="card shadow mb-4">

    <div class="card-header bg-info text-white">

        <i class="fas fa-calendar-day me-2"></i>

        Daily Sales Summary

    </div>

    <div class="card-body table-responsive">

        <table class="table table-striped table-hover">

            <thead>

                <tr>

                    <th>Date</th>

                    <th class="text-center">Transactions</th>

                    <th class="text-end">Sales</th>

                </tr>

            </thead>

            <tbody>

                @forelse($dailySales as $day)

                    <tr>

                        <td>

                            {{ \Carbon\Carbon::parse($day->sale_day)->format('d M Y') }}

                        </td>

                        <td class="text-center">

                            <span class="badge bg-primary">

                                {{ $day->transactions }}

                            </span>

                        </td>

                        <td class="text-end fw-bold text-success">

                            ₦{{ number_format($day->total_sales, 2) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="text-center">

                            No sales found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="row mb-4">

    <div class="col-lg-8">

        <div class="card shadow">

            <div class="card-header">

                <i class="fas fa-chart-line me-2"></i>

                Daily Sales Trend

            </div>

            <div class="card-body">

                <canvas id="dailySalesChart"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow">

            <div class="card-header">

                <i class="fas fa-chart-pie me-2"></i>

                Payment Methods

            </div>

            <div class="card-body">

                <canvas id="paymentChart"></canvas>

            </div>

        </div>

    </div>

</div>

    {{-- Sales Table --}}
    <div class="card shadow">

        <div class="card-header">

            <i class="fas fa-list me-2"></i>

            Sales List

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">

                    <tr>

                        <th>Receipt</th>

                        <th>Date</th>

                        <th>Customer</th>

                        <th>Cashier</th>

                        <th>Payment</th>

                        <th>Total</th>

                        <th>Paid</th>

                        <th>Balance</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($sales as $sale)

                        <tr>

                            <td>{{ $sale->receipt_number }}</td>

                            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y h:i A') }}</td>

                            <td>{{ optional($sale->customer)->name ?? 'Walk-in' }}</td>

                            <td>{{ optional($sale->user)->name }}</td>

                            <td>

                                <span class="badge bg-info">

                                    {{ ucfirst($sale->payment_method) }}

                                </span>

                            </td>

                            <td>

                                ₦{{ number_format($sale->total_amount,2) }}

                            </td>

                            <td>

                                ₦{{ number_format($sale->amount_paid,2) }}

                            </td>

                            <td>

                                ₦{{ number_format($sale->balance,2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="8" class="text-center py-4">

                                No sales found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $sales->links() }}

        </div>

    </div>

</div>
@section('scripts')

<script>

const dailyLabels = @json(
    $dailySales->pluck('sale_day')
);

const dailyTotals = @json(
    $dailySales->pluck('total_sales')
);

new Chart(
    document.getElementById('dailySalesChart'),
    {
        type:'line',

        data:{

            labels:dailyLabels,

            datasets:[{

                label:'Sales',

                data:dailyTotals,

                borderWidth:3,

                tension:.4,

                fill:true

            }]

        }

    }
);

const paymentLabels = @json(
    $paymentMethods->pluck('payment_method')
);

const paymentTotals = @json(
    $paymentMethods->pluck('total')
);

new Chart(
    document.getElementById('paymentChart'),
    {
        type:'pie',

        data:{

            labels:paymentLabels,

            datasets:[{

                data:paymentTotals

            }]

        }

    }
);

</script>

@endsection

@endsection