@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <!-- Dashboard Header -->

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold mb-1">

                <i class="fas fa-chart-line text-primary me-2"></i>

                Pharmacy Dashboard

            </h2>

            <small class="text-muted">

                Welcome back! Here's an overview of your pharmacy.

            </small>

        </div>

        <div class="text-end">

            <h5 class="mb-0">

                {{ now()->format('l') }}

            </h5>

            <small class="text-muted">

                {{ now()->format('d F Y') }}

            </small>

        </div>
        <div class="d-flex gap-2">

    <a href="{{ route('sales.index') }}" class="btn btn-success">

        <i class="fas fa-cash-register me-1"></i>

        New Sale

    </a>

    <a href="{{ route('purchase.create') }}" class="btn btn-primary">

        <i class="fas fa-shopping-cart me-1"></i>

        New Purchase

    </a>

    <a href="{{ route('medicines.index') }}" class="btn btn-warning text-dark">

        <i class="fas fa-pills me-1"></i>

        Add Medicine

    </a>

</div>

    </div>

    <!-- ===================================================== -->
    <!-- BUSINESS SUMMARY -->
    <!-- ===================================================== -->

    <div class="row mb-4">

        <!-- Sales -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Sales

                            </small>

                            <h3 class="fw-bold text-success mt-2">

                                ₦{{ number_format($totalSales,2) }}

                            </h3>

                        </div>

                        <div class="bg-success rounded-circle d-flex align-items-center justify-content-center"

                            style="width:70px;height:70px;">

                            <i class="fas fa-money-bill-wave fa-2x text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Purchases -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Total Purchases

                            </small>

                            <h3 class="fw-bold text-primary mt-2">

                                ₦{{ number_format($totalPurchases,2) }}

                            </h3>

                        </div>

                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"

                            style="width:70px;height:70px;">

                            <i class="fas fa-cart-plus fa-2x text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Medicines -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Medicines

                            </small>

                            <h3 class="fw-bold text-info mt-2">

                                {{ number_format($totalMedicines) }}

                            </h3>

                        </div>

                        <div class="bg-info rounded-circle d-flex align-items-center justify-content-center"

                            style="width:70px;height:70px;">

                            <i class="fas fa-pills fa-2x text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- Customers -->

        <div class="col-xl-3 col-md-6 mb-4">

            <div class="card border-0 shadow rounded-4 h-100">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <small class="text-muted">

                                Customers

                            </small>

                            <h3 class="fw-bold text-warning mt-2">

                                {{ number_format($totalCustomers) }}

                            </h3>

                        </div>

                        <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center"

                            style="width:70px;height:70px;">

                            <i class="fas fa-users fa-2x text-white"></i>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- ===================================================== -->
    <!-- TODAY'S SUMMARY -->
    <!-- ===================================================== -->

    <div class="card border-0 shadow rounded-4 mb-4">

        <div class="card-header bg-dark text-white">

            <h5 class="mb-0">

                <i class="fas fa-calendar-day me-2"></i>

                Today's Summary

            </h5>

        </div>

        <div class="card-body">

            <div class="row text-center">

                <div class="col-md-3">

                    <h6 class="text-muted">

                        Today's Sales

                    </h6>

                    <h3 class="text-success">

                        ₦{{ number_format($todaySales,2) }}

                    </h3>

                </div>

                <div class="col-md-3">

                    <h6 class="text-muted">

                        Today's Purchases

                    </h6>

                    <h3 class="text-primary">

                        ₦{{ number_format($todayPurchases,2) }}

                    </h3>

                </div>

                <div class="col-md-3">

                    <h6 class="text-muted">

                        Sales Returns

                    </h6>

                    <h3 class="text-warning">

                        ₦{{ number_format($todaySalesReturns,2) }}

                    </h3>

                </div>

                <div class="col-md-3">

                    <h6 class="text-muted">

                        Purchase Returns

                    </h6>

                    <h3 class="text-danger">

                        ₦{{ number_format($todayPurchaseReturns,2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>
        <!-- ===================================================== -->
    <!-- INVENTORY OVERVIEW -->
    <!-- ===================================================== -->

    <div class="card border-0 shadow rounded-4 mb-4">

        <div class="card-header bg-primary text-white">

            <h5 class="mb-0">

                <i class="fas fa-boxes me-2"></i>

                Inventory Overview

            </h5>

        </div>

        <div class="card-body">

            <div class="row">

                <!-- Low Stock -->

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="card border-0 bg-warning text-dark h-100">

                        <div class="card-body text-center">

                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>

                            <h2 class="fw-bold">

                                {{ $lowStockCount }}

                            </h2>

                            <h6>

                                Low Stock

                            </h6>

                        </div>

                    </div>

                </div>

                <!-- Out of Stock -->

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="card border-0 bg-danger text-white h-100">

                        <div class="card-body text-center">

                            <i class="fas fa-times-circle fa-3x mb-3"></i>

                            <h2 class="fw-bold">

                                {{ $outOfStockCount }}

                            </h2>

                            <h6>

                                Out of Stock

                            </h6>

                        </div>

                    </div>

                </div>

                <!-- Expired -->

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="card border-0 bg-secondary text-white h-100">

                        <div class="card-body text-center">

                            <i class="fas fa-calendar-times fa-3x mb-3"></i>

                            <h2 class="fw-bold">

                                {{ $expiredCount }}

                            </h2>

                            <h6>

                                Expired Medicines

                            </h6>

                        </div>

                    </div>

                </div>

                <!-- Expiring Soon -->

                <div class="col-lg-3 col-md-6 mb-3">

                    <div class="card border-0 bg-info text-white h-100">

                        <div class="card-body text-center">

                            <i class="fas fa-hourglass-half fa-3x mb-3"></i>

                            <h2 class="fw-bold">

                                {{ $expiringSoonCount }}

                            </h2>

                            <h6>

                                Expiring Soon

                            </h6>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <!-- ===================================================== -->
<!-- ANALYTICS -->
<!-- ===================================================== -->

<div class="card border-0 shadow rounded-4 mb-4">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">

            <i class="fas fa-chart-line me-2"></i>

            Business Analytics

        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            <!-- Sales Chart -->

            <div class="col-lg-6 mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h6 class="mb-0">

                            Sales (Last 7 Days)

                        </h6>

                    </div>

                    <div class="card-body">

                        <canvas id="salesChart" height="140"></canvas>

                    </div>

                </div>

            </div>

            <!-- Purchase Chart -->

            <div class="col-lg-6 mb-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h6 class="mb-0">

                            Purchases (Last 7 Days)

                        </h6>

                    </div>

                    <div class="card-body">

                        <canvas id="purchaseChart" height="140"></canvas>

                    </div>

                </div>

            </div>

        </div>

        <div class="row">

            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header">

                        <h6 class="mb-0">

                            Revenue Breakdown

                        </h6>

                    </div>

                    <div class="card-body">

                        <canvas id="revenueChart" height="120"></canvas>

                    </div>

                </div>

            </div>

            <div class="col-lg-4">

                <div class="card border-0 bg-light h-100">

                    <div class="card-body">

                        <h6 class="fw-bold mb-3">

                            Overall Revenue

                        </h6>

                        <table class="table table-borderless">

                            <tr>

                                <td>Total Sales</td>

                                <td class="text-end text-success fw-bold">

                                    ₦{{ number_format($revenueBreakdown['sales'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>Purchases</td>

                                <td class="text-end text-primary fw-bold">

                                    ₦{{ number_format($revenueBreakdown['purchases'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>Sales Returns</td>

                                <td class="text-end text-warning fw-bold">

                                    ₦{{ number_format($revenueBreakdown['salesReturns'],2) }}

                                </td>

                            </tr>

                            <tr>

                                <td>Purchase Returns</td>

                                <td class="text-end text-danger fw-bold">

                                    ₦{{ number_format($revenueBreakdown['purchaseReturns'],2) }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
@push('scripts')

<script>

document.addEventListener('DOMContentLoaded', function(){

    /*
    |--------------------------------------------------------------------------
    | Sales Chart
    |--------------------------------------------------------------------------
    */

    const salesCtx = document.getElementById('salesChart');

    if(salesCtx){

        new Chart(salesCtx,{

            type:'line',

            data:{

                labels:@json($salesChart->pluck('date')),

                datasets:[{

                    label:'Sales',

                    data:@json($salesChart->pluck('total')),

                    borderColor:'#198754',

                    backgroundColor:'rgba(25,135,84,.15)',

                    borderWidth:3,

                    fill:true,

                    tension:.4

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Purchase Chart
    |--------------------------------------------------------------------------
    */

    const purchaseCtx = document.getElementById('purchaseChart');

    if(purchaseCtx){

        new Chart(purchaseCtx,{

            type:'bar',

            data:{

                labels:@json($purchaseChart->pluck('date')),

                datasets:[{

                    label:'Purchases',

                    data:@json($purchaseChart->pluck('total')),

                    backgroundColor:'#0d6efd'

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false

            }

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Revenue Pie Chart
    |--------------------------------------------------------------------------
    */

    const revenueCtx = document.getElementById('revenueChart');

    if(revenueCtx){

        new Chart(revenueCtx,{

            type:'pie',

            data:{

                labels:[

                    'Sales',

                    'Purchases',

                    'Sales Returns',

                    'Purchase Returns'

                ],

                datasets:[{

                    data:[

                        {{ $revenueBreakdown['sales'] }},

                        {{ $revenueBreakdown['purchases'] }},

                        {{ $revenueBreakdown['salesReturns'] }},

                        {{ $revenueBreakdown['purchaseReturns'] }}

                    ],

                    backgroundColor:[

                        '#198754',

                        '#0d6efd',

                        '#ffc107',

                        '#dc3545'

                    ]

                }]

            },

            options:{

                responsive:true,

                maintainAspectRatio:false

            }

        });

    }

});

</script>

@endpush
@endsection