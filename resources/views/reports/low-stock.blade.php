@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3>
                <i class="fas fa-exclamation-triangle text-danger"></i>
                Low Stock Report
            </h3>

            <p class="text-muted mb-0">
                Medicines that have reached or fallen below their minimum stock level.
            </p>

        </div>

        <span class="badge bg-danger fs-6">
            {{ $totalLowStock }} Items
        </span>

    </div>

    <div class="card shadow">

        <div class="card-body table-responsive">

            <table class="table table-hover align-middle">

                <thead>

                    <tr>
                        <th>Medicine</th>
                        <th>Category</th>
                        <th>Current Stock</th>
                        <th>Minimum Stock</th>
                        <th>Selling Price</th>
                        <th>Status</th>
                    </tr>

                </thead>

                <tbody>

                    @forelse($medicines as $medicine)

                        <tr>

                            <td>{{ $medicine->name }}</td>

                            <td>{{ optional($medicine->category)->name }}</td>

                            <td>
                                <strong>{{ $medicine->quantity }}</strong>
                            </td>

                            <td>{{ $medicine->minimum_stock }}</td>

                            <td>
                                ₦{{ number_format($medicine->selling_price, 2) }}
                            </td>

                            <td>

                                @if($medicine->quantity == 0)

                                    <span class="badge bg-danger">
                                        Out of Stock
                                    </span>

                                @elseif($medicine->quantity <= ($medicine->minimum_stock / 2))

                                    <span class="badge bg-warning text-dark">
                                        Critical
                                    </span>

                                @else

                                    <span class="badge bg-info">
                                        Low Stock
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center">
                                No low-stock medicines found.
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