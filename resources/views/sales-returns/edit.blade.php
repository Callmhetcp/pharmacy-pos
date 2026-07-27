@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow border-0">

        <div class="card-header bg-warning text-dark d-flex justify-content-between">

            <h4 class="mb-0">

                Edit Sales Return

            </h4>

            <a href="{{ route('sales-returns.index') }}"
                class="btn btn-dark">

                Back

            </a>

        </div>

        <div class="card-body">

            <form action="{{ route('sales-returns.update', $salesReturn) }}"
                method="POST">

                @csrf

                @method('PUT')

                <div class="row">

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Return Number

        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $salesReturn->return_number }}"
            readonly>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Receipt Number

        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $salesReturn->sale->receipt_number }}"
            readonly>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Customer

        </label>

        <input
            type="text"
            class="form-control"
            value="{{ $salesReturn->customer->name }}"
            readonly>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Return Date

        </label>

        <input
            type="date"
            name="return_date"
            value="{{ $salesReturn->return_date }}"
            class="form-control">

    </div>

</div>

<div class="mb-3">

    <label class="form-label">

        Reason

    </label>

    <input
        type="text"
        name="reason"
        value="{{ $salesReturn->reason }}"
        class="form-control">

</div>
<div class="table-responsive mt-4">

    <table class="table table-bordered align-middle">

        <thead class="table-warning">

            <tr>

                <th>Medicine</th>

                <th width="140">Selling Price</th>

                <th width="140">Return Quantity</th>

                <th width="160">Subtotal</th>

            </tr>

        </thead>

        <tbody>

            @foreach($salesReturn->items as $index => $item)

                <tr>

                    <td>

                        {{ $item->medicine->name }}

                        <input
                            type="hidden"
                            name="items[{{ $index }}][medicine_id]"
                            value="{{ $item->medicine_id }}">

                    </td>

                    <td>

                        ₦{{ number_format($item->selling_price, 2) }}

                        <input
                            type="hidden"
                            name="items[{{ $index }}][selling_price]"
                            value="{{ $item->selling_price }}">

                    </td>

                    <td>

                        <input
                            type="number"
                            class="form-control quantity"
                            min="0"
                            value="{{ $item->quantity }}"
                            data-price="{{ $item->selling_price }}"
                            name="items[{{ $index }}][quantity]">

                    </td>

                    <td class="subtotal">

                        ₦{{ number_format($item->subtotal, 2) }}

                    </td>

                </tr>

            @endforeach

        </tbody>

        <tfoot>

            <tr>

                <th colspan="3" class="text-end">

                    Total Amount

                </th>

                <th>

                    <input
                        type="text"
                        id="grandTotal"
                        class="form-control fw-bold text-danger"
                        value="₦{{ number_format($salesReturn->total_amount,2) }}"
                        readonly>

                </th>

            </tr>

        </tfoot>

    </table>

</div>
<div class="mt-4">

    <button
        type="submit"
        class="btn btn-primary">

        <i class="fas fa-save me-1"></i>

        Update Sales Return

    </button>

    <a
        href="{{ route('sales-returns.index') }}"
        class="btn btn-secondary">

        Cancel

    </a>

</div>
<script>

document.querySelectorAll('.quantity').forEach(function(input){

    input.addEventListener('input', function(){

        let qty = parseFloat(this.value) || 0;

        let price = parseFloat(this.dataset.price) || 0;

        let subtotal = qty * price;

        this.closest('tr').querySelector('.subtotal').innerHTML =
            '₦' + subtotal.toFixed(2);

        updateGrandTotal();

    });

});

function updateGrandTotal(){

    let total = 0;

    document.querySelectorAll('.subtotal').forEach(function(cell){

        total += parseFloat(
            cell.innerHTML
                .replace('₦','')
                .replace(/,/g,'')
        ) || 0;

    });

    document.getElementById('grandTotal').value =
        '₦' + total.toFixed(2);

}

</script>
@endsection
