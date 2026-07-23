@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h3 class="mb-0">
                <i class="fas fa-cash-register"></i>
                New Sale
            </h3>

            <a href="{{ route('sales.history') }}" class="btn btn-light">
                <i class="fas fa-clock-rotate-left"></i>
                Sales History
            </a>

        </div>

        <div class="card-body">

            {{-- ================= CUSTOMER SECTION ================= --}}

            <div class="row mb-4">

                <div class="col-md-8">

                    <label class="form-label fw-bold">
                        Customer
                    </label>

                    <select
                        id="customer_id"
                        class="form-control"
                        name="customer_id">

                        <option value="">
                            Select Customer
                        </option>

                        @foreach($customers as $customer)

                        <option
                            value="{{ $customer->id }}"
                            {{ session('customer_id') == $customer->id ? 'selected' : '' }}>

                            {{ $customer->name }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="col-md-2">

                    <label class="form-label">&nbsp;</label>

                    {{-- <a href="{{ route('customers.create') }}"
                        class="btn btn-success w-100">

                        <i class="fas fa-user-plus"></i>

                        New Customer

                    </a> --}}

                </div>

                <div class="col-md-2">

                    <label class="form-label">&nbsp;</label>

                    <div class="form-check mt-2">
                        <input type="checkbox"
                            class="form-check-input"
                            id="walkin">

                        <label class="form-check-label" for="walkin">
                            Walk-in Customer
                        </label>
                    </div>
                </div>

            </div>

            <hr>

            {{-- ================= ADD MEDICINE ================= --}}

            <form
                action="{{ route('sales.addToCart') }}"
                method="POST" id="addToCartForm">

                @csrf

                <input
                    type="hidden"
                    id="selectedCustomer"
                    name="customer_id">

                <div class="row">

                    <div class="col-md-6">

                        <label class="form-label fw-bold">

                            Medicine

                        </label>

                        <select
                            class="form-control"
                            name="medicine_id"
                            required>

                            <option value="">

                                Select Medicine

                            </option>

                            @foreach($medicines as $medicine)

                            <option value="{{ $medicine->id }}">

                                {{ $medicine->name }}

                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-2">

                        <label class="form-label fw-bold">

                            Quantity

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            value="1"
                            min="1"
                            name="quantity">

                    </div>

                    <div class="col-md-4 d-flex align-items-end">

                        <button
                            class="btn btn-primary me-2"
                            type="submit">

                            <i class="fas fa-cart-plus"></i>

                            Add To Cart

                        </button>

                        <a
                            href="{{ route('sales.clearCart') }}"
                            class="btn btn-danger">

                            <i class="fas fa-trash"></i>

                            Clear Cart

                        </a>

                    </div>

                </div>

            </form>

            <hr>

            {{-- ================= CART ================= --}}

            <div class="table-responsive">

                <table class="table table-hover table-bordered align-middle">

                    <thead class="table-dark">

                    <tr>

                        <th>Medicine</th>

                        <th width="100">Qty</th>

                        <th width="140">

                            Unit Price

                        </th>

                        <th width="140">

                            Subtotal

                        </th>

                        <th width="90">

                            Action

                        </th>

                    </tr>

                    </thead>

                    <tbody id="cartTable"></tbody>
                    @if(count($cart) > 0)

    @foreach($cart as $item)

    <tr id="cart-row-{{ $item['id'] }}">

        <td>

            <strong>{{ $item['name'] }}</strong>

        </td>

        <td>

            {{ $item['quantity'] }}

        </td>

        <td>

            ₦{{ number_format($item['price'],2) }}

        </td>

        <td>

            ₦{{ number_format($item['subtotal'],2) }}

        </td>

        <td>

            <form
                action="{{ route('cart.remove',$item['id']) }}"
                method="POST">

                @csrf
                @method('DELETE')

                <button
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('Remove this medicine?')">

                    <i class="fas fa-trash"></i>

                </button>

            </form>

        </td>

    </tr>

    @endforeach

@else

<tr>

    <td colspan="5" class="text-center text-muted py-4">

        <i class="fas fa-cart-shopping fa-2x mb-2"></i>

        <br>

        No medicine has been added to the cart.

    </td>

</tr>

@endif

</tbody>

</table>

</div>

<hr>

<div class="row mt-4">

    <div class="col-lg-6">

        <div class="card border-0 shadow-sm">

            <div class="card-body">

                <h5 class="mb-3">

                    <i class="fas fa-receipt"></i>

                    Order Summary

                </h5>

                <table class="table table-borderless">

                    <tr>

                        <th>Total Items</th>

                        <td class="text-end">

                            {{ count($cart) }}

                        </td>

                    </tr>

                    <tr>

                        <th>Grand Total</th>

                        <td class="text-end">

                            <span
                                class="fw-bold text-success fs-4"
                                id="grandTotal">

                                ₦{{ number_format(collect($cart)->sum('subtotal'),2) }}

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

        </div>

    </div>

    <div class="col-lg-6">

        <div class="card border-0 shadow">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="fas fa-money-check-dollar"></i>

                    Payment

                </h5>

            </div>

            <div class="card-body">

                <form action="{{ route('sales.store') }}" method="POST">

                    @csrf

                    <input
                        type="hidden"
                        name="customer_id"
                        id="paymentCustomer">

                    <div class="mb-3">

                        <label class="form-label">

                            Payment Method

                        </label>

                        <select
                            class="form-control"
                            name="payment_method">

                            <option value="Cash">

                                Cash

                            </option>

                            <option value="POS">

                                POS

                            </option>

                            <option value="Transfer">

                                Bank Transfer

                            </option>

                        </select>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Amount Paid

                        </label>

                        <input
                            type="number"
                            class="form-control"
                            id="amount_paid"
                            name="amount_paid"
                            step="0.01"
                            min="0"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Change

                        </label>

                        <input
                            type="text"
                            class="form-control bg-light"
                            id="change"
                            name="balance"
                            readonly>

                    </div>

                    <button
                        id="completeSale"
                        class="btn btn-success btn-lg w-100"
                        disabled>

                        <i class="fas fa-check-circle"></i>

                        Complete Sale

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

</div>

</div>

</div>

@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ============================
    // Elements
    // ============================
   const customerSelect = document.getElementById('customer_id');
    const hiddenCustomer = document.getElementById('selectedCustomer');
    const paymentCustomer = document.getElementById('paymentCustomer');
    const walkin = document.getElementById('walkin');

    const walkInCustomerId = {{ $walkInCustomer->id }};

    const amountPaid = document.getElementById('amount_paid');
    const change = document.getElementById('change');
    const completeSale = document.getElementById('completeSale');

    // Grand total from PHP
    const grandTotal = {{ collect($cart)->sum('subtotal') }};

    // ============================
    // Keep customer in hidden inputs
    // ============================
 function updateCustomer() {

    if (walkin.checked) {

        hiddenCustomer.value = walkInCustomerId;
        paymentCustomer.value = walkInCustomerId;

    } else {

        hiddenCustomer.value = customerSelect.value;
        paymentCustomer.value = customerSelect.value;

    }

}

function toggleCustomer() {

    customerSelect.disabled = walkin.checked;

    if (walkin.checked) {
        customerSelect.value = "";
    }

    updateCustomer();

}

customerSelect.addEventListener('change', updateCustomer);
walkin.addEventListener('change', toggleCustomer);

toggleCustomer();

    // ============================
    // Calculate Change
    // ============================
    function calculateChange() {

        let paid = parseFloat(amountPaid.value);

        if (isNaN(paid)) {

            paid = 0;

        }

        let balance = paid - grandTotal;

        change.value = "₦" + balance.toFixed(2);

        if (paid >= grandTotal && grandTotal > 0) {

            completeSale.disabled = false;

        } else {

            completeSale.disabled = true;

        }

    }

    amountPaid.addEventListener('input', calculateChange);

    calculateChange();

    // ============================
    // Prevent empty customer
    // ============================
    document.querySelector('form[action="{{ route("sales.store") }}"]')
        .addEventListener('submit', function (e) {

            if (!walkin.checked && customerSelect.value === "") {

                e.preventDefault();

                alert("Please select a customer.");

                return;

            }

            if (grandTotal <= 0) {

                e.preventDefault();

                alert("Your cart is empty.");

                return;

            }

        });

});

const addToCartForm = document.getElementById('addToCartForm');

addToCartForm.addEventListener('submit', function(e){

    e.preventDefault();

    let formData = new FormData(this);

    fetch(this.action, {

        method: 'POST',

        headers: {

            'X-CSRF-TOKEN': document
                .querySelector('meta[name="csrf-token"]')
                .content,

            'X-Requested-With': 'XMLHttpRequest'

        },

        body: formData

    })

    .then(response => response.json())

    .then(data => {

        if(data.success){

            // We'll update the table here

            let html = "";

data.cart.forEach(item => {

    html += `
        <tr>

            <td>${item.name}</td>

            <td>${item.quantity}</td>

            <td>₦${parseFloat(item.price).toFixed(2)}</td>

            <td>₦${parseFloat(item.subtotal).toFixed(2)}</td>

            <td>

                <button
                    class="btn btn-danger btn-sm">

                    <i class="fas fa-trash"></i>

                </button>

            </td>

        </tr>
    `;

});

document.getElementById('cartTable').innerHTML = html;

document.getElementById('grandTotal').innerHTML =
"₦" + parseFloat(data.grandTotal).toLocaleString(undefined,{
    minimumFractionDigits:2
});

        }

    });

});
</script>
