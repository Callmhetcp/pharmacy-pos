@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">

        <i class="fas fa-cart-plus text-primary"></i>

        Purchase Report

    </h3>

    {{-- Filter Section --}}

    <div class="card shadow mb-4">

        <div class="card-body">

            <div class="mb-3">

                <a href="{{ route('reports.purchases',['period'=>'today']) }}"
                    class="btn btn-primary btn-sm">
                    Today
                </a>

                <a href="{{ route('reports.purchases',['period'=>'yesterday']) }}"
                    class="btn btn-secondary btn-sm">
                    Yesterday
                </a>

                <a href="{{ route('reports.purchases',['period'=>'week']) }}"
                    class="btn btn-success btn-sm">
                    This Week
                </a>

                <a href="{{ route('reports.purchases',['period'=>'month']) }}"
                    class="btn btn-warning btn-sm">
                    This Month
                </a>

                <a href="{{ route('reports.purchases',['period'=>'year']) }}"
                    class="btn btn-dark btn-sm">
                    This Year
                </a>

            </div>

            <form method="GET" action="{{ route('reports.purchases') }}">

                <div class="row">

                    <div class="col-md-5">

                        <label>From</label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ $from }}">

                    </div>

                    <div class="col-md-5">

                        <label>To</label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ $to }}">

                    </div>

                    <div class="col-md-2 d-flex align-items-end">

                        <button class="btn btn-primary w-100">

                            Generate

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Summary Cards --}}

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Total Purchases</h6>

                    <h3 class="text-primary">

                        {{ $totalPurchases }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Total Amount</h6>

                    <h3 class="text-success">

                        ₦{{ number_format($totalAmount,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Average Purchase</h6>

                    <h3 class="text-warning">

                        ₦{{ number_format($averagePurchase,2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>

    <div class="mb-3">

    <button onclick="window.print()" class="btn btn-dark">

        <i class="fas fa-print"></i>

        Print Report

    </button>
    <a href="{{ route('reports.purchases.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>


        <a href="#" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

</div>

    {{-- Supplier Summary --}}

    <div class="card shadow mb-4">

        <div class="card-header">

            <i class="fas fa-truck me-2"></i>

            Supplier Summary

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>Supplier</th>

                        <th>Total Purchases</th>

                        <th>Total Amount</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($supplierSummary as $supplier)

                        <tr>

                            <td>

                                {{ optional($supplier->supplier)->name }}

                            </td>

                            <td>

                                {{ $supplier->purchases }}

                            </td>

                            <td>

                                ₦{{ number_format($supplier->amount,2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="3" class="text-center">

                                No supplier data found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Purchase List --}}

    <div class="card shadow">

        <div class="card-header">

            Purchase History

        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover">

                <thead>

                    <tr>

                        <th>Purchase No.</th>

                        <th>Date</th>

                        <th>Supplier</th>

                        <th>Invoice</th>

                        <th>User</th>

                        <th>Total</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($purchases as $purchase)

                        <tr>

                            <td>{{ $purchase->purchase_number }}</td>

                            <td>{{ $purchase->purchase_date }}</td>

                            <td>{{ optional($purchase->supplier)->name }}</td>

                            <td>{{ $purchase->invoice_number ?? '-' }}</td>

                            <td>{{ optional($purchase->user)->name }}</td>

                            <td>

                                ₦{{ number_format($purchase->grand_total,2) }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">

                                No purchases found.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

            {{ $purchases->links() }}

        </div>

    </div>

</div>

@endsection