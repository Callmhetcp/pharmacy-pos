@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="fas fa-chart-bar text-primary"></i>

                Reports Center

            </h2>

            <p class="text-muted mb-0">

                View business reports and analytics.

            </p>

        </div>

    </div>

    <div class="row">

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.sales') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-cash-register fa-3x text-success mb-3"></i>

                        <h4>Sales Report</h4>

                        <p class="text-muted">

                            Daily, weekly, monthly and custom sales.

                        </p>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.purchases') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-cart-plus fa-3x text-primary mb-3"></i>

                        <h4>Purchase Report</h4>

                        <p class="text-muted">

                            Purchases and supplier reports.

                        </p>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.inventory') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-boxes-stacked fa-3x text-warning mb-3"></i>

                        <h4>Inventory Report</h4>

                        <p class="text-muted">

                            Stock, expiry and valuation.

                        </p>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.profit') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-chart-line fa-3x text-danger mb-3"></i>

                        <h4>Profit Report</h4>

                        <p class="text-muted">

                            Revenue, cost and profit.

                        </p>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.medicines') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-pills fa-3x text-info mb-3"></i>

                        <h4>Medicine Report</h4>

                        <p class="text-muted">

                            Best-selling and slow-moving medicines.

                        </p>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4 mb-4">

            <a href="{{ route('reports.customers') }}" class="text-decoration-none">

                <div class="card shadow border-0 h-100">

                    <div class="card-body text-center">

                        <i class="fas fa-users fa-3x text-secondary mb-3"></i>

                        <h4>Customer Report</h4>

                        <p class="text-muted">

                            Customer purchase history.

                        </p>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>
<div class="col-lg-4 mb-4">

    <a href="{{ route('reports.low-stock') }}" class="text-decoration-none">

        <div class="card shadow border-0 h-100">

            <div class="card-body text-center">

                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>

                <h4>Low Stock Report</h4>

                <p class="text-muted">

                    Medicines below their reorder level.

                </p>

            </div>

        </div>

    </a>

</div>

<div class="col-lg-4 mb-4">

    <a href="{{ route('reports.expiry') }}" class="text-decoration-none">

        <div class="card shadow border-0 h-100">

            <div class="card-body text-center">

                <i class="fas fa-calendar-times fa-3x text-danger mb-3"></i>

                <h4>Expiry Report</h4>

                <p class="text-muted">

                    View expired and expiring medicines.

                </p>

            </div>

        </div>

    </a>

</div>


@endsection