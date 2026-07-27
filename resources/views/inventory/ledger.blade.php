@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">


        {{-- Header --}}
        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-book me-2"></i>

                    Stock Movement Ledger

                </h4>


                <small class="opacity-75">

                    Complete history of inventory movements

                </small>

            </div>


            <a href="{{ route('inventory.index') }}" class="btn btn-light">

                <i class="fas fa-arrow-left me-1"></i>

                Back

            </a>

        </div>



        <div class="card-body">



            {{-- Filter Form --}}
            <form action="{{ route('inventory.ledger') }}" method="GET" id="filterForm">


                <div class="row mb-4 g-2">


                    {{-- Search --}}
                    <div class="col-md-3">

                        <label class="small text-muted">
                            Search
                        </label>

                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="form-control"
                            placeholder="Medicine / Reference / User"
                            value="{{ request('search') }}">

                    </div>



                    {{-- From --}}
                    <div class="col-md-2">

                        <label class="small text-muted">
                            From
                        </label>

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ request('from') }}">

                    </div>




                    {{-- To --}}
                    <div class="col-md-2">

                        <label class="small text-muted">
                            To
                        </label>

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ request('to') }}">

                    </div>




                    {{-- Type --}}
                    <div class="col-md-2">

                        <label class="small text-muted">
                            Movement
                        </label>


                        <select name="type" class="form-select">


                            <option value="">
                                All Types
                            </option>


                            <option value="in"
                                {{ request('type') == 'in' ? 'selected' : '' }}>
                                Stock In
                            </option>


                            <option value="out"
                                {{ request('type') == 'out' ? 'selected' : '' }}>
                                Stock Out
                            </option>


                            <option value="purchase"
                                {{ request('type') == 'purchase' ? 'selected' : '' }}>
                                Purchase
                            </option>


                            <option value="sale"
                                {{ request('type') == 'sale' ? 'selected' : '' }}>
                                Sale
                            </option>


                            <option value="adjustment"
                                {{ request('type') == 'adjustment' ? 'selected' : '' }}>
                                Adjustment
                            </option>


                        </select>


                    </div>




                    {{-- Buttons --}}
                    <div class="col-md-3 d-flex align-items-end gap-2">


                        <button type="submit" class="btn btn-primary flex-fill">

                            <i class="fas fa-filter"></i>

                            Filter

                        </button>



                        <a href="{{ route('inventory.ledger') }}"
                           class="btn btn-secondary">

                            <i class="fas fa-sync"></i>

                        </a>


                    </div>



                </div>


            </form>





            {{-- Ledger Table --}}
            <div class="table-responsive">


                <table class="table table-hover align-middle mb-0">


                    <thead style="background:#0d6efd;color:white;">


                        <tr>

                            <th>Date</th>

                            <th>Medicine</th>

                            <th>Reference</th>

                            <th>Movement</th>

                            <th class="text-center">
                                Stock In
                            </th>

                            <th class="text-center">
                                Stock Out
                            </th>

                            <th class="text-center">
                                Balance
                            </th>

                            <th>
                                User
                            </th>

                        </tr>


                    </thead>



                    <tbody id="ledgerTable">


                        @include('inventory.ledger_table')


                    </tbody>


                </table>


            </div>




            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-4">


                {{ $ledger->links() }}


            </div>



        </div>


    </div>


</div>





<script>

document.addEventListener('DOMContentLoaded', function(){


    const search = document.getElementById('search');

    const form = document.getElementById('filterForm');



    search.addEventListener('keyup', function(){


        let formData = new FormData(form);

        let params = new URLSearchParams(formData);



        fetch("{{ route('inventory.ledger') }}?" + params.toString(), {


            headers:{


                'X-Requested-With':'XMLHttpRequest'


            }


        })


        .then(response => {


            if(!response.ok){

                throw new Error(response.status);

            }


            return response.text();


        })


        .then(html => {


            document.getElementById('ledgerTable').innerHTML = html;


        })


        .catch(error => {


            console.error(error);


        });



    });



});


</script>


@endsection