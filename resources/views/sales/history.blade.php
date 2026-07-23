@extends('layouts.app')
@section('content')

<h2>
    Sales History
</h2>

<form action="{{ route('sales.history') }}" method="GET" class="mb-3">
    <div class="input-group">
        <input type="text"
            id="search"
            name="search"
            class="form-control"
            placeholder="Search reciept number or payment method"
            value="{{ request('search') }}">
    </div>
</form>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Reciept No</th>
            <th>Total</th>
            <th>Paid</th>
            <th>Change</th>
            <th>Payment Method</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody id="salesTable">
        @foreach ($sales as $sale )
            <tr>
                <td>{{ $sale->receipt_number  }}</td>
                <td>{{ $sale->total_amount }}</td>
                <td>{{ $sale->amount_paid }}</td>
                <td>{{ $sale->balance }}</td>
                <td>{{ $sale->payment_method }}</td>
                <td>{{ $sale->sale_date }}</td>
                <td>
                    <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-info">

                        View

                    </a>
                </td>
            </tr>
        @endforeach
    </tbody>

</table>
    
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('search').addEventListener('keyup', function () {

        let search = this.value;

        fetch("{{ route('sales.history') }}?search=" + encodeURIComponent(search))
            .then(response => response.text())
            .then(data => {

                let parser = new DOMParser();
                let html = parser.parseFromString(data, 'text/html');

                document.getElementById('salesTable').innerHTML =
                    html.getElementById('salesTable').innerHTML;
            });

    });

});
</script>