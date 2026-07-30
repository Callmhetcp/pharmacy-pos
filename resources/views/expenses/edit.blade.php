@extends('layouts.app')

@section('title','Edit Expense')


@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h3 class="fw-bold">

                <i class="fas fa-edit text-warning"></i>

                Edit Expense

            </h3>


            <p class="text-muted mb-0">

                Update expense information.

            </p>

        </div>




        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>

            Back

        </a>


    </div>







    <div class="row">


        <div class="col-lg-8 mx-auto">


            <div class="card shadow-sm border-0">


                <div class="card-header bg-white fw-bold">


                    <i class="fas fa-file-invoice-dollar text-warning"></i>

                    Expense Details


                </div>




                <div class="card-body">


                    <form action="{{ route('expenses.update',$expense) }}"
                          method="POST">


                        @csrf

                        @method('PUT')






                        {{-- Category --}}

                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Expense Category

                            </label>



                            <select

                                name="expense_category_id"

                                class="form-select"

                                required>


                                <option value="">

                                    Select Category

                                </option>




                                @foreach($categories as $category)


                                    <option value="{{ $category->id }}"

                                    {{ $expense->expense_category_id == $category->id ? 'selected' : '' }}>


                                        {{ $category->name }}


                                    </option>


                                @endforeach



                            </select>


                        </div>







                        {{-- Amount --}}

                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Amount

                            </label>



                            <div class="input-group">


                                <span class="input-group-text">

                                    ₦

                                </span>



                                <input

                                    type="number"

                                    step="0.01"

                                    name="amount"

                                    value="{{ $expense->amount }}"

                                    class="form-control"

                                    required>


                            </div>


                        </div>








                        <div class="row">


                            {{-- Date --}}

                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Expense Date

                                </label>



                                <input

                                    type="date"

                                    name="expense_date"

                                    value="{{ $expense->expense_date }}"

                                    class="form-control"

                                    required>


                            </div>






                            {{-- Payment --}}

                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Payment Method

                                </label>




                                <select

                                    name="payment_method"

                                    class="form-select"

                                    required>



                                    <option value="Cash"

                                    {{ $expense->payment_method == 'Cash' ? 'selected':'' }}>

                                        Cash

                                    </option>





                                    <option value="Bank Transfer"

                                    {{ $expense->payment_method == 'Bank Transfer' ? 'selected':'' }}>

                                        Bank Transfer

                                    </option>





                                    <option value="POS"

                                    {{ $expense->payment_method == 'POS' ? 'selected':'' }}>

                                        POS

                                    </option>





                                    <option value="Cheque"

                                    {{ $expense->payment_method == 'Cheque' ? 'selected':'' }}>

                                        Cheque

                                    </option>



                                </select>


                            </div>


                        </div>







                        {{-- Description --}}


                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Description

                            </label>




                            <textarea

                                name="description"

                                rows="4"

                                class="form-control">{{ $expense->description }}</textarea>


                        </div>








                        <div class="text-end">


                            <button class="btn btn-warning">


                                <i class="fas fa-save"></i>

                                Update Expense


                            </button>


                        </div>




                    </form>


                </div>


            </div>


        </div>


    </div>



</div>


@endsection