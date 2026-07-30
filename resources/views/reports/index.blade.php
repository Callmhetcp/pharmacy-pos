@extends('layouts.app')

@section('title','Reports Center')

@section('content')

<div class="container-fluid">


    {{-- Header --}}
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





    {{-- Report Cards --}}

    <div class="row g-4">



        {{-- Sales --}}

        <div class="col-lg-4 col-md-6">

            <a href="{{ route('reports.sales') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-cash-register fa-3x text-success mb-3"></i>


                        <h4>
                            Sales Report
                        </h4>


                        <p class="text-muted">

                            Daily, weekly, monthly and custom sales.

                        </p>


                    </div>


                </div>


            </a>


        </div>





        {{-- Purchases --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.purchases') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-cart-plus fa-3x text-primary mb-3"></i>


                        <h4>
                            Purchase Report
                        </h4>


                        <p class="text-muted">

                            Purchases and supplier reports.

                        </p>


                    </div>


                </div>


            </a>


        </div>






        {{-- Inventory --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.inventory') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-boxes-stacked fa-3x text-warning mb-3"></i>


                        <h4>
                            Inventory Report
                        </h4>


                        <p class="text-muted">

                            Stock, expiry and valuation.

                        </p>


                    </div>


                </div>


            </a>


        </div>






        {{-- Profit --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.profit-loss') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-chart-line fa-3x text-danger mb-3"></i>


                        <h4>
                            Profit &amp; Loss Report
                        </h4>


                        <p class="text-muted">

                            Analyze revenue, cost of goods sold, operating expenses and net profit.

                        </p>


                    </div>


                </div>


            </a>


        </div>







        {{-- Medicines --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.medicines') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-pills fa-3x text-info mb-3"></i>


                        <h4>
                            Medicine Report
                        </h4>


                        <p class="text-muted">

                            Best selling and slow moving medicines.

                        </p>


                    </div>


                </div>


            </a>


        </div>







        {{-- Customers --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.customers') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-users fa-3x text-secondary mb-3"></i>


                        <h4>
                            Customer Report
                        </h4>


                        <p class="text-muted">

                            Customer purchase history.

                        </p>


                    </div>


                </div>


            </a>


        </div>








        {{-- Low Stock --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.low-stock') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>


                        <h4>
                            Low Stock Report
                        </h4>


                        <p class="text-muted">

                            Medicines below reorder level.

                        </p>


                    </div>


                </div>


            </a>


        </div>






        {{-- Expiry --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.expiry') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-calendar-times fa-3x text-danger mb-3"></i>


                        <h4>
                            Expiry Report
                        </h4>


                        <p class="text-muted">

                            Expired and expiring medicines.

                        </p>


                    </div>


                </div>


            </a>


        </div>







        {{-- Expenses --}}

        <div class="col-lg-4 col-md-6">


            <a href="{{ route('reports.expenses') }}"
               class="text-decoration-none">


                <div class="card shadow border-0 h-100 report-card">


                    <div class="card-body text-center">


                        <i class="fas fa-money-bill-wave fa-3x text-success mb-3"></i>


                        <h4>
                            Expense Report
                        </h4>


                        <p class="text-muted">

                            Track business expenses and spending.

                        </p>


                    </div>


                </div>


            </a>


        </div>



    </div>







    {{-- Expense Summary --}}

    <hr class="my-5">



    <h4 class="fw-bold mb-3">


        <i class="fas fa-wallet text-danger"></i>

        Expense Overview


    </h4>


    <p class="text-muted">

        Quick expense summary.

    </p>





    <div class="row g-4">



        <div class="col-md-4">


            <div class="card shadow-sm border-0">


                <div class="card-body">


                    <h6 class="text-muted">

                        Total Expenses

                    </h6>


                    <h3 class="fw-bold text-danger">

                        ₦{{ number_format($totalExpenses,2) }}

                    </h3>


                </div>


            </div>


        </div>





        <div class="col-md-4">


            <div class="card shadow-sm border-0">


                <div class="card-body">


                    <h6 class="text-muted">

                        This Month

                    </h6>


                    <h3 class="fw-bold text-warning">

                        ₦{{ number_format($monthExpenses,2) }}

                    </h3>


                </div>


            </div>


        </div>






        <div class="col-md-4">


            <div class="card shadow-sm border-0">


                <div class="card-body">


                    <h6 class="text-muted">

                        Today

                    </h6>


                    <h3 class="fw-bold text-primary">

                        ₦{{ number_format($todayExpenses,2) }}

                    </h3>


                </div>


            </div>


        </div>



    </div>



</div>


@endsection