@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">
        <i class="fas fa-boxes text-primary"></i>
        Storekeeper Dashboard
    </h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6>Total Medicines</h6>
                    <h2>{{ $totalMedicines }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6>Suppliers</h6>
                    <h2>{{ $totalSuppliers }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6>Today's Purchases</h6>
                    <h2>{{ $todayPurchases }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow">
                <div class="card-body text-center">
                    <h6>Low Stock</h6>
                    <h2 class="text-danger">{{ $lowStock }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow">

        <div class="card-header">
            Recent Purchases
        </div>

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead>

                    <tr>
                        <th>Purchase No.</th>
                        <th>Supplier</th>
                        <th>Date</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($recentPurchases as $purchase)

                    <tr>
                        <td>{{ $purchase->purchase_number }}</td>
                        <td>{{ $purchase->supplier->name }}</td>
                        <td>{{ $purchase->purchase_date }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="text-center">
                            No purchases found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection