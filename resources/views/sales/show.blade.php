@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h3 class="mb-0">
                <i class="fas fa-file-invoice-dollar me-2"></i>
                Sale Details
            </h3>

            <a href="{{ route('sales.history') }}" class="btn btn-light">

                <i class="fas fa-arrow-left me-2"></i>

                Back to History

            </a>

        </div>

        <div class="card-body">

            <!-- Sale Information -->

            <div class="row g-4 mb-4">

                <div class="col-lg-3">

                    <div class="card border-0 bg-light h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Receipt Number
                            </small>

                            <h5 class="fw-bold text-primary mb-0">
                                {{ $sale->receipt_number }}
                            </h5>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card border-0 bg-light h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Customer
                            </small>

                            <h5 class="fw-bold mb-0">

                                <i class="fas fa-user text-primary me-2"></i>

                                {{ $sale->customer->name }}

                            </h5>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card border-0 bg-light h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Cashier
                            </small>

                            <h5 class="fw-bold mb-0">

                                <i class="fas fa-user-shield text-success me-2"></i>

                                {{ $sale->user->name }}

                            </h5>

                        </div>

                    </div>

                </div>

                <div class="col-lg-3">

                    <div class="card border-0 bg-light h-100">

                        <div class="card-body">

                            <small class="text-muted">
                                Date
                            </small>

                            <h5 class="fw-bold mb-0">

                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}

                            </h5>

                            <small class="text-muted">

                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('h:i A') }}

                            </small>

                        </div>

                    </div>

                </div>

            </div>

            <!-- Payment -->

            <div class="row mb-4">

                <div class="col-md-4">

                    <strong>Payment Method</strong><br>

                    @if($sale->payment_method == 'Cash')

                        <span class="badge bg-success fs-6">

                            <i class="fas fa-money-bill-wave me-1"></i>

                            Cash

                        </span>

                    @elseif($sale->payment_method == 'POS')

                        <span class="badge bg-primary fs-6">

                            <i class="fas fa-credit-card me-1"></i>

                            POS

                        </span>

                    @else

                        <span class="badge bg-warning text-dark fs-6">

                            <i class="fas fa-building-columns me-1"></i>

                            Transfer

                        </span>

                    @endif

                </div>

            </div>

            <!-- Medicines -->

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead style="background:linear-gradient(90deg,#0d6efd,#0b5ed7); color:white;">

                        <tr>

                            <th>Medicine</th>
                            <th width="120">Quantity</th>
                            <th width="180">Unit Price</th>
                            <th width="180">Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($sale->saleItems as $item)

                        <tr>

                            <td>

                                <strong>

                                    {{ $item->medicine->name }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-primary rounded-pill">

                                    {{ $item->quantity }}

                                </span>

                            </td>

                            <td>

                                ₦{{ number_format($item->unit_price,2) }}

                            </td>

                            <td class="fw-bold text-success">

                                ₦{{ number_format($item->subtotal,2) }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot class="table-light">

                        <tr>

                            <th colspan="3" class="text-end">

                                Total Amount

                            </th>

                            <th class="text-success">

                                ₦{{ number_format($sale->total_amount,2) }}

                            </th>

                        </tr>

                        <tr>

                            <th colspan="3" class="text-end">

                                Amount Paid

                            </th>

                            <th>

                                ₦{{ number_format($sale->amount_paid,2) }}

                            </th>

                        </tr>

                        <tr>

                            <th colspan="3" class="text-end">

                                Change

                            </th>

                            <th class="text-danger">

                                ₦{{ number_format($sale->balance,2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <!-- Buttons -->

            <div class="d-flex justify-content-end gap-2 mt-4">

                <a href="{{ route('sales.history') }}"
                   class="btn btn-outline-secondary">

                    <i class="fas fa-arrow-left me-2"></i>

                    Back

                </a>

                <a href="{{ route('sales.receipt', $sale->id) }}"
                   class="btn btn-success">

                    <i class="fas fa-print me-2"></i>

                    Print Receipt

                </a>

            </div>

        </div>

    </div>

</div>

@endsection