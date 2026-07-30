@extends('layouts.app')

@section('title','Add Expense')


@section('content')

<div class="container-fluid">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <div>

            <h3 class="fw-bold">

                <i class="fas fa-plus-circle text-primary"></i>

                Add Expense

            </h3>


            <p class="text-muted mb-0">

                Record a new business expense.

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


                    <i class="fas fa-file-invoice-dollar text-success"></i>

                    Expense Information


                </div>





                <div class="card-body">


                    <form action="{{ route('expenses.store') }}"
                          method="POST"
                          enctype="multipart/form-data">


                        @csrf





                        {{-- Category --}}

                        <div class="mb-3">


                            <label class="form-label fw-semibold">

                                Expense Category

                            </label>


                            <select

                                name="expense_category_id"

                                class="form-select @error('expense_category_id') is-invalid @enderror"

                                required>


                                <option value="">

                                    Select Category

                                </option>



                                @foreach($categories as $category)


                                    <option value="{{ $category->id }}"

                                        {{ old('expense_category_id') == $category->id ? 'selected' : '' }}>


                                        {{ $category->name }}


                                    </option>


                                @endforeach



                            </select>




                            @error('expense_category_id')

                                <div class="text-danger small">

                                    {{ $message }}

                                </div>

                            @enderror


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

                                    value="{{ old('amount') }}"

                                    class="form-control @error('amount') is-invalid @enderror"

                                    placeholder="Enter amount"

                                    required>


                            </div>




                            @error('amount')

                                <div class="text-danger small">

                                    {{ $message }}

                                </div>

                            @enderror


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

                                    value="{{ old('expense_date',date('Y-m-d')) }}"

                                    class="form-control"

                                    required>


                            </div>







                            {{-- Payment Method --}}

                            <div class="col-md-6 mb-3">


                                <label class="form-label fw-semibold">

                                    Payment Method

                                </label>



                                <select

                                    name="payment_method"

                                    class="form-select"

                                    required>


                                    <option value="">

                                        Select Method

                                    </option>



                                    <option value="Cash">

                                        Cash

                                    </option>



                                    <option value="Bank Transfer">

                                        Bank Transfer

                                    </option>



                                    <option value="POS">

                                        POS

                                    </option>



                                    <option value="Cheque">

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

                                class="form-control"

                                placeholder="Enter expense details...">{{ old('description') }}</textarea>


                        </div>




                        <div class="mb-3">

                        <label class="form-label fw-semibold">

                        Receipt Attachment

                        </label>


                        <input 
                        type="file"
                        name="receipt"
                        class="form-control">


                        <small class="text-muted">

                        PDF, JPG or PNG (Max 2MB)

                        </small>

                        </div>



                        <div class="text-end">


                            <button class="btn btn-primary">


                                <i class="fas fa-save"></i>

                                Save Expense


                            </button>


                        </div>





                    </form>


                </div>


            </div>


        </div>


    </div>



</div>


@endsection