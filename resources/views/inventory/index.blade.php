@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- Statistics --}}
    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Total Medicines
                    </h6>

                    <h2 class="fw-bold text-primary">
                        {{ $totalMedicines }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Total Stock
                    </h6>

                    <h2 class="fw-bold text-success">
                        {{ number_format($totalStock) }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Low Stock
                    </h6>

                    <h2 class="fw-bold text-warning">
                        {{ $lowStock }}
                    </h2>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Expired
                    </h6>

                    <h2 class="fw-bold text-danger">
                        {{ $expired }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Inventory Table --}}
    <div class="card border-0 shadow-sm">

        <div class="card-header bg-white">

            <div class="d-flex justify-content-between align-items-center mb-3">

                <div>

                    <h5 class="fw-bold mb-0">

                        <i class="fas fa-boxes text-primary me-2"></i>

                        Inventory

                    </h5>

                    <small class="text-muted">

                        Total Medicines:
                        {{ $medicines->total() }}

                    </small>

                </div>
                <div class="d-flex gap-2">


                    <a href="{{ route('inventory.ledger') }}"
                    class="btn btn-primary">

                        <i class="fas fa-book me-2"></i>

                        Stock Ledger

                    </a>



                    <a href="{{ route('stock-adjustments.create') }}"
                    class="btn btn-warning">

                        <i class="fas fa-sliders-h me-2"></i>

                        Stock Adjustment

                    </a>


                </div>

            </div>

            <div class="input-group">

                <span class="input-group-text">

                    <i class="fas fa-search"></i>

                </span>

                <input
                    type="text"
                    id="search"
                    class="form-control"
                    placeholder="Search medicine or category..."
                    value="{{ request('search') }}">

            </div>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Medicine</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Min Stock</th>
                            <th>Expiry</th>
                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody id="tableBody">

                        @include('inventory.table')

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="d-flex justify-content-end mt-3">

        {{ $medicines->links() }}

    </div>

</div>

<script>

document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('search').addEventListener('keyup', function () {

        fetch("{{ route('inventory.index') }}?search=" + encodeURIComponent(this.value),{

            headers:{

                'X-Requested-With':'XMLHttpRequest'

            }

        })

        .then(response => response.text())

        .then(html => {

            document.getElementById('tableBody').innerHTML = html;

        });

    });

});

</script>

@endsection