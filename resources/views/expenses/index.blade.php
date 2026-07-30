@extends('layouts.app')

@section('title','Expense Management')


@section('content')

<div class="container-fluid">


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h3 class="fw-bold">

                <i class="fas fa-money-bill-wave text-danger"></i>

                Expense Management

            </h3>


            <p class="text-muted mb-0">

                Manage business expenses and payments.

            </p>

        </div>

        <a href="{{ route('expenses.exportPdf',request()->query()) }}"
        class="btn btn-danger">

        <i class="fas fa-file-pdf"></i>

        Export PDF

        </a>




        <a href="{{ route('expenses.create') }}"
           class="btn btn-primary">

            <i class="fas fa-plus-circle"></i>

            Add Expense

        </a>


    </div>







    {{-- Summary Cards --}}

    <div class="row g-4 mb-4">


        <div class="col-md-4">


            <div class="card border-0 shadow-sm h-100">


                <div class="card-body">


                    <div class="d-flex justify-content-between">


                        <div>

                            <h6 class="text-muted">

                                Total Expenses

                            </h6>


                            <h3 class="fw-bold text-danger">

                                ₦{{ number_format($totalExpenses,2) }}

                            </h3>

                        </div>



                        <i class="fas fa-wallet fa-3x text-danger opacity-50"></i>


                    </div>


                </div>


            </div>


        </div>







        <div class="col-md-4">


            <div class="card border-0 shadow-sm h-100">


                <div class="card-body">


                    <div class="d-flex justify-content-between">


                        <div>

                            <h6 class="text-muted">

                                This Month

                            </h6>


                            <h3 class="fw-bold text-warning">

                                ₦{{ number_format($monthExpenses,2) }}

                            </h3>

                        </div>



                        <i class="fas fa-calendar-alt fa-3x text-warning opacity-50"></i>


                    </div>


                </div>


            </div>


        </div>







        <div class="col-md-4">


            <div class="card border-0 shadow-sm h-100">


                <div class="card-body">


                    <div class="d-flex justify-content-between">


                        <div>

                            <h6 class="text-muted">

                                Today's Expense

                            </h6>


                            <h3 class="fw-bold text-primary">

                                ₦{{ number_format($todayExpenses,2) }}

                            </h3>

                        </div>



                        <i class="fas fa-calendar-day fa-3x text-primary opacity-50"></i>


                    </div>


                </div>


            </div>


        </div>



    </div>








    {{-- Search --}}
<div class="card shadow-sm border-0 mb-4">

<div class="card-body">


<form method="GET">


<div class="row g-3">


<div class="col-md-3">

<input
type="text"
name="search"
value="{{ request('search') }}"
class="form-control"
placeholder="Search expense...">

</div>




<div class="col-md-3">


<select name="category"
class="form-select">


<option value="">

All Categories

</option>


@foreach($categories as $category)

<option value="{{ $category->id }}"
{{ request('category')==$category->id?'selected':'' }}>

{{ $category->name }}

</option>

@endforeach


</select>


</div>





<div class="col-md-2">


<select name="payment_method"
class="form-select">


<option value="">

Payment

</option>


<option value="Cash">

Cash

</option>


<option value="POS">

POS

</option>


<option value="Bank Transfer">

Bank Transfer

</option>


</select>


</div>





<div class="col-md-2">


<input
type="date"
name="from"
value="{{ request('from') }}"
class="form-control">


</div>





<div class="col-md-2">


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

            Expense Records


        </div>





        <div class="card-body p-0">


            <div class="table-responsive">


                <table class="table table-hover align-middle mb-0">


                    <thead class="table-dark">


                        <tr>

                            <th>#</th>

                            <th>Expense No.</th>

                            <th>Category</th>

                            <th>Date</th>

                            <th>Payment</th>

                            <th>Amount</th>

                            <th>Receipt</th>

                            <th>Recorded By</th>

                            <th width="120">Action</th>

                        </tr>


                    </thead>





                    <tbody>


                    @forelse($expenses as $expense)


                        <tr>


                            <td>

                                {{ $loop->iteration }}

                            </td>



                            <td class="fw-bold">

                                {{ $expense->expense_number }}

                            </td>




                            <td>


                                <span class="badge bg-info">

                                    {{ $expense->category->name ?? '-' }}

                                </span>


                            </td>




                            <td>

                                {{ $expense->expense_date }}

                            </td>




                            <td>

                                {{ $expense->payment_method }}

                            </td>





                            <td class="text-danger fw-bold">

                                ₦{{ number_format($expense->amount,2) }}

                            </td>

                            <td>

                            @if($expense->receipt)

                            <a href="{{ asset('storage/'.$expense->receipt) }}"
                            target="_blank"
                            class="btn btn-sm btn-outline-primary">

                                <i class="fas fa-file"></i>

                                View

                            </a>

                            @else

                            <span class="text-muted">

                            No Receipt

                            </span>

                            @endif

                            </td>





                            <td>

                                {{ $expense->user->name ?? '-' }}

                            </td>





                            <td>


                                <a href="{{ route('expenses.edit',$expense) }}"

                                   class="btn btn-sm btn-warning">


                                    <i class="fas fa-edit"></i>


                                </a>




                                <form action="{{ route('expenses.destroy',$expense) }}"

                                      method="POST"

                                      class="d-inline deleteExpenseForm">


                                    @csrf

                                    @method('DELETE')



                                    <button class="btn btn-sm btn-danger">


                                        <i class="fas fa-trash"></i>


                                    </button>



                                </form>


                            </td>



                        </tr>


                    @empty


                        <tr>

                            <td colspan="9"

                                class="text-center py-4">


                                No expenses found.


                            </td>

                        </tr>


                    @endforelse


                    </tbody>


                </table>


            </div>


        </div>





        <div class="card-footer">


            {{ $expenses->links() }}


        </div>


    </div>



</div>
<script>

document.addEventListener("DOMContentLoaded", function(){


    document.querySelectorAll(".deleteExpenseForm")
    .forEach(form => {


        form.addEventListener("submit", function(e){


            e.preventDefault();



            Swal.fire({


                title: "Delete Expense?",


                text: "This expense record will be permanently removed.",


                icon: "warning",


                showCancelButton: true,


                confirmButtonColor: "#d33",


                cancelButtonColor: "#3085d6",


                confirmButtonText: "Yes, delete it!",


                cancelButtonText: "Cancel"



            }).then((result)=>{


                if(result.isConfirmed){


                    form.submit();


                }


            });



        });



    });



});


</script>



@endsection