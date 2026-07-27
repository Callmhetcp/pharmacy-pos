@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <!-- Add Supplier -->
        <div class="col-lg-4 mb-4">

           <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
               <div class="card-header border-0 text-white"
     style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

                    <h5 class="mb-0">

                        <i class="fas fa-truck me-2"></i>

                        Add New Supplier

                    </h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('suppliers.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Company Name

                            </label>

                            <input
                                type="text"
                                name="company"
                                class="form-control form-control-lg"
                                value="{{ old('company') }}"
                                placeholder="Enter company name"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Supplier Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control form-control-lg"
                                value="{{ old('name') }}"
                                placeholder="Enter supplier name"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Phone Number

                            </label>

                            <input
                                type="tel"
                                name="phone_number"
                                class="form-control form-control-lg"
                                value="{{ old('phone_number') }}"
                                placeholder="Enter phone number"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Email Address

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control form-control-lg"
                                value="{{ old('email') }}"
                                placeholder="Enter email">

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Address

                            </label>

                            <textarea
                                name="address"
                                rows="3"
                               class="form-control form-control-lg"
                                placeholder="Enter address">{{ old('address') }}</textarea>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Status

                            </label>

                            <select
                                name="status"
                               class="form-select form-select-lg">

                                <option value="Active">Active</option>

                                <option value="Inactive">Inactive</option>

                            </select>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Notes

                            </label>

                            <textarea
                                name="notes"
                                rows="3"
                               class="form-control form-control-lg"
                                placeholder="Additional notes">{{ old('notes') }}</textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-save me-2"></i>

                            Save Supplier

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Supplier List -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h5 class="mb-0 fw-bold">

                                <i class="fas fa-truck text-primary me-2"></i>

                                Supplier Directory

                            </h5>

                            <small class="text-muted">

                                Total Suppliers:
                                {{ $suppliers->total() }}

                            </small>

                        </div>

                    </div>

                </div>

                <div class="card-body">

                    <div class="col-md-12">

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-search"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                id="search"
                                class="form-control form-control-lg"
                                placeholder="Search company, supplier, phone or email..."
                                value="{{ request('search') }}">

                            

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table table-hover align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>#</th>
                                    <th>Company</th>
                                    <th>Supplier</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>

                                </tr>

                            </thead>

                            <tbody id="tableBody">

                                 @include('suppliers.table')
                            </tbody>

                        </table>

                    </div>

                    <div class="mt-3">

                        {{ $suppliers->links() }}

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('search');

    search.addEventListener('keyup', function () {

        fetch("{{ route('suppliers.index') }}?search=" + encodeURIComponent(this.value), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {

            document.getElementById('tableBody').innerHTML = html;

        })
        .catch(error => console.error(error));

    });

});
</script>

@endsection
