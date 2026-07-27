@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-undo-alt me-2"></i>

                    New Purchase Return

                </h4>

                <small class="opacity-75">

                    Return purchased medicines to supplier

                </small>

            </div>

            <a href="{{ route('purchase-returns.index') }}"
                class="btn btn-light">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>

        <div class="card-body">

            <form action="{{ route('purchase-returns.update', $purchaseReturn->id) }}"
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
                            value="{{ $purchaseReturn->return_number }}"
                            readonly>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Purchase

                        </label>

                        <select
                        name="purchase_id"
                        id="purchase"
                        class="form-select"
                        required>

                        @foreach($purchases as $purchase)

                            <option value="{{ $purchase->id }}"
                                {{ $purchaseReturn->purchase_id == $purchase->id ? 'selected' : '' }}>

                                {{ $purchase->purchase_number }}

                            </option>

                        @endforeach

                    </select>
                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Supplier

                        </label>
                        <input
                            type="text"
                            id="supplier"
                            class="form-control"
                            value="{{ $purchaseReturn->supplier->name }}"
                            readonly>

                        <input
                            type="hidden"
                            name="supplier_id"
                            id="supplier_id"
                            value="{{ $purchaseReturn->supplier_id }}">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Return Date

                        </label>

                        <input
                            type="date"
                            name="return_date"
                            class="form-control"
                            value="{{ $purchaseReturn->return_date->format('Y-m-d') }}"
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
                        value="{{ $purchaseReturn->reason }}"
                        required>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Status

                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required>

                            <option value="Completed"
                                {{ $purchaseReturn->status == 'Completed' ? 'selected' : '' }}>

                                Completed

                            </option>

                            <option value="Pending"
                                {{ $purchaseReturn->status == 'Pending' ? 'selected' : '' }}>

                                Pending

                            </option>

                            <option value="Cancelled"
                                {{ $purchaseReturn->status == 'Cancelled' ? 'selected' : '' }}>

                                Cancelled

                            </option>

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label class="form-label">

                            Total Amount

                        </label>

                        <input
                            type="text"
                            id="grandTotal"
                            class="form-control fw-bold text-danger"
                            value="₦{{ number_format($purchaseReturn->total_amount,2) }}"
                            readonly>

                    </div>

                </div>

                <div class="table-responsive mt-3">

                    <table class="table table-bordered align-middle">

                        <thead class="table-primary">

                            <tr>

                                <th>Medicine</th>

                                <th width="120">Purchased</th>

                                <th width="120">Stock</th>

                                <th width="120">Cost Price</th>

                                <th width="120">Return Qty</th>

                                <th width="150">Subtotal</th>

                            </tr>

                        </thead>

                        <tbody id="purchaseItems">

                            <tr>

                                <td colspan="6"
                                    class="text-center py-4">

                                    Select a purchase to load medicines.

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                <div class="mt-4">

                    <button
                        type="submit"
                        class="btn btn-primary">

                        <i class="fas fa-save me-1"></i>

                        Update Purchase Return

                    </button>

                    <a href="{{ route('purchase-returns.index') }}"
                        class="btn btn-secondary">

                        Cancel

                    </a>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

document.getElementById('purchase').addEventListener('change', function(){

    let purchaseId = this.value;

    if(!purchaseId){

        document.getElementById('purchaseItems').innerHTML = `
            <tr>
                <td colspan="6" class="text-center">
                    Select a purchase.
                </td>
            </tr>
        `;

        document.getElementById('supplier').value = '';

        document.getElementById('supplier_id').value = '';

        return;
    }

    fetch('/purchase-returns/purchase/' + purchaseId)

    .then(response => response.json())

    .then(data => {

         console.log(data);

        document.getElementById('supplier').value = data.supplier.name;

        document.getElementById('supplier_id').value = data.supplier.id;

        let html = '';

        data.items.forEach((item,index)=>{

            html += `
            <tr>

                <td>

                    ${item.medicine.name}

                    <input type="hidden"
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

                    ${parseFloat(item.cost_price).toFixed(2)}

                    <input
                        type="hidden"
                        name="items[${index}][cost_price]"
                        value="${item.cost_price}">

                </td>

                <td>

                    <input
                        type="number"
                        min="0"
                        max="${item.quantity}"
                        value="0"
                        class="form-control quantity"
                        data-price="${item.cost_price}"
                        name="items[${index}][quantity]">

                </td>

                <td class="subtotal">

                    ₦0.00

                </td>

            </tr>
            `;

        });

        document.getElementById('purchaseItems').innerHTML = html;

        calculateTotals();

    });

});

function calculateTotals(){

    let grandTotal = 0;

    document.querySelectorAll('.quantity').forEach(function(input){

        input.addEventListener('input', function(){

            let qty = parseFloat(this.value) || 0;

            let price = parseFloat(this.dataset.price);

            let subtotal = qty * price;

            this.closest('tr').querySelector('.subtotal').innerHTML =
                '₦' + subtotal.toFixed(2);

            updateGrandTotal();

        });

    });

}

function updateGrandTotal(){

    let total = 0;

    document.querySelectorAll('.subtotal').forEach(function(cell){

        total += parseFloat(
            cell.innerHTML.replace('₦','')
                          .replace(',','')
        ) || 0;

    });

    document.getElementById('grandTotal').value =
        '₦' + total.toFixed(2);

}

</script>

@endsection