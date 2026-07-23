<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        @media print{
            .no-print{
                display: none;
            }
        }
    </style>
</head>
<body>

    @extends('layouts.app')
    @section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center">
                            {{-- logo goes here --}}
    
                            <h3>
                                Hypet Pharmacy
                            </h3>
                            <small class="text-muted">
                                Port Harcourt, Rivers State
    
                            </small>
    
                            <hr>
    
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <strong> Receipt No:</strong>
                                {{ $sale->receipt_number }}
                            </div>
    
                            <div class="col-6 text-end">
                                <strong> Date:</strong>
                                 {{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y h:i A') }}
                            </div>
    
                        </div>
    
                        <hr>
    
                        <div class="row">
                            <div class="col-6">
                                <strong>Customer</strong><br>
                                 {{ $sale->customer->name }}
                            </div>
    
                            <div class="col-6 text-end">
                                <strong>Cashier</strong>
                                {{ $sale->user->name }}
                            </div>
                        </div>
    
                        <hr>
                        
                        <table class="table table-sm table bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Medicine</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
    
                            </thead>
                            <tbody>
                                @foreach ($sale->saleItems as $item )
                                <tr>
                                    <td>{{ $item->medicine->name }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₦{{ number_format($item->unit_price,2) }}</td>
                                    <td class="text-end">₦{{ number_format($item->subtotal,2) }}</td>
                                </tr>
                                    
                                @endforeach
    
                            </tbody>
                        </table>

                        <table class="table table-borderless">
                            <tr>
                                <td>
                                    Payment Method
                                </td>
                                <td class="text-end">
                                    {{ $sale->payment_method }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Amount Paid
                                </td>
                                <td class="text-end">
                                    ₦{{ number_format($sale->amount_paid,2) }}

                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Balance
                                </td>
                                <td class="text-end">
                                    ₦{{ number_format($sale->balance,2) }}
                                </td>
                            </tr>

                        </table>
    
                        <div class="text-end">
                            <H4>
                                Grand Total:
                                <span>
                                    ₦{{ number_format($sale->total_amount,2) }}
    
                                </span>
    
                            </H4>
                        </div>
                        <hr>
                        <p class="text-center text-muted">
                            Thank you for your patronage.

                            <br>

                            Medicines sold are not returnable unless damaged or wrongly dispensed.

                            <br>
                            Get Well Soon
                        </p>
                        <div class="d-flex justify-content-center gap-2 no-print">
                            <button
                                class="btn btn-primary"
                                onclick="window.print()">
                                Print Receipt
                                
                            </button>
                            <a href="/sales"
                            class="btn btn-success">
                            New Sale
                            </a>
    
                        </div>
    
                    </div>
    
                </div>
    
            </div>

        </div>

    </div>
      <script>
        window.onload = function(){
            window.print();
        };

        window.onafterprint = function(){
            window.location.href = "/sales";
        };
    </script>
    @endsection
</body>
</html>