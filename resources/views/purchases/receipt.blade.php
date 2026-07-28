<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Purchase Receipt</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    background:#f5f5f5;
    font-family:Arial, Helvetica, sans-serif;
}

.receipt{
    width:80mm;
    margin:20px auto;
    background:#fff;
    padding:15px;
    box-shadow:0 0 10px rgba(0,0,0,.15);
}

.receipt h4,
.receipt h5,
.receipt p{
    margin:0;
}

table{
    width:100%;
    font-size:12px;
}

th,
td{
    padding:3px;
}

.total{
    font-size:16px;
    font-weight:bold;
}

@media print{

    body{
        background:white;
    }

    .receipt{
        width:100%;
        margin:0;
        box-shadow:none;
        border:none;
    }

    .no-print{
        display:none;
    }

}

</style>

</head>

<body>

<div class="receipt">

    <div class="text-center">

    @if($setting && $setting->logo)

        <img src="{{ asset('storage/' . $setting->logo) }}"
             width="70"
             class="mb-2">

    @endif

    <h3 class="mb-1">

        {{ $setting->pharmacy_name ?? 'HYPET PHARMACY' }}

    </h3>

    @if(!empty($setting?->address))

        <small>{{ $setting->address }}</small><br>

    @endif

    @if(!empty($setting?->phone))

        <small>Tel: {{ $setting->phone }}</small><br>

    @endif

    @if(!empty($setting?->email))

        <small>{{ $setting->email }}</small>

    @endif

</div>

    <br>

    <table>

        <tr>
            <td><strong>Receipt:</strong></td>
            <td class="text-end">{{ $purchase->purchase_number }}</td>
        </tr>

        <tr>
            <td><strong>Date:</strong></td>
            <td class="text-end">
                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d/m/Y') }}
            </td>
        </tr>

        <tr>
            <td><strong>Supplier:</strong></td>
            <td class="text-end">
                {{ $purchase->supplier->company }}
            </td>
        </tr>

        <tr>
            <td><strong>Invoice:</strong></td>
            <td class="text-end">
                {{ $purchase->invoice_number }}
            </td>
        </tr>

    </table>

    <hr>

    <table class="table table-sm">

        <thead>

            <tr>

                <th>Item</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Cost</th>
                <th class="text-end">Total</th>

            </tr>

        </thead>

        <tbody>

        @foreach($purchase->purchaseItems as $item)

        <tr>

            <td>

                {{ $item->medicine->name }}

                <br>

                <small>

                    Batch:
                    {{ $item->batch_number }}

                </small>

            </td>

            <td class="text-center">

                {{ $item->quantity }}

            </td>

            <td class="text-end">

                {{ number_format($item->cost_price,2) }}

            </td>

            <td class="text-end">

                {{ number_format($item->subtotal,2) }}

            </td>

        </tr>

        @endforeach

        </tbody>

    </table>

    <hr>

    <table>

        <tr>

            <td>

                <strong>Grand Total</strong>

            </td>

            <td class="text-end total">

                ₦{{ number_format($purchase->grand_total,2) }}

            </td>

        </tr>

    </table>

    <hr>

    <div class="text-center">

        <div class="text-center">

    @if($setting && $setting->logo)

        <img src="{{ asset('storage/' . $setting->logo) }}"
             width="70"
             class="mb-2">

    @endif

    <h3 class="mb-1">

        {{ $setting->pharmacy_name ?? 'HYPET PHARMACY' }}

    </h3>

    @if(!empty($setting?->address))

        <small>{{ $setting->address }}</small><br>

    @endif

    @if(!empty($setting?->phone))

        <small>Tel: {{ $setting->phone }}</small><br>

    @endif

    @if(!empty($setting?->email))

        <small>{{ $setting->email }}</small>

    @endif

</div>

    </div>

    <br>

    <div class="text-center no-print">

        <button
            class="btn btn-primary btn-sm"
            onclick="window.print()">

            <i class="fas fa-print"></i>

            Print

        </button>

        <a
            href="{{ route('purchase.index') }}"
            class="btn btn-secondary btn-sm">

            Back

        </a>

    </div>

</div>

<script>

window.onload=function(){

    window.print();

}

</script>

</body>
</html>