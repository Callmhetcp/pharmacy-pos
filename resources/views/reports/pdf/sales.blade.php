@include('reports.pdf.header',[
    'title' => 'Sales Report'
])

<table>

    <thead>

        <tr>

            <th>Receipt No.</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Cashier</th>
            <th>Payment</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Balance</th>

        </tr>

    </thead>

    <tbody>

        @foreach($sales as $sale)

            <tr>

                <td>{{ $sale->receipt_number }}</td>

                <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M Y') }}</td>

                <td>{{ optional($sale->customer)->name ?? 'Walk-in Customer' }}</td>

                <td>{{ optional($sale->user)->name }}</td>

                <td>{{ ucfirst($sale->payment_method) }}</td>

                <td>₦{{ number_format($sale->total_amount,2) }}</td>

                <td>₦{{ number_format($sale->amount_paid,2) }}</td>

                <td>₦{{ number_format($sale->balance,2) }}</td>

            </tr>

        @endforeach

    </tbody>

</table>

<br>

<table style="width:40%; margin-left:auto;">

    <tr>

        <th>Total Sales</th>

        <td>₦{{ number_format($totalSales,2) }}</td>

    </tr>

    <tr>

        <th>Total Paid</th>

        <td>₦{{ number_format($totalPaid,2) }}</td>

    </tr>

    <tr>

        <th>Outstanding Balance</th>

        <td>₦{{ number_format($totalBalance,2) }}</td>

    </tr>

    <tr>

        <th>Total Transactions</th>

        <td>{{ count($sales) }}</td>

    </tr>

</table>

@include('reports.pdf.footer')