@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">
        <i class="fas fa-pills text-primary"></i>
        Pharmacist Dashboard
    </h3>

    <div class="row">

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h6>Total Medicines</h6>
                    <h2>{{ $totalMedicines }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h6>Low Stock</h6>
                    <h2 class="text-warning">{{ $lowStock }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h6>Out of Stock</h6>
                    <h2 class="text-danger">{{ $outOfStock }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h6>Expiring Soon</h6>
                    <h2 class="text-info">{{ $expiringSoon }}</h2>
                </div>
            </div>
        </div>

    </div>

    <div class="card shadow">

        <div class="card-header">
            Recently Added Medicines
        </div>

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead>

                    <tr>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Expiry</th>
                    </tr>

                </thead>

                <tbody>

                @forelse($recentMedicines as $medicine)

                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}</td>
                    </tr>

                @empty

                    <tr>
                        <td colspan="3" class="text-center">
                            No medicines found.
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection