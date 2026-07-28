@extends('layouts.app')

@section('content')

<div class="container-fluid">

<h3 class="mb-4">

<i class="fas fa-pills text-primary"></i>

Medicine Report

</h3>

<div class="mb-3">

    <button onclick="window.print()" class="btn btn-dark">

        <i class="fas fa-print"></i>

        Print Report

    </button>

      <a href="{{ route('reports.medicines.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>

        <a href="#" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

</div>
<div class="card shadow mb-4">

<div class="card-header">
    

Top Selling Medicines

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>

<tr>

<th>Medicine</th>

<th>Quantity Sold</th>

<th>Total Sales</th>

</tr>

</thead>

<tbody>

@forelse($topSelling as $medicine)

<tr>

<td>{{ optional($medicine->medicine)->name }}</td>

<td>{{ $medicine->total_quantity }}</td>

<td>₦{{ number_format($medicine->total_sales,2) }}</td>

</tr>

@empty

<tr>

<td colspan="3" class="text-center">

No sales yet.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>
<div class="card shadow mb-4">

<div class="card-header">

Slow Moving Medicines

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>

<tr>

<th>Name</th>

<th>Category</th>

<th>Stock</th>

</tr>

</thead>

<tbody>

@forelse($slowMoving as $medicine)

<tr>

<td>{{ $medicine->name }}</td>

<td>{{ optional($medicine->category)->name }}</td>

<td>{{ $medicine->quantity }}</td>

</tr>

@empty

<tr>

<td colspan="3" class="text-center">

No records.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>
<div class="card shadow">

<div class="card-header">

All Medicines

</div>

<div class="card-body table-responsive">

<table class="table table-hover table-bordered">

<thead>

<tr>

<th>Name</th>

<th>Category</th>

<th>Stock</th>

<th>Cost</th>

<th>Selling</th>

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

@if($medicine->quantity==0)

<span class="badge bg-danger">

Out of Stock

</span>

@elseif($medicine->quantity <= $medicine->minimum_stock)

<span class="badge bg-warning text-dark">

Low Stock

</span>

@else

<span class="badge bg-success">

Available

</span>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

{{ $medicines->links() }}

</div>

</div>

</div>

@endsection