<!DOCTYPE html>

<html>

<head>

<title>
Expense Report
</title>


<style>

body{
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #ddd;
    padding:8px;
}

th{
    background:#2c3e50;
    color:#fff;
}


</style>


</head>


<body>


<h2>
Pharmacy Expense Report
</h2>


<p>
Generated:
{{ now()->format('d M Y') }}
</p>




<table>


<thead>

<tr>

<th>#</th>

<th>Date</th>

<th>Category</th>

<th>Description</th>

<th>Payment</th>

<th>Amount</th>


</tr>

</thead>



<tbody>


@foreach($expenses as $expense)


<tr>


<td>

{{ $loop->iteration }}

</td>


<td>

{{ $expense->expense_date }}

</td>


<td>

{{ $expense->category->name }}

</td>


<td>

{{ $expense->description ?? '-' }}

</td>


<td>

{{ $expense->payment_method }}

</td>


<td>

₦{{ number_format($expense->amount,2) }}

</td>


</tr>


@endforeach


<tr>


<td colspan="5"
class="text-right">

<strong>
Total
</strong>

</td>


<td>

<strong>

₦{{ number_format($total,2) }}

</strong>

</td>


</tr>


</tbody>


</table>



</body>

</html>