@include('reports.pdf.header',[
    'title' => 'Customer Report'
])

<table>

    <thead>

        <tr>

            <th>Customer</th>
            <th>Phone</th>
            <th>Transactions</th>
            <th>Total Purchased</th>
            <th>Total Paid</th>
            <th>Balance</th>

        </tr>

    </thead>

    <tbody>

        @foreach($customers as $customer)

            <tr>

                <td>{{ $customer->name }}</td>

                <td>{{ $customer->phone_number }}</td>

                <td>{{ $customer->sales_count }}</td>

                <td>₦{{ number_format($customer->sales_sum_total_amount ?? 0,2) }}</td>

                <td>₦{{ number_format($customer->sales_sum_amount_paid ?? 0,2) }}</td>

                <td>₦{{ number_format($customer->sales_sum_balance ?? 0,2) }}</td>

            </tr>

        @endforeach

    </tbody>

</table>

<br>

<h4>Total Customers: {{ $totalCustomers }}</h4>

<h4>Active Customers: {{ $activeCustomers }}</h4>

<h4>Outstanding Balance: ₦{{ number_format($outstandingBalance,2) }}</h4>

@include('reports.pdf.footer')