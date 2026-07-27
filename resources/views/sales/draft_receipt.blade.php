<!DOCTYPE html>
<html>
<head>

    <title>Draft Receipt</title>

    <style>

        body{
            font-family: Arial, sans-serif;
            padding:30px;
        }

        .receipt{
            width:400px;
            margin:auto;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            padding:8px;
            border-bottom:1px solid #ddd;
            text-align:left;
        }

        .total{
            font-size:20px;
            font-weight:bold;
            margin-top:20px;
        }

    </style>

</head>

<body>


<div class="receipt">


<h2 style="text-align:center;">
    Pharmacy Receipt
</h2>


<hr>


<p>
<strong>Draft:</strong>
{{ $draft->draft_number }}
</p>


<p>
<strong>Customer:</strong>

@if($draft->customer)

    {{ $draft->customer->name }}

@else

    Walk-in Customer

@endif

</p>


<table>

<tr>

<th>
Medicine
</th>

<th>
Qty
</th>

<th>
Price
</th>

<th>
Total
</th>

</tr>


@foreach($draft->items as $item)

<tr>

<td>
{{ $item->medicine->name }}
</td>


<td>
{{ $item->quantity }}
</td>


<td>
₦{{ number_format($item->unit_price,2) }}
</td>


<td>
₦{{ number_format($item->subtotal,2) }}
</td>


</tr>

@endforeach


</table>


<div class="total">

Total:

₦{{ number_format($draft->items->sum('subtotal'),2) }}

</div>


<br>

<a 
href="{{ route('drafts.print',$draft->id) }}"
class="btn btn-sm btn-primary">

<i class="fas fa-print"></i>

</a>


<button onclick="window.print()"
class="btn btn-sm btn-danger delete-draft">
Print
</button>


</div>


</body>
</html>