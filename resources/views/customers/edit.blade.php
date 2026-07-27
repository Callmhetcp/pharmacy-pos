@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">

                    <h4 class="mb-0 fw-bold">

                        <i class="fas fa-user-edit me-2"></i>

                        Edit Customer

                    </h4>

                    <a href="{{ route('customers.index') }}"
                       class="btn btn-dark btn-sm">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

                <div class="card-body p-4">

                    <form action="{{ route('customers.update', $customer->id) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Customer Name

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $customer->name) }}"
                                    placeholder="Enter customer name"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Phone Number

                                </label>

                                <input
                                    type="tel"
                                    name="phone_number"
                                    class="form-control"
                                    value="{{ old('phone_number', $customer->phone_number) }}"
                                    placeholder="Enter phone number"
                                    required>

                            </div>

                            <div class="col-12 mb-4">

                                <label class="form-label fw-semibold">

                                    Address

                                </label>

                                <textarea
                                    name="address"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Enter customer address"
                                    required>{{ old('address', $customer->address) }}</textarea>

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('customers.index') }}"
                               class="btn btn-secondary">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-warning text-dark">

                                <i class="fas fa-save me-1"></i>

                                Update Customer

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
