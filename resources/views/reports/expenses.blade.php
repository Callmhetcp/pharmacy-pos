@extends('layouts.app')

@section('title','Expense Report')

@section('content')

<div class="container-fluid">


    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">

                <i class="fas fa-money-bill-wave text-danger"></i>

                Expense Report

            </h3>


            <p class="text-muted mb-0">

                Detailed expense analysis and spending overview.

            </p>


        </div>


    </div>





    {{-- Summary Card --}}

    <div class="row g-4 mb-4">


        <div class="col-md-4">


            <div class="card shadow-sm border-0 h-100">


                <div class="card-body">


                    <div class="d-flex justify-content-between align-items-center">


                        <div>


                            <h6 class="text-muted">

                                Total Expenses

                            </h6>


                            <h2 class="fw-bold text-danger">


                                ₦{{ number_format($totalExpense,2) }}


                            </h2>


                        </div>



                        <i class="fas fa-wallet fa-3x text-danger opacity-50"></i>



                    </div>


                </div>


            </div>


        </div>



    </div>








    {{-- Charts --}}

    <div class="row g-4 mb-4">


        <div class="col-md-6">


            <div class="card shadow-sm border-0 h-100">


                <div class="card-header bg-white fw-bold">


                    <i class="fas fa-chart-pie text-primary"></i>

                    Expense By Category


                </div>


                <div class="card-body">


                    <canvas id="categoryChart"></canvas>


                </div>


            </div>


        </div>







        <div class="col-md-6">


            <div class="card shadow-sm border-0 h-100">


                <div class="card-header bg-white fw-bold">


                    <i class="fas fa-chart-line text-success"></i>

                    Monthly Expense Trend


                </div>


                <div class="card-body">


                    <canvas id="monthlyChart"></canvas>


                </div>


            </div>


        </div>



    </div>








    {{-- Date Filter --}}


    <div class="card shadow-sm border-0 mb-4">


        <div class="card-body">


            <form method="GET">


                <div class="row g-3">


                    <div class="col-md-5">


                        <label class="form-label">

                            From Date

                        </label>


                        <input

                            type="date"

                            name="from"

                            value="{{ $from }}"

                            class="form-control">


                    </div>





                    <div class="col-md-5">


                        <label class="form-label">

                            To Date

                        </label>


                        <input

                            type="date"

                            name="to"

                            value="{{ $to }}"

                            class="form-control">


                    </div>





                    <div class="col-md-2 d-flex align-items-end">


                        <button class="btn btn-primary w-100">


                            <i class="fas fa-filter"></i>

                            Filter


                        </button>


                    </div>



                </div>


            </form>


        </div>


    </div>









    {{-- Expense Table --}}


    <div class="card shadow-sm border-0">


        <div class="card-header bg-white fw-bold">


            <i class="fas fa-list"></i>

            Expense History


        </div>


        <div class="card-body p-0">


            <div class="table-responsive">


                <table class="table table-hover align-middle mb-0">


                    <thead class="table-dark">


                        <tr>

                            <th>#</th>

                            <th>Date</th>

                            <th>Category</th>

                            <th>Description</th>

                            <th>Amount</th>

                            <th>Recorded By</th>

                        </tr>


                    </thead>



                    <tbody>


                    @forelse($expenses as $expense)


                        <tr>


                            <td>

                                {{ $loop->iteration }}

                            </td>



                            <td>

                                {{ $expense->expense_date }}

                            </td>



                            <td>

                                {{ $expense->category->name ?? '-' }}

                            </td>



                            <td>

                                {{ $expense->description ?? '-' }}

                            </td>



                            <td class="fw-bold text-danger">


                                ₦{{ number_format($expense->amount,2) }}


                            </td>



                            <td>


                                {{ $expense->user->name ?? '-' }}


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td colspan="6" class="text-center py-4">


                                No expenses found.


                            </td>

                        </tr>


                    @endforelse



                    </tbody>


                </table>


            </div>


        </div>


    </div>



</div>





{{-- Chart JS --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>


const categoryChart = document.getElementById('categoryChart');


if(categoryChart){


new Chart(categoryChart, {


    type:'doughnut',


    data:{


        labels:@json(
            $categoryExpenses->pluck('category.name')
        ),


        datasets:[{


            data:@json(
                $categoryExpenses->pluck('total')
            )


        }]


    },


    options:{


        responsive:true


    }


});


}







const monthlyChart = document.getElementById('monthlyChart');


if(monthlyChart){


new Chart(monthlyChart, {


    type:'line',


    data:{


        labels:@json($monthLabels),


        datasets:[{


            label:'Expenses',


            data:@json($monthValues),


            tension:0.4


        }]


    },


    options:{


        responsive:true


    }


});


}



</script>


@endsection