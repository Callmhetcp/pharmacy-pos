<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->receipt_number }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>

        body{
            background:#f5f5f5;
            font-family:Arial, Helvetica, sans-serif;
        }

        .receipt{
            width:380px;
            margin:30px auto;
            background:#fff;
            border:1px solid #ddd;
            padding:20px;
            color:#000;
        }

        .receipt h3{
            margin-bottom:5px;
            font-weight:bold;
        }

        .receipt small{
            color:#555;
        }

        .receipt hr{
            border-top:1px dashed #000;
        }

        table{
            width:100%;
            font-size:14px;
        }

        table th{
            border-bottom:1px solid #000;
            padding-bottom:5px;
        }

        table td{
            padding:6px 0;
        }

        .grand-total{
            font-size:22px;
            font-weight:bold;
        }

        .footer{
            text-align:center;
            margin-top:20px;
            font-size:13px;
        }

        @media print{

            body{
                background:#fff;
                margin:0;
                -webkit-print-color-adjust:exact;
                print-color-adjust:exact;
            }

            .receipt{
                width:100%;
                max-width:100%;
                border:none;
                box-shadow:none;
                margin:0;
            }

            .no-print{
                display:none!important;
            }

        }

    </style>

</head>

<body>

<div class="receipt">

    <div class="text-center">

        {{-- Logo --}}
        {{-- <img src="{{ asset('images/pharm_logo.png') }}" width="70"> --}}

        <h3>HYPET PHARMACY</h3>

        <small>Port Harcourt, Rivers State</small><br>

        <small>Tel: +234 XXX XXX XXXX</small>

    </div>

    <hr>

    <table>

        <tr>
            <td><strong>Receipt No</strong></td>
            <td class="text-end">{{ $sale->receipt_number }}</td>
        </tr>

        <tr>
            <td><strong>Date</strong></td>
            <td class="text-end">
                {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y h:i A') }}
            </td>
        </tr>

        <tr>
            <td><strong>Customer</strong></td>
            <td class="text-end">{{ $sale->customer->name }}</td>
        </tr>

        <tr>
            <td><strong>Cashier</strong></td>
            <td class="text-end">{{ $sale->user->name }}</td>
        </tr>

    </table>

    <hr>

    <table>

        <thead>

            <tr>

                <th>Item</th>

                <th class="text-center">Qty</th>

                <th class="text-end">Total</th>

            </tr>

        </thead>

        <tbody>

        @foreach($sale->saleItems as $item)

            <tr>

                <td>

                    {{ $item->medicine->name }}

                    <br>

                    <small>
                        ₦{{ number_format($item->unit_price,2) }}
                    </small>

                </td>

                <td class="text-center">

                    {{ $item->quantity }}

                </td>

                <td class="text-end">

                    ₦{{ number_format($item->subtotal,2) }}

                </td>

            </tr>

        @endforeach

        </tbody>

    </table>

    <hr>

    <table>

        <tr>

            <td>Payment</td>

            <td class="text-end">{{ $sale->payment_method }}</td>

        </tr>

        <tr>

            <td>Amount Paid</td>

            <td class="text-end">
                ₦{{ number_format($sale->amount_paid,2) }}
            </td>

        </tr>

        <tr>

            <td>Change</td>

            <td class="text-end">
                ₦{{ number_format($sale->balance,2) }}
            </td>

        </tr>

    </table>

    <hr>

    <div class="d-flex justify-content-between grand-total">

        <span>TOTAL</span>

        <span>
            ₦{{ number_format($sale->total_amount,2) }}
        </span>

    </div>

    <hr>

    <div class="footer">

        <strong>THANK YOU FOR YOUR PATRONAGE</strong>

        <br><br>

        Medicines sold are not returnable unless damaged or wrongly dispensed.

        <br><br>

        Get Well Soon ❤️

    </div>

</div>

<div class="text-center mt-4 no-print">

    <button onclick="window.print()" class="btn btn-primary">

        Print Receipt

    </button>

    <a href="{{ route('sales.index') }}" class="btn btn-success">

        New Sale

    </a>

</div>

<script>

window.onload = function(){

    window.print();

};

window.onafterprint = function(){

    window.location.href = "{{ route('sales.index') }}";

};

</script>

</body>
</html>