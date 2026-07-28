@extends('layouts.app')

@section('content')

<div class="container-fluid">

<h3 class="mb-4">

<i class="fas fa-users text-primary"></i>

Customer Report

</h3>

<div class="row mb-4">

<div class="col-md-3">

<div class="card shadow text-center">

<div class="card-body">

<h6>Total Customers</h6>

<h3 class="text-primary">

{{ $totalCustomers }}

</h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow text-center">

<div class="card-body">

<h6>Active Customers</h6>

<h3 class="text-success">

{{ $activeCustomers }}

</h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow text-center">

<div class="card-body">

<h6>Outstanding Balance</h6>

<h3 class="text-danger">

₦{{ number_format($outstandingBalance,2) }}

</h3>

</div>

</div>

</div>

<div class="col-md-3">

<div class="card shadow text-center">

<div class="card-body">

<h6>Top Customers</h6>

<h3 class="text-warning">

{{ $topCustomers->count() }}

</h3>

</div>

</div>

</div>

</div>

<div class="mb-3">

    <button onclick="window.print()" class="btn btn-dark">

        <i class="fas fa-print"></i>

        Print Report

    </button>

    <a href="{{ route('reports.customers.pdf') }}" class="btn btn-danger">
        <i class="fas fa-file-pdf"></i> PDF
    </a>

        <a href="#" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

</div>
<div class="card shadow mb-4">

<div class="card-header">

Top Customers

</div>

<div class="card-body table-responsive">

<table class="table table-bordered">

<thead>

<tr>

<th>Customer</th>

<th>Total Purchases</th>

</tr>

</thead>

<tbody>

@forelse($topCustomers as $customer)

<tr>

<td>{{ $customer->name }}</td>

<td>

₦{{ number_format($customer->sales_sum_total_amount ?? 0,2) }}

</td>

</tr>

@empty

<tr>

<td colspan="2" class="text-center">

No customer records.

</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>
<div class="card shadow">

<div class="card-header">

Customer Details

</div>

<div class="card-body table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>Name</th>

<th>Phone</th>

<th>Transactions</th>

<th>Total Purchased</th>

<th>Total Paid</th>

<th>Balance</th>

</tr>

</thead>

<tbody>

@forelse($customers as $customer)

<tr>

<td>{{ $customer->name }}</td>

<td>{{ $customer->phone_number }}</td>

<td>{{ $customer->sales_count }}</td>

<td>

₦{{ number_format($customer->sales_sum_total_amount ?? 0,2) }}

</td>

<td>

₦{{ number_format($customer->sales_sum_amount_paid ?? 0,2) }}

</td>

<td>

₦{{ number_format($customer->sales_sum_balance ?? 0,2) }}

</td>

</tr>

@empty

<tr>

<td colspan="6" class="text-center">

No customers found.

</td>

</tr>

@endforelse

</tbody>

</table>

{{ $customers->links() }}

</div>

</div>

</div>

@endsection