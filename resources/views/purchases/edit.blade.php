@extends('layouts.app')

@section('content')

<div class="container-fluid">

   <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <div class="card-header border-0 text-white d-flex justify-content-between align-items-center"
     style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <h4 class="mb-0">
                <i class="fas fa-cart-plus me-2"></i>
                New Purchase
            </h4>

            <a href="{{ route('purchase.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i>
                Back
            </a>

        </div>

       <div class="card-body p-4">

            <form action="{{ route('purchase.update', $purchase->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Purchase Number
                        </label>

                        <input
                            type="text"
                            name="purchase_number"
                            class="form-control form-control-lg"
                            value="{{ old('purchase_number', $purchase->purchase_number) }}"
                            readonly>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Purchase Date
                        </label>

                       <input
                            type="date"
                            name="purchase_date"
                            class="form-control form-control-lg"
                            value="{{ old('purchase_date', $purchase->purchase_date) }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Supplier
                        </label>

                       <select
                            name="supplier_id"
                            class="form-select form-select-lg"
                            required>

                            @foreach($suppliers as $supplier)

                                <option
                                    value="{{ $supplier->id }}"
                                    {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>

                                    {{ $supplier->company }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label fw-bold">
                            Invoice Number
                        </label>

                        <input
                            type="text"
                            name="invoice_number"
                            class="form-control form-control-lg"
                            value="{{ old('invoice_number',$purchase->invoice_number) }}">

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="fw-bold text-primary mb-0">

                        <i class="fas fa-pills me-2"></i>

                        Purchase Items

                    </h5>

                    <button
                        type="button"
                        class="btn btn-success rounded-pill px-4 shadow-sm"
                        id="addMedicine">

                        <i class="fas fa-plus-circle"></i>

                        Add Medicine

                    </button>

                </div>

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">
                        <thead style="background:#0d6efd;color:white;">
                            <tr>

                                <th width="20%">Medicine</th>

                                <th width="12%">Batch No</th>

                                <th width="12%">Expiry</th>

                                <th width="8%">Qty</th>

                                <th width="12%">Cost Price</th>

                                <th width="12%">Selling Price</th>

                                <th width="14%">Subtotal</th>

                                <th width="10%">Action</th>

                            </tr>

                        </thead>

                       <tbody id="purchaseBody">

                        @foreach($purchase->purchaseItems as $item)

                        <tr>

                            <td>

                                <select
                                    name="medicine_id[]"
                                    class="form-select medicine"
                                    required>

                                    @foreach($medicines as $medicine)

                                        <option
                                            value="{{ $medicine->id }}"
                                            {{ $item->medicine_id == $medicine->id ? 'selected' : '' }}>

                                            {{ $medicine->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </td>

                            <td>

                                <input
                                    type="text"
                                    name="batch_number[]"
                                    class="form-control batch_number"
                                    value="{{ $item->batch_number }}">

                            </td>

                            <td>

                                <input
                                    type="date"
                                    name="expiry_date[]"
                                    class="form-control expiry"
                                    value="{{ $item->expiry_date }}">

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="quantity[]"
                                    class="form-control quantity"
                                    value="{{ $item->quantity }}">

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="cost_price[]"
                                    class="form-control cost_price"
                                    value="{{ $item->cost_price }}">

                            </td>

                            <td>

                                <input
                                    type="number"
                                    name="selling_price[]"
                                    class="form-control selling_price"
                                    value="{{ $item->selling_price }}">

                            </td>

                            <td>

                                <input
                                    type="text"
                                    class="form-control subtotal"
                                    value="{{ number_format($item->subtotal,2,'.','') }}"
                                    readonly>

                            </td>

                            <td class="text-center">

                                <button
                                    type="button"
                                    class="btn btn-outline-danger rounded-circle removeRow">

                                    <i class="fas fa-trash"></i>

                                </button>

                            </td>

                        </tr>

                        @endforeach

                        </tbody>

                    </table>

                </div>
<div class="row mt-5">

    <div class="col-lg-7"></div>

    <div class="col-lg-5">

        <div class="card border-0 bg-light shadow-sm">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <h5 class="fw-bold">

                        Grand Total

                    </h5>

                    <h3 class="text-success fw-bold">

                        ₦<span id="grandTotalText">{{ number_format($purchase->grand_total,2) }}</span>

                    </h3>

                </div>

                <input
                    type="hidden"
                    id="grandTotal"
                    name="grand_total"
                    value="{{ $purchase->grand_total }}">

            </div>

        </div>

    </div>

</div>

                <div class="text-end mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary btn-lg px-5 rounded-pill shadow">

                        <i class="fas fa-save"></i>

                        Save Purchase

                    </button>

                </div>
                            </form>

        </div>

    </div>

</div>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const purchaseBody = document.getElementById("purchaseBody");
    const addMedicineBtn = document.getElementById("addMedicine");
    const grandTotal = document.getElementById("grandTotal");

    function addRow() {

        let html = `

        <tr>

            <td>

                <select
                    name="medicine_id[]"
                    class="form-select medicine"
                    required>

                    <option value="">Select Medicine</option>

                    @foreach($medicines as $medicine)

                        <option value="{{ $medicine->id }}">
                            {{ $medicine->name }}
                        </option>

                    @endforeach

                </select>

            </td>

            <td>

                <input
                    type="text"
                    name="batch_number[]"
                    class="form-control batch_number">

            </td>

            <td>

                <input
                    type="date"
                    name="expiry_date[]"
                    class="form-control expiry">

            </td>

            <td>

                <input
                    type="number"
                    name="quantity[]"
                    class="form-control quantity"
                    value="1"
                    min="1">

            </td>

            <td>

                <input
                    type="number"
                    name="cost_price[]"
                    class="form-control cost_price"
                    value="0"
                    min="0"
                    step="0.01">

            </td>

            <td>

                <input
                    type="number"
                    name="selling_price[]"
                    class="form-control selling_price"
                    value="0"
                    min="0"
                    step="0.01">

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
                    class="btn btn-outline-danger rounded-circle removeRow">

                    <i class="fas fa-trash"></i>

                </button>

            </td>

        </tr>

        `;

        purchaseBody.insertAdjacentHTML("beforeend", html);

    }

    addMedicineBtn.addEventListener("click", addRow);
        purchaseBody.addEventListener("input", function (e) {

        if (
            e.target.classList.contains("quantity") ||
            e.target.classList.contains("cost_price")
        ) {

            calculateTotals();

        }

    });


    purchaseBody.addEventListener("click", function (e) {

        if (e.target.closest(".removeRow")) {

            e.target.closest("tr").remove();

            calculateTotals();

        }

    });


    purchaseBody.addEventListener("change", function (e) {

        let row = e.target.closest("tr");

        if (!row) return;

        let medicine = row.querySelector(".medicine").value;
        let batch = row.querySelector(".batch_number").value;
        let expiry = row.querySelector(".expiry").value;
        let qty = row.querySelector(".quantity").value;
        let cost = row.querySelector(".cost_price").value;

        let lastRow = purchaseBody.lastElementChild;

        if (
            row === lastRow &&
            medicine &&
            batch &&
            expiry &&
            qty > 0 &&
            cost > 0
        ) {

            if(document.querySelectorAll("#purchaseBody tr").length === 0){
                addRow();
            }

            calculateTotals();

        }

    });


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
                Number(total).toLocaleString('en-NG',{
                    minimumFractionDigits:2
                });

    }

    addRow();

});
</script>
@endsection