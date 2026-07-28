@include('reports.pdf.header',[
    'title' => 'Inventory Report'
])

<table>

    <thead>

        <tr>

            <th>Medicine</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Cost Price</th>
            <th>Selling Price</th>
            <th>Status</th>

        </tr>

    </thead>

    <tbody>

        @foreach($medicines as $medicine)

            <tr>

                <td>{{ $medicine->name }}</td>

                <td>{{ optional($medicine->category)->name }}</td>

                <td>{{ $medicine->quantity }}</td>

                <td>₦{{ number_format($medicine->cost_price,2) }}</td>

                <td>₦{{ number_format($medicine->selling_price,2) }}</td>

                <td>

                    @if($medicine->quantity == 0)

                        Out of Stock

                    @elseif($medicine->quantity <= $medicine->minimum_stock)

                        Low Stock

                    @else

                        In Stock

                    @endif

                </td>

            </tr>

        @endforeach

    </tbody>

</table>

<br>

<h4>Total Medicines: {{ $totalMedicines }}</h4>

<h4>Total Stock: {{ number_format($totalStock) }}</h4>

<h4>Inventory Cost: ₦{{ number_format($inventoryCost,2) }}</h4>

<h4>Inventory Value: ₦{{ number_format($inventoryValue,2) }}</h4>

@include('reports.pdf.footer')
