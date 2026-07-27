@extends('layouts.app')

@section('content')

<div class="container-fluid">

<div class="card shadow border-0">

    <div class="card-header bg-primary text-white d-flex justify-content-between">

        <h4 class="mb-0">

            Sales Return Details

        </h4>

        <a href="{{ route('sales-returns.index') }}"
            class="btn btn-light">

            Back

        </a>

    </div>

    <div class="card-body"></div>

    <div class="row mb-4">

    <div class="col-md-3">

        <strong>Return Number</strong><br>

        {{ $salesReturn->return_number }}

    </div>

    <div class="col-md-3">

        <strong>Receipt Number</strong><br>

        {{ $salesReturn->sale->receipt_number }}

    </div>

    <div class="col-md-3">

        <strong>Customer</strong><br>

        {{ $salesReturn->customer->name }}

    </div>

    <div class="col-md-3">

        <strong>Return Date</strong><br>

        {{ \Carbon\Carbon::parse($salesReturn->return_date)->format('d M Y') }}

    </div>

</div>
<div class="table-responsive">

    <table class="table table-bordered table-hover align-middle">

        <thead class="table-primary">

            <tr>

                <th>#</th>

                <th>Medicine</th>

                <th class="text-end">Selling Price</th>

                <th class="text-center">Quantity</th>

                <th class="text-end">Subtotal</th>

            </tr>

        </thead>

        <tbody>

            @foreach($salesReturn->items as $item)

                <tr>

                    <td>

                        {{ $loop->iteration }}

                    </td>

                    <td>

                        {{ $item->medicine->name }}

                    </td>

                    <td class="text-end">

                        ₦{{ number_format($item->selling_price, 2) }}

                    </td>

                    <td class="text-center">

                        {{ number_format($item->quantity) }}

                    </td>

                    <td class="text-end">

                        ₦{{ number_format($item->subtotal, 2) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

        <tfoot>

            <tr>

                <th colspan="4" class="text-end">

                    Total Amount

                </th>

                <th class="text-end text-danger">

                    ₦{{ number_format($salesReturn->total_amount, 2) }}

                </th>

            </tr>

        </tfoot>

    </table>

</div>
    </div>

</div>

</div>

@endsection