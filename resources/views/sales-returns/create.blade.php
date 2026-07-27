@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-undo-alt me-2"></i>

                   New Sales Return

                </h4>

                <small class="opacity-75">

                   Return medicines sold to customers

                </small>

            </div>

            <a href="{{ route('sales-returns.index') }}"
                class="btn btn-light">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

        <div class="card-body">

<form action="{{ route('sales-returns.store') }}"
      method="POST">

    @csrf

    <div class="row">

        <div class="col-md-3 mb-3">

            <label class="form-label">

                Return Number

            </label>

            <input
                type="text"
                class="form-control"
                value="{{ $returnNumber }}"
                readonly>

        </div>

        <div class="col-md-3 mb-3">

            <label class="form-label">

                Sale

            </label>

            <select
                name="sale_id"
                id="sale"
                class="form-select"
                required>

                <option value="">

                    Select Sale

                </option>

                @foreach($sales as $sale)

                    <option value="{{ $sale->id }}">

                        {{ $sale->receipt_number }}

                    </option>

                @endforeach

            </select>

        </div>

        <div class="col-md-3 mb-3">

            <label class="form-label">

                Customer

            </label>

            <input
                type="text"
                id="customer"
                class="form-control"
                readonly>

            <input
                type="hidden"
                name="customer_id"
                id="customer_id">

        </div>

        <div class="col-md-3 mb-3">

            <label class="form-label">

                Return Date

            </label>

            <input
                type="date"
                name="return_date"
                class="form-control"
                value="{{ date('Y-m-d') }}"
                required>

        </div>

    </div>

               <div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Reason

        </label>

        <input
            type="text"
            name="reason"
            class="form-control"
            placeholder="Reason for return"
            required>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Status

        </label>

        <input
            type="text"
            class="form-control"
            value="Completed"
            readonly>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Total Amount

        </label>

        <input
            type="text"
            id="grandTotal"
            class="form-control fw-bold text-danger"
            value="₦0.00"
            readonly>

    </div>

</div>

               <div class="table-responsive mt-3">

                <table class="table table-bordered align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>Medicine</th>

                            <th width="120">Sold Qty</th>

                            <th width="120">Current Stock</th>

                            <th width="120">Selling Price</th>

                            <th width="120">Return Qty</th>

                            <th width="150">Subtotal</th>

                        </tr>

                    </thead>

                    <tbody id="saleItems">

                        <tr>

                            <td colspan="6" class="text-center py-4">

                                Select a sale to load medicines.

                            </td>

                        </tr>

                    </tbody>

                </table>

</div>

</div>
<div class="mt-4">

    <button
        type="submit"
        class="btn btn-primary">

        <i class="fas fa-save me-1"></i>

        Save Sales Return

    </button>

    <a href="{{ route('sales-returns.index') }}"
        class="btn btn-secondary">

        Cancel

    </a>

</div>

</form>

        </div>

    </div>

</div>

<script>

document.getElementById('sale').addEventListener('change', function () {

    let saleId = this.value;

    if (!saleId) {

        document.getElementById('saleItems').innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-4">
                    Select a sale to load medicines.
                </td>
            </tr>
        `;

        document.getElementById('customer').value = '';
        document.getElementById('customer_id').value = '';
        document.getElementById('grandTotal').value = '₦0.00';

        return;
    }

    fetch('/sales-returns/sale/' + saleId)

        .then(response => response.json())

        .then(data => {

            console.log(data);

            document.getElementById('customer').value = data.customer.name;
            document.getElementById('customer_id').value = data.customer.id;

            let html = '';

            data.items.forEach((item, index) => {

                html += `
                    <tr>

                        <td>
                            ${item.medicine.name}

                            <input
                                type="hidden"
                                name="items[${index}][medicine_id]"
                                value="${item.medicine.id}">
                        </td>

                        <td>
                            ${item.quantity}
                        </td>

                        <td>
                            ${item.medicine.quantity}
                        </td>

                        <td>

                            ₦${parseFloat(item.unit_price).toFixed(2)}

                            <input
                                type="hidden"
                                name="items[${index}][unit_price]"
                                value="${item.unit_price}">

                        </td>

                        <td>

                            <input
                                type="number"
                                class="form-control quantity"
                                min="0"
                                max="${item.quantity}"
                                value="0"
                                data-price="${item.unit_price}"
                                name="items[${index}][quantity]">

                        </td>

                        <td class="subtotal">

                            ₦0.00

                        </td>

                    </tr>
                `;

            });

            document.getElementById('saleItems').innerHTML = html;

            bindQuantityEvents();

        })

        .catch(error => {

            console.error(error);

            alert('Unable to load sale items.');

        });

});

function bindQuantityEvents() {

    document.querySelectorAll('.quantity').forEach(function (input) {

        input.addEventListener('input', function () {

            let qty = parseFloat(this.value) || 0;

            let price = parseFloat(this.dataset.price) || 0;

            let subtotal = qty * price;

            this.closest('tr').querySelector('.subtotal').innerHTML =
                '₦' + subtotal.toFixed(2);

            updateGrandTotal();

        });

    });

}

function updateGrandTotal() {

    let total = 0;

    document.querySelectorAll('.subtotal').forEach(function (cell) {

        total += parseFloat(
            cell.innerHTML
                .replace('₦', '')
                .replace(/,/g, '')
        ) || 0;

    });

    document.getElementById('grandTotal').value =
        '₦' + total.toFixed(2);

}

</script>

@endsection