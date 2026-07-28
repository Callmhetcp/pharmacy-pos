@include('reports.pdf.header',[
    'title' => 'Purchase Report'
])

<table>

    <thead>

        <tr>

            <th>Purchase No.</th>
            <th>Date</th>
            <th>Supplier</th>
            <th>Invoice</th>
            <th>User</th>
            <th>Total</th>

        </tr>

    </thead>

    <tbody>

        @foreach($purchases as $purchase)

            <tr>

                <td>{{ $purchase->purchase_number }}</td>

                <td>{{ $purchase->purchase_date }}</td>

                <td>{{ optional($purchase->supplier)->name }}</td>

                <td>{{ $purchase->invoice_number ?? '-' }}</td>

                <td>{{ optional($purchase->user)->name }}</td>

                <td>₦{{ number_format($purchase->grand_total,2) }}</td>

            </tr>

        @endforeach

    </tbody>

</table>

<br>

<h4>Total Purchases: {{ $totalPurchases }}</h4>

<h4>Total Amount: ₦{{ number_format($totalAmount,2) }}</h4>

<h4>Average Purchase: ₦{{ number_format($averagePurchase,2) }}</h4>

@include('reports.pdf.footer')