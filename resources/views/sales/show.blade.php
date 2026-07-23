@extends('layouts.app')
@section('content')

<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>Sale Details</h4>
        </div>

        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <strong>Reciept No:</strong>
                    {{ $sale->receipt_number }}
                </div>
                 <div class="col-md-4">
                    <strong>Customer:</strong>
                    {{ $sale->customer->name }}
                </div>
                 <div class="col-md-4">
                    <strong>Cashier:</strong>
                    {{ $sale->user->name }}
                </div>
              
            </div>

            <div class="col-mb-3">
                 <div class="col-md-4">
                    <strong>Payment Method:</strong>
                    {{ $sale->payment_method }}
                </div>

                 <div class="col-md-4">
                    <strong>Sale Date:</strong>
                    {{ $sale->sale_date }}
                </div>


            </div>

            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Medicine</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->saleItems as $item )

                        <tr>
                            <td>{{ $item->medicine->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>₦{{ number_format($item->unit_price,2) }}</td>
                            <td>₦{{ number_format($item->subtotal,2) }}</td>
                        </tr>
                        
                    @endforeach
                </tbody>

                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">
                            Total Amount
                        </th>
                        <th>
                            ₦{{ number_format($sale->total_amount,2) }}
                        </th>
                        
                        
                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">
                            Amount Paid
                        </th>
                        <th>
                             ₦{{ number_format($sale->amount_paid,2) }}
                        </th>

                    </tr>
                    <tr>
                        <th colspan="3" class="text-end">
                            Change
                        </th>
                        <th>
                             ₦{{ number_format($sale->balance,2) }}
                        </th>

                    </tr>
                </tfoot>

            </table>

            <a href="{{ route('sales.receipt', $sale->id) }}"
                class="btn btn-success">
                Print Receipt
            </a>

        </div>

    </div>
</div>
    
@endsection

