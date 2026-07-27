@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">

                    <h4 class="mb-0 fw-bold">

                        <i class="fas fa-edit me-2"></i>

                        Edit Supplier

                    </h4>

                    <a href="{{ route('suppliers.index') }}"
                       class="btn btn-dark btn-sm">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

                <div class="card-body p-4">

                    <form action="{{ route('suppliers.update', $supplier->id) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Company Name

                                </label>

                                <input
                                    type="text"
                                    name="company"
                                    class="form-control"
                                    value="{{ old('company', $supplier->company) }}"
                                    placeholder="Enter company name"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Supplier Name

                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $supplier->name) }}"
                                    placeholder="Enter supplier name"
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
                                    value="{{ old('phone_number', $supplier->phone_number) }}"
                                    placeholder="Enter phone number"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Email Address

                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $supplier->email) }}"
                                    placeholder="Enter email address">

                            </div>

                            <div class="col-12 mb-3">

                                <label class="form-label fw-semibold">

                                    Address

                                </label>

                                <textarea
                                    name="address"
                                    rows="3"
                                    class="form-control"
                                    placeholder="Enter supplier address">{{ old('address', $supplier->address) }}</textarea>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">

                                    Status

                                </label>

                                <select
                                    name="status"
                                    class="form-select">

                                    <option value="Active" {{ old('status', $supplier->status) == 'Active' ? 'selected' : '' }}>
                                        Active
                                    </option>

                                    <option value="Inactive" {{ old('status', $supplier->status) == 'Inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>

                                </select>

                            </div>

                            <div class="col-12 mb-4">

                                <label class="form-label fw-semibold">

                                    Notes

                                </label>

                                <textarea
                                    name="notes"
                                    rows="4"
                                    class="form-control"
                                    placeholder="Additional notes...">{{ old('notes', $supplier->notes) }}</textarea>

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('suppliers.index') }}"
                               class="btn btn-secondary">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-warning text-dark">

                                <i class="fas fa-save me-1"></i>

                                Update Supplier

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection
