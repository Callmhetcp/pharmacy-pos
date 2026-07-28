@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <!-- Add Customer -->
        <div class="col-lg-4 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-user-plus me-2"></i>

                        Add New Customer

                    </h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('customers.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Customer Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Enter customer name"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Phone Number

                            </label>

                            <input
                                type="tel"
                                name="phone_number"
                                class="form-control"
                                value="{{ old('phone_number') }}"
                                placeholder="Enter phone number"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Address

                            </label>

                            <textarea
                                name="address"
                                rows="4"
                                class="form-control"
                                placeholder="Enter customer address">{{ old('address') }}</textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-save me-2"></i>

                            Save Customer

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Customer List -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-users text-primary me-2"></i>
                                Customer Directory
                            </h5>

                            <small class="text-muted">
                                Total Customers: {{ $customers->total() }}
                            </small>

                        </div>

                    </div>

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>

                        <input
                            type="text"
                            id="search"
                            class="form-control"
                            placeholder="Search customer by name, phone or address..."
                            autocomplete="off"
                            value="{{ request('search') }}">

                    </div>

                </div>
                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>
                                    <th>Customer Name</th>
                                    <th>Phone Number</th>
                                    <th>Address</th>
                                    <th class="text-center">Actions</th>

                                </tr>

                            </thead>

                            <tbody id="tableBody">

                               @include('customers.table')

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener("click", function(e){

    const button = e.target.closest(".status-btn");

    if(!button) return;


    Swal.fire({

        title: "Are you sure?",

        text: button.dataset.message,

        icon: "warning",

        showCancelButton: true,

        confirmButtonText: "Yes, continue",

        cancelButtonText: "Cancel"

    }).then((result)=>{

        if(result.isConfirmed){

            button.closest("form").submit();

        }

    });

});
</script>

@endsection
