@include('reports.pdf.header',[
    'title' => 'Medicine Report'
])

<table>

    <thead>

        <tr>

            <th>Medicine</th>
            <th>Category</th>
            <th>Stock</th>
            <th>Cost Price</th>
            <th>Selling Price</th>

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

            </tr>

        @endforeach

    </tbody>

</table>

@include('reports.pdf.footer')