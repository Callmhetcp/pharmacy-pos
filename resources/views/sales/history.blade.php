@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h3 class="mb-0">
                <i class="fas fa-clock-rotate-left me-2"></i>
                Sales History
            </h3>

            <span class="badge bg-light text-primary fs-6">
                {{ $sales->total() }} Sales
            </span>

        </div>

        <div class="card-body">

            <!-- Search -->

            <form action="{{ route('sales.history') }}" method="GET" class="mb-4">

                <div class="input-group input-group-lg">

                    <span class="input-group-text bg-white">
                        <i class="fas fa-search text-primary"></i>
                    </span>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        class="form-control"
                        placeholder="Search receipt number or payment method..."
                        value="{{ request('search') }}">

                </div>

            </form>

            <!-- Table -->

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead style="background:linear-gradient(90deg,#0d6efd,#0b5ed7); color:white;">

                        <tr>

                            <th>Receipt No</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Change</th>
                            <th>Payment</th>
                            <th>Date</th>
                            <th width="120">Action</th>

                        </tr>

                    </thead>

                    <tbody id="salesTable">

                        @forelse($sales as $sale)

                        <tr>

                            <td>

                                <span class="fw-bold text-primary">
                                    {{ $sale->receipt_number }}
                                </span>

                            </td>

                            <td class="fw-semibold">
                                ₦{{ number_format($sale->total_amount,2) }}
                            </td>

                            <td class="text-success fw-semibold">
                                ₦{{ number_format($sale->amount_paid,2) }}
                            </td>

                            <td class="text-danger fw-semibold">
                                ₦{{ number_format($sale->balance,2) }}
                            </td>

                            <td>

                                @if($sale->payment_method == 'Cash')

                                    <span class="badge bg-success">
                                        <i class="fas fa-money-bill-wave me-1"></i>
                                        Cash
                                    </span>

                                @elseif($sale->payment_method == 'POS')

                                    <span class="badge bg-primary">
                                        <i class="fas fa-credit-card me-1"></i>
                                        POS
                                    </span>

                                @else

                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-building-columns me-1"></i>
                                        Transfer
                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}

                                <br>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($sale->sale_date)->format('h:i A') }}
                                </small>

                            </td>

                            <td>

                                <a href="{{ route('sales.show',$sale->id) }}"
                                   class="btn btn-outline-primary btn-sm">

                                    <i class="fas fa-eye me-1"></i>

                                    View

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="fas fa-file-invoice-dollar fa-4x text-muted mb-3"></i>

                                <h5 class="fw-bold">

                                    No Sales Found

                                </h5>

                                <p class="text-muted">

                                    No sales match your search.

                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="d-flex justify-content-center mt-4">

                {{ $sales->links() }}

            </div>

        </div>

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('search').addEventListener('keyup', function () {

        let search = this.value;

        fetch("{{ route('sales.history') }}?search=" + encodeURIComponent(search))

        .then(response => response.text())

        .then(data => {

            let parser = new DOMParser();

            let html = parser.parseFromString(data,'text/html');

            document.getElementById('salesTable').innerHTML =
                html.getElementById('salesTable').innerHTML;

        });

    });

});

</script>

@endsection