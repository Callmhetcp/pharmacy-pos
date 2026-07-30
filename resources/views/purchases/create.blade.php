@extends('layouts.app')

@section('title','Create Purchase')

@section('content')

<div class="container-fluid">

    <!-- ==========================================================
    PAGE HEADER
    =========================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-cart-plus text-primary"></i>

                New Purchase

            </h2>

            <p class="text-muted mb-0">

                Record a supplier purchase and automatically update inventory.

            </p>

        </div>

        <a href="{{ route('purchase.index') }}"
           class="btn btn-outline-secondary">

            <i class="fas fa-arrow-left"></i>

            Back

        </a>

    </div>

    <!-- ==========================================================
    PURCHASE FORM
    =========================================================== -->

    <form
        action="{{ route('purchase.store') }}"
        method="POST"
        id="purchaseForm">

        @csrf

        <!-- ==========================================================
        PURCHASE DETAILS
        =========================================================== -->

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fas fa-file-invoice"></i>

                    Purchase Information

                </h5>

            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">

                            Supplier

                        </label>

                        <select
                            name="supplier_id"
                            class="form-select"
                            required>

                            <option value="">

                                Select Supplier

                            </option>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}">

                                    {{ $supplier->name }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">

                            Invoice Number

                        </label>

                        <input
                            type="text"
                            name="invoice_number"
                            class="form-control"
                            placeholder="Invoice Number">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label fw-bold">

                            Purchase Date

                        </label>

                        <input
                            type="date"
                            name="purchase_date"
                            value="{{ date('Y-m-d') }}"
                            class="form-control"
                            required>

                    </div>

                </div>

            </div>

        </div>

        <!-- ==========================================================
        PURCHASE ITEMS
        =========================================================== -->

        <div class="card shadow-sm border-0">

            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">

                <h5 class="mb-0">

                    <i class="fas fa-pills"></i>

                    Purchase Items

                </h5>

                <button
                    type="button"
                    id="addRow"
                    class="btn btn-light btn-sm">

                    <i class="fas fa-plus"></i>

                    Add Item

                </button>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-bordered align-middle mb-0">

                        <thead class="table-light">

                            <tr>

                                <th style="width:25%">

                                    Medicine

                                </th>

                                <th>

                                    Batch No.

                                </th>

                                <th>

                                    Expiry

                                </th>

                                <th width="90">

                                    Qty

                                </th>

                                <th>

                                    Cost Price

                                </th>

                                <th>

                                    Selling Price

                                </th>

                                <th>

                                    Subtotal

                                </th>

                                <th width="60">

                                </th>

                            </tr>

                        </thead>

                        <tbody id="purchaseBody">
                            <tr>

    <td>

        <select
            name="medicine_id[]"
            class="form-select medicine"
            required>

            <option value="">

                Select Medicine

            </option>

            @foreach($medicines as $medicine)

                <option
                    value="{{ $medicine->id }}">

                    {{ $medicine->name }}

                </option>

            @endforeach

        </select>

    </td>

    <td>

        <input
            type="text"
            name="batch_number[]"
            class="form-control"
            required>

    </td>

    <td>

        <input
            type="date"
            name="expiry_date[]"
            class="form-control"
            required>

    </td>

    <td>

        <input
            type="number"
            name="quantity[]"
            class="form-control quantity"
            min="1"
            value="1"
            required>

    </td>

    <td>

        <input
            type="number"
            step="0.01"
            min="0"
            name="cost_price[]"
            class="form-control cost_price"
            required>

    </td>

    <td>

        <input
            type="number"
            step="0.01"
            min="0"
            name="selling_price[]"
            class="form-control selling_price"
            required>

    </td>

    <td>

        <input
            type="text"
            class="form-control subtotal"
            value="0.00"
            readonly>

    </td>

    <td class="text-center">

        <button
            type="button"
            class="btn btn-danger btn-sm removeRow">

            <i class="fas fa-trash"></i>

        </button>

    </td>

</tr>

</tbody>

</table>

</div>

</div>

</div>

<!-- ==========================================================
PURCHASE SUMMARY
========================================================== -->

<div class="row mt-4">

    <div class="col-lg-7"></div>

    <div class="col-lg-5">

        <div class="card shadow border-0">

            <div class="card-header bg-primary text-white">

                <h5 class="mb-0">

                    <i class="fas fa-calculator me-2"></i>

                    Purchase Summary

                </h5>

            </div>

            <div class="card-body">

                <input
                    type="hidden"
                    name="grand_total"
                    id="grandTotal">

                <div class="d-flex justify-content-between mb-3">

                    <span class="fw-bold">

                        Grand Total

                    </span>

                    <span
                        class="fw-bold text-success fs-4">

                        ₦<span id="grandTotalText">

                            0.00

                        </span>

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ==========================================================
PAYMENT INFORMATION
========================================================== -->

<div class="row mt-4">

    <div class="col-lg-7"></div>

    <div class="col-lg-5">

        <div class="card shadow border-0">

            <div class="card-header bg-success text-white">

                <h5 class="mb-0">

                    <i class="fas fa-credit-card me-2"></i>

                    Payment Information

                </h5>

            </div>

            <div class="card-body">

                <div class="mb-3">

                    <label class="form-label fw-bold">

                        Initial Payment

                    </label>

                    <input
                        type="number"
                        name="amount_paid"
                        id="amountPaid"
                        class="form-control form-control-lg"
                        value="0"
                        min="0"
                        step="0.01">

                </div>

                <div class="mb-3">

                    <label class="form-label fw-bold">

                        Payment Method

                    </label>

                    <select
                        name="payment_method"
                        class="form-select form-select-lg">

                        <option value="Cash">

                            Cash

                        </option>

                        <option value="Bank Transfer">

                            Bank Transfer

                        </option>

                        <option value="POS">

                            POS

                        </option>

                        <option value="Cheque">

                            Cheque

                        </option>

                        <option value="Credit">

                            Credit Purchase

                        </option>

                    </select>

                </div>

                <hr>

                <div class="d-flex justify-content-between mb-2">

                    <span class="fw-bold">

                        Purchase Total

                    </span>

                    <span class="fw-bold text-success">

                        ₦<span id="paymentGrandTotal">

                            0.00

                        </span>

                    </span>

                </div>

                <div class="d-flex justify-content-between">

                    <span class="fw-bold">

                        Outstanding Balance

                    </span>

                    <span class="fw-bold text-danger">

                        ₦<span id="balanceText">

                            0.00

                        </span>

                    </span>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="text-end mt-4 mb-5">

    <button
        type="submit"
        class="btn btn-primary btn-lg px-5 rounded-pill shadow">

        <i class="fas fa-save me-2"></i>

        Save Purchase

    </button>

</div>

</form>

</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {

    const purchaseBody = document.getElementById("purchaseBody");
    const addRowBtn = document.getElementById("addRow");
    const grandTotal = document.getElementById("grandTotal");
    const amountPaid = document.getElementById("amountPaid");

    // ==========================================
    // CALCULATE TOTALS
    // ==========================================

    function calculateTotals() {

        let total = 0;

        document.querySelectorAll("#purchaseBody tr").forEach(function (row) {

            let qty = parseFloat(row.querySelector(".quantity").value) || 0;

            let cost = parseFloat(row.querySelector(".cost_price").value) || 0;

            let subtotal = qty * cost;

            row.querySelector(".subtotal").value = subtotal.toFixed(2);

            total += subtotal;

        });

        grandTotal.value = total.toFixed(2);

        document.getElementById("grandTotalText").innerHTML =
            total.toLocaleString('en-NG', {
                minimumFractionDigits: 2
            });

        document.getElementById("paymentGrandTotal").innerHTML =
            total.toLocaleString('en-NG', {
                minimumFractionDigits: 2
            });

        let paid = parseFloat(amountPaid.value) || 0;

        if (paid > total) {

            paid = total;

            amountPaid.value = total.toFixed(2);

        }

        let balance = total - paid;

        document.getElementById("balanceText").innerHTML =
            balance.toLocaleString('en-NG', {
                minimumFractionDigits: 2
            });

    }

    // ==========================================
    // ADD NEW ROW
    // ==========================================

    addRowBtn.addEventListener("click", function () {

        let row = purchaseBody.querySelector("tr").cloneNode(true);

        row.querySelectorAll("input").forEach(function (input) {

            if (
                input.classList.contains("quantity")
            ) {

                input.value = 1;

            } else if (
                input.classList.contains("subtotal")
            ) {

                input.value = "0.00";

            } else {

                input.value = "";

            }

        });

        row.querySelectorAll("select").forEach(function (select) {

            select.selectedIndex = 0;

        });

        purchaseBody.appendChild(row);

        calculateTotals();

    });

    // ==========================================
    // REMOVE ROW
    // ==========================================

    purchaseBody.addEventListener("click", function (e) {

        if (e.target.closest(".removeRow")) {

            if (purchaseBody.querySelectorAll("tr").length > 1) {

                e.target.closest("tr").remove();

                calculateTotals();

            }

        }

    });

    // ==========================================
    // LIVE CALCULATIONS
    // ==========================================

    purchaseBody.addEventListener("input", function (e) {

        if (

            e.target.classList.contains("quantity") ||

            e.target.classList.contains("cost_price")

        ) {

            calculateTotals();

        }

    });

    amountPaid.addEventListener("input", calculateTotals);

    calculateTotals();

});
</script>

@endsection