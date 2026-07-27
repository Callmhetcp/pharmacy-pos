@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-file-invoice text-primary me-2"></i>
                Purchase Details
            </h2>

            <small class="text-muted">
                View complete purchase information
            </small>
        </div>

        <div>

            <a href="{{ route('purchase.index') }}"
               class="btn btn-outline-secondary">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

            <a href="{{ route('purchase.receipt', $purchase->id) }}"
               class="btn btn-primary">

                <i class="fas fa-print me-1"></i>

                Print Receipt

            </a>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">

                Purchase Information

            </h5>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted d-block">

                            Purchase Number

                        </small>

                        <h5 class="fw-bold text-primary mt-2">

                            {{ $purchase->purchase_number }}

                        </h5>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted d-block">

                            Supplier

                        </small>

                        <h5 class="fw-semibold mt-2">

                            {{ $purchase->supplier->company }}

                        </h5>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted d-block">

                            Invoice Number

                        </small>

                        <h5 class="fw-semibold mt-2">

                            {{ $purchase->invoice_number }}

                        </h5>

                    </div>

                </div>

                <div class="col-lg-3 col-md-6">

                    <div class="border rounded-3 p-3 h-100">

                        <small class="text-muted d-block">

                            Purchase Date

                        </small>

                        <h5 class="fw-semibold mt-2">

                            {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}

                        </h5>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="border rounded-3 p-3">

                        <small class="text-muted d-block">

                            Recorded By

                        </small>

                        <h5 class="fw-semibold mt-2">

                            <i class="fas fa-user-circle text-primary me-2"></i>

                            {{ $purchase->user->name }}

                        </h5>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="border rounded-3 p-3 bg-primary text-white">

                        <small>

                            Grand Total

                        </small>

                        <h2 class="fw-bold mt-2 mb-0">

                            ₦{{ number_format($purchase->grand_total,2) }}

                        </h2>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm mt-4">

        <div class="card-header bg-white border-0 py-3">

            <h5 class="mb-0 fw-bold">

                Purchased Medicines

            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Medicine</th>
                            <th>Batch No</th>
                            <th>Expiry Date</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-end">Cost Price</th>
                            <th class="text-end">Selling Price</th>
                            <th class="text-end">Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($purchase->purchaseItems as $index => $item)

                        <tr>

                            <td>

                                {{ $index + 1 }}

                            </td>

                            <td>

                                <strong>

                                    {{ $item->medicine->name }}

                                </strong>

                            </td>

                            <td>

                                <span class="badge bg-secondary">

                                    {{ $item->batch_number }}

                                </span>

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-success">

                                    {{ $item->quantity }}

                                </span>

                            </td>

                            <td class="text-end">

                                ₦{{ number_format($item->cost_price,2) }}

                            </td>

                            <td class="text-end">

                                ₦{{ number_format($item->selling_price,2) }}

                            </td>

                            <td class="text-end fw-bold">

                                ₦{{ number_format($item->subtotal,2) }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot class="table-light">

                        <tr>

                            <th colspan="7" class="text-end">

                                Grand Total

                            </th>

                            <th class="text-end text-primary fs-5">

                                ₦{{ number_format($purchase->grand_total,2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection

