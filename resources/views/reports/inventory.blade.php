@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">

        <i class="fas fa-boxes text-warning"></i>

        Inventory Report

    </h3>

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Total Medicines</h6>

                    <h3 class="text-primary">

                        {{ $totalMedicines }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Total Stock</h6>

                    <h3 class="text-success">

                        {{ number_format($totalStock) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Low Stock</h6>

                    <h3 class="text-warning">

                        {{ $lowStock }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow text-center">

                <div class="card-body">

                    <h6>Out of Stock</h6>

                    <h3 class="text-danger">

                        {{ $outOfStock }}

                    </h3>

                </div>

            </div>

        </div>

    </div>
    <div class="row mb-4">

    <div class="col-md-6">

        <div class="card shadow">

            <div class="card-body text-center">

                <h5>Inventory Cost Value</h5>

                <h2 class="text-danger">

                    ₦{{ number_format($inventoryCost,2) }}

                </h2>

            </div>

        </div>

    </div>

    <div class="col-md-6">

        <div class="card shadow">

            <div class="card-body text-center">

                <h5>Inventory Selling Value</h5>

                <h2 class="text-success">

                    ₦{{ number_format($inventoryValue,2) }}

                </h2>

            </div>

        </div>

    </div>

</div>

<div class="mb-3">

    <button onclick="window.print()" class="btn btn-dark">

        <i class="fas fa-print"></i>

        Print Report

    </button>

    <a href="{{ route('reports.inventory.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> PDF
        </a>

        <a href="#" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Excel
        </a>

</div>
<div class="card shadow">

    <div class="card-header">

        Inventory Details

    </div>

    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">

            <thead>

                <tr>

                    <th>Medicine</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Minimum Stock</th>
                    <th>Cost Price</th>
                    <th>Selling Price</th>
                    <th>Stock Value</th>
                    <th>Status</th>

                </tr>

            </thead>

            <tbody>

                @forelse($medicines as $medicine)

                    <tr>

                        <td>{{ $medicine->name }}</td>

                        <td>{{ optional($medicine->category)->name }}</td>

                        <td>{{ $medicine->quantity }}</td>

                        <td>{{ $medicine->minimum_stock }}</td>

                        <td>₦{{ number_format($medicine->cost_price,2) }}</td>

                        <td>₦{{ number_format($medicine->selling_price,2) }}</td>

                        <td>
                            ₦{{ number_format($medicine->quantity * $medicine->selling_price,2) }}
                        </td>

                        <td>

                            @if($medicine->quantity == 0)

                                <span class="badge bg-danger">
                                    Out of Stock
                                </span>

                            @elseif($medicine->quantity <= $medicine->minimum_stock)

                                <span class="badge bg-warning text-dark">
                                    Low Stock
                                </span>

                            @else

                                <span class="badge bg-success">
                                    In Stock
                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="8" class="text-center">

                            No medicines found.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

        {{ $medicines->links() }}

    </div>

</div>

</div>

@endsection