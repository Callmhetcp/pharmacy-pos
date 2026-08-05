@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">

    {{-- Dashboard Header --}}
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-chart-line text-primary me-2"></i>
                Pharmacy Dashboard
            </h2>

            <p class="text-muted mb-0">
                Welcome back! Here's your pharmacy performance overview.
            </p>
        </div>


        <div class="text-end mt-3 mt-md-0">

            <h5 class="fw-bold mb-0">
                {{ now()->format('l') }}
            </h5>

            <small class="text-muted">
                {{ now()->format('d F Y') }}
            </small>

        </div>


        <div class="d-flex gap-2 flex-wrap mt-3">

            <a href="{{ route('sales.index') }}"
               class="btn btn-success rounded-pill px-4">

                <i class="fas fa-cash-register me-2"></i>
                New Sale

            </a>


            <a href="{{ route('purchase.create') }}"
               class="btn btn-primary rounded-pill px-4">

                <i class="fas fa-shopping-cart me-2"></i>
                New Purchase

            </a>


            <a href="{{ route('medicines.index') }}"
               class="btn btn-warning text-dark rounded-pill px-4">

                <i class="fas fa-pills me-2"></i>
                Add Medicine

            </a>

        </div>

    </div>



    {{-- Main Summary Cards --}}

    <div class="row g-4 mb-4">


        {{-- Total Sales --}}

       <div class="col-xl-3 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100">

        <div class="card-body d-flex justify-content-between align-items-center">


            <div class="flex-grow-1 overflow-hidden">

                <small class="text-muted fw-semibold">
                    Total Sales
                </small>


                <h3 class="fw-bold text-success mt-2 mb-0 text-truncate"
                    style="font-size:1.45rem;">

                    ₦{{ number_format($totalSales,2) }}

                </h3>


            </div>



            <div class="rounded-circle bg-success bg-opacity-10 p-4 flex-shrink-0 ms-3">

                <i class="fas fa-money-bill-wave fa-2x text-success"></i>

            </div>


        </div>

    </div>

</div>



        {{-- Total Purchases --}}

        <div class="col-xl-3 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100">

        <div class="card-body d-flex justify-content-between align-items-center">


            <div class="flex-grow-1 overflow-hidden">

                <small class="text-muted fw-semibold">
                    Total Purchases
                </small>


                <h3 class="fw-bold text-primary mt-2 mb-0 text-truncate"
                    style="font-size:1.45rem;">

                    ₦{{ number_format($totalPurchases,2) }}

                </h3>


            </div>



            <div class="rounded-circle bg-primary bg-opacity-10 p-4 flex-shrink-0 ms-3">

                <i class="fas fa-cart-plus fa-2x text-primary"></i>

            </div>


        </div>

    </div>

</div>



        {{-- Medicines --}}

        <div class="col-xl-3 col-md-6">

    <div class="card border-0 shadow-sm rounded-4 h-100">


        <div class="card-body d-flex justify-content-between align-items-center">


            <div class="flex-grow-1 overflow-hidden">

                <small class="text-muted fw-semibold">
                    Medicines
                </small>


                <h3 class="fw-bold text-info mt-2 mb-0 text-truncate"
                    style="font-size:1.45rem;">

                    {{ number_format($totalMedicines) }}

                </h3>


            </div>



            <div class="rounded-circle bg-info bg-opacity-10 p-4 flex-shrink-0 ms-3">

                <i class="fas fa-pills fa-2x text-info"></i>

            </div>


        </div>


    </div>

</div>




        {{-- Customers --}}

       <div class="col-xl-3 col-md-6">


    <div class="card border-0 shadow-sm rounded-4 h-100">


        <div class="card-body d-flex justify-content-between align-items-center">


            <div class="flex-grow-1 overflow-hidden">

                <small class="text-muted fw-semibold">
                    Customers
                </small>


                <h3 class="fw-bold text-warning mt-2 mb-0 text-truncate"
                    style="font-size:1.45rem;">

                    {{ number_format($totalCustomers) }}

                </h3>


            </div>



            <div class="rounded-circle bg-warning bg-opacity-10 p-4 flex-shrink-0 ms-3">

                <i class="fas fa-users fa-2x text-warning"></i>

            </div>



        </div>


    </div>


</div>

{{-- Financial Cards --}}

<div class="row g-4 mb-4">


    {{-- Total Expenses --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm rounded-4 h-100">


            <div class="card-body d-flex justify-content-between align-items-center">


                <div class="flex-grow-1 overflow-hidden">


                    <small class="text-muted fw-semibold">

                        Total Expenses

                    </small>



                    <h3 class="fw-bold text-danger mt-2 mb-0 text-truncate"
                        style="font-size:1.45rem;">


                        ₦{{ number_format($totalExpenses,2) }}


                    </h3>



                </div>



                <div class="rounded-circle bg-danger bg-opacity-10 p-4 flex-shrink-0 ms-3">


                    <i class="fas fa-wallet fa-2x text-danger"></i>


                </div>



            </div>


        </div>


    </div>







    {{-- Net Profit --}}

    <div class="col-xl-3 col-md-6">

        <div class="card border-0 shadow-sm rounded-4 h-100">


            <div class="card-body d-flex justify-content-between align-items-center">


                <div class="flex-grow-1 overflow-hidden">


                    <small class="text-muted fw-semibold">

                        Net Profit

                    </small>



                    <h3 class="fw-bold text-success mt-2 mb-0 text-truncate"
                        style="font-size:1.45rem;">


                        ₦{{ number_format($netProfit,2) }}


                    </h3>



                </div>




                <div class="rounded-circle bg-success bg-opacity-10 p-4 flex-shrink-0 ms-3">


                    <i class="fas fa-chart-line fa-2x text-success"></i>


                </div>



            </div>


        </div>


    </div>



</div>

        {{-- Today's Summary --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">


        <div class="card-header bg-dark text-white py-3">

            <h5 class="mb-0 fw-bold">

                <i class="fas fa-calendar-day me-2"></i>

                Today's Summary

            </h5>

        </div>



        <div class="card-body p-4">


            <div class="row g-4 text-center">


                <div class="col-lg-3 col-md-6">


                    <div class="p-3 rounded-4 bg-success bg-opacity-10">


                        <small class="text-muted fw-semibold">
                            Today's Sales
                        </small>


                        <h3 class="fw-bold text-success mt-2 mb-0">

                            ₦{{ number_format($todaySales,2) }}

                        </h3>


                    </div>


                </div>




                <div class="col-lg-3 col-md-6">


                    <div class="p-3 rounded-4 bg-primary bg-opacity-10">


                        <small class="text-muted fw-semibold">
                            Today's Purchases
                        </small>


                        <h3 class="fw-bold text-primary mt-2 mb-0">

                            ₦{{ number_format($todayPurchases,2) }}

                        </h3>


                    </div>


                </div>





                <div class="col-lg-3 col-md-6">


                    <div class="p-3 rounded-4 bg-warning bg-opacity-10">


                        <small class="text-muted fw-semibold">
                            Sales Returns
                        </small>


                        <h3 class="fw-bold text-warning mt-2 mb-0">

                            ₦{{ number_format($todaySalesReturns,2) }}

                        </h3>


                    </div>


                </div>





                <div class="col-lg-3 col-md-6">


                    <div class="p-3 rounded-4 bg-danger bg-opacity-10">


                        <small class="text-muted fw-semibold">
                            Purchase Returns
                        </small>


                        <h3 class="fw-bold text-danger mt-2 mb-0">

                            ₦{{ number_format($todayPurchaseReturns,2) }}

                        </h3>


                    </div>


                </div>

                {{-- Today's Expenses --}}

<div class="col-lg-3 col-md-6">


    <div class="p-3 rounded-4 bg-danger bg-opacity-10">


        <small class="text-muted fw-semibold">

            Today's Expenses

        </small>



        <h3 class="fw-bold text-danger mt-2 mb-0">


            ₦{{ number_format($todayExpenses,2) }}


        </h3>



    </div>


</div>



            </div>


        </div>


    </div>






    {{-- Inventory Overview --}}

    <div class="card border-0 shadow-sm rounded-4 mb-4">


        <div class="card-header bg-primary text-white py-3">


            <h5 class="mb-0 fw-bold">


                <i class="fas fa-boxes me-2"></i>

                Inventory Overview


            </h5>


        </div>




        <div class="card-body p-4">


            <div class="row g-4">



                {{-- Low Stock --}}

                <div class="col-lg-3 col-md-6">


                    <div class="card border-0 rounded-4 shadow-sm bg-warning bg-opacity-10 h-100">


                        <div class="card-body text-center">


                            <div class="mb-3">

                                <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>

                            </div>


                            <h2 class="fw-bold text-warning">

                                {{ $lowStockCount }}

                            </h2>


                            <p class="mb-0 fw-semibold">

                                Low Stock

                            </p>


                        </div>


                    </div>


                </div>





                {{-- Out Of Stock --}}

                <div class="col-lg-3 col-md-6">


                    <div class="card border-0 rounded-4 shadow-sm bg-danger bg-opacity-10 h-100">


                        <div class="card-body text-center">


                            <div class="mb-3">

                                <i class="fas fa-times-circle fa-3x text-danger"></i>

                            </div>


                            <h2 class="fw-bold text-danger">

                                {{ $outOfStockCount }}

                            </h2>


                            <p class="mb-0 fw-semibold">

                                Out Of Stock

                            </p>


                        </div>


                    </div>


                </div>





                {{-- Expired --}}

                <div class="col-lg-3 col-md-6">


                    <div class="card border-0 rounded-4 shadow-sm bg-secondary bg-opacity-10 h-100">


                        <div class="card-body text-center">


                            <div class="mb-3">

                                <i class="fas fa-calendar-times fa-3x text-secondary"></i>

                            </div>


                            <h2 class="fw-bold text-secondary">

                                {{ $expiredCount }}

                            </h2>


                            <p class="mb-0 fw-semibold">

                                Expired Medicines

                            </p>


                        </div>


                    </div>


                </div>





                {{-- Expiring Soon --}}

                <div class="col-lg-3 col-md-6">


                    <div class="card border-0 rounded-4 shadow-sm bg-info bg-opacity-10 h-100">


                        <div class="card-body text-center">


                            <div class="mb-3">

                                <i class="fas fa-hourglass-half fa-3x text-info"></i>

                            </div>


                            <h2 class="fw-bold text-info">

                                {{ $expiringSoonCount }}

                            </h2>


                            <p class="mb-0 fw-semibold">

                                Expiring Soon

                            </p>


                        </div>


                    </div>


                </div>




            </div>


        </div>


    </div>
    {{-- ===================================================== --}}
{{-- ANALYTICS --}}
{{-- ===================================================== --}}

<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">


    <div class="card-header bg-success text-white py-3">


        <h5 class="mb-0 fw-bold">

            <i class="fas fa-chart-line me-2"></i>

            Business Analytics

        </h5>


    </div>




    <div class="card-body p-4">


        <div class="row g-4">


            {{-- Sales Chart --}}

            <div class="col-lg-6">


                <div class="card border-0 shadow-sm rounded-4 h-100">


                    <div class="card-header bg-light border-0 py-3">


                        <h6 class="fw-bold mb-0">

                            <i class="fas fa-chart-area text-success me-2"></i>

                            Sales Performance
                            <small class="text-muted">
                                (Last 7 Days)
                            </small>

                        </h6>


                    </div>



                    <div class="card-body">


                        <canvas id="salesChart" height="140"></canvas>


                    </div>


                </div>


            </div>






            {{-- Purchase Chart --}}

            <div class="col-lg-6">


                <div class="card border-0 shadow-sm rounded-4 h-100">


                    <div class="card-header bg-light border-0 py-3">


                        <h6 class="fw-bold mb-0">


                            <i class="fas fa-shopping-cart text-primary me-2"></i>


                            Purchase Performance
                            <small class="text-muted">
                                (Last 7 Days)
                            </small>


                        </h6>


                    </div>




                    <div class="card-body">


                        <canvas id="purchaseChart" height="140"></canvas>


                    </div>


                </div>


            </div>



        </div>





        <div class="row g-4 mt-1">



            {{-- Revenue Chart --}}

            <div class="col-lg-8">


                <div class="card border-0 shadow-sm rounded-4 h-100">


                    <div class="card-header bg-light border-0 py-3">


                        <h6 class="fw-bold mb-0">


                            <i class="fas fa-chart-pie text-success me-2"></i>


                            Revenue Breakdown


                        </h6>


                    </div>




                    <div class="card-body">


                        <canvas id="revenueChart" height="120"></canvas>


                    </div>



                </div>


            </div>






            {{-- Revenue Summary --}}

            <div class="col-lg-4">


                <div class="card border-0 shadow-sm rounded-4 h-100">


                    <div class="card-body">


                        <h6 class="fw-bold mb-4">


                            <i class="fas fa-wallet me-2 text-primary"></i>


                            Financial Overview


                        </h6>





                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <span class="text-muted">

                                Total Sales

                            </span>


                            <strong class="text-success">


                                ₦{{ number_format($revenueBreakdown['sales'],2) }}


                            </strong>


                        </div>





                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <span class="text-muted">

                                Purchases

                            </span>


                            <strong class="text-primary">


                                ₦{{ number_format($revenueBreakdown['purchases'],2) }}


                            </strong>


                        </div>





                        <div class="d-flex justify-content-between align-items-center mb-3">


                            <span class="text-muted">

                                Sales Returns

                            </span>


                            <strong class="text-warning">


                                ₦{{ number_format($revenueBreakdown['salesReturns'],2) }}


                            </strong>


                        </div>





                        <div class="d-flex justify-content-between align-items-center">


                            <span class="text-muted">

                                Purchase Returns

                            </span>


                            <strong class="text-danger">


                                ₦{{ number_format($revenueBreakdown['purchaseReturns'],2) }}


                            </strong>


                        </div>

                         <div class="d-flex justify-content-between align-items-center">


                            <span class="text-muted">

                                Expenses

                            </span>


                            <strong class="text-danger">


                                ₦{{ number_format($revenueBreakdown['expenses'],2) }}

                            </strong>


                        </div>
                        



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
    | Currency Formatter
    |--------------------------------------------------------------------------
    */

    const currency = value => {

        return '₦' + Number(value)
            .toLocaleString('en-NG', {
                minimumFractionDigits:2
            });

    };





    /*
    |--------------------------------------------------------------------------
    | Sales Chart
    |--------------------------------------------------------------------------
    */


    const salesCtx = document.getElementById('salesChart');


    if(salesCtx){


        new Chart(salesCtx, {


            type:'line',


            data:{


                labels:@json($salesChart->pluck('date')),


                datasets:[{

                    label:'Sales',

                    data:@json($salesChart->pluck('total')),


                    borderWidth:3,

                    fill:true,

                    tension:.4,


                    pointRadius:5,


                }]

            },



            options:{


                responsive:true,


                maintainAspectRatio:false,



                animation:{


                    duration:1200,


                    easing:'easeOutQuart'


                },



                plugins:{


                    legend:{


                        display:true,


                        position:'top'


                    },



                    tooltip:{


                        callbacks:{


                            label:function(context){


                                return currency(context.raw);


                            }


                        }


                    }


                },


                scales:{


                    y:{


                        ticks:{


                            callback:function(value){


                                return currency(value);


                            }


                        }


                    }


                }


            }



        });


    }







    /*
    |--------------------------------------------------------------------------
    | Purchase Chart
    |--------------------------------------------------------------------------
    */


    const purchaseCtx =
        document.getElementById('purchaseChart');



    if(purchaseCtx){



        new Chart(purchaseCtx,{



            type:'bar',



            data:{



                labels:@json($purchaseChart->pluck('date')),



                datasets:[{


                    label:'Purchases',


                    data:@json($purchaseChart->pluck('total')),


                    borderRadius:8,


                }]


            },



            options:{



                responsive:true,


                maintainAspectRatio:false,



                animation:{


                    duration:1200


                },



                plugins:{



                    tooltip:{


                        callbacks:{


                            label:function(context){


                                return currency(context.raw);


                            }


                        }


                    }


                },



                scales:{



                    y:{



                        ticks:{



                            callback:function(value){


                                return currency(value);


                            }


                        }


                    }


                }


            }



        });


    }







    /*
    |--------------------------------------------------------------------------
    | Revenue Pie Chart
    |--------------------------------------------------------------------------
    */


    const revenueCtx =
        document.getElementById('revenueChart');



    if(revenueCtx){



        new Chart(revenueCtx,{



            type:'doughnut',



            data:{



                labels:[


                    'Sales',

                    'Purchases',

                    'Sales Returns',

                    'Purchase Returns',

                    'Expenses'


                ],




                datasets:[{


                    data:[


                        {{ $revenueBreakdown['sales'] }},


                        {{ $revenueBreakdown['purchases'] }},


                        {{ $revenueBreakdown['salesReturns'] }},


                        {{ $revenueBreakdown['purchaseReturns'] }},

                         {{ $revenueBreakdown['expenses'] }}


                    ],



                    borderWidth:2



                }]



            },




            options:{



                responsive:true,


                maintainAspectRatio:false,



                cutout:'65%',



                animation:{



                    animateRotate:true,


                    duration:1200



                },



                plugins:{



                    tooltip:{



                        callbacks:{



                            label:function(context){


                                return context.label +
                                ': ' +
                                currency(context.raw);



                            }



                        }


                    }


                }



            }




        });



    }





});

</script>

@endpush
@endsection