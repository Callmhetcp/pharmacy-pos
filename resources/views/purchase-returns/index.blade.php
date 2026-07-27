@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-undo-alt me-2"></i>

                    Purchase Returns

                </h4>

                <small class="opacity-75">

                    Manage supplier purchase returns

                </small>

            </div>

            <a href="{{ route('purchase-returns.create') }}"
                class="btn btn-light">

                <i class="fas fa-plus me-1"></i>

                New Return

            </a>

        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row mb-3">

                    <div class="col-md-3">

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Return No / Supplier"
                            value="{{ request('search') }}">

                    </div>

                    <div class="col-md-2">

                        <input
                            type="date"
                            name="from"
                            class="form-control"
                            value="{{ request('from') }}">

                    </div>

                    <div class="col-md-2">

                        <input
                            type="date"
                            name="to"
                            class="form-control"
                            value="{{ request('to') }}">

                    </div>

                    <div class="col-md-2">

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option value="Completed"
                                {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option value="Pending"
                                {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="Cancelled"
                                {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button
                            class="btn btn-primary w-100">

                            <i class="fas fa-filter me-1"></i>

                            Filter

                        </button>

                    </div>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>Date</th>

                            <th>Return No</th>

                            <th>Supplier</th>

                            <th>Total Amount</th>

                            <th>Status</th>

                            <th>User</th>

                            <th width="150">
                                Actions
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($purchaseReturns as $purchaseReturn)

                        <tr>

                            <td>

                                {{ $purchaseReturn->return_date->format('d M Y') }}

                            </td>

                            <td>

                                {{ $purchaseReturn->return_number }}

                            </td>

                            <td>

                                {{ $purchaseReturn->supplier->name ?? '-' }}

                            </td>

                            <td class="fw-bold text-danger">

                                ₦{{ number_format($purchaseReturn->total_amount,2) }}

                            </td>

                            <td>

                                @if($purchaseReturn->status == 'Completed')

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                @elseif($purchaseReturn->status == 'Pending')

                                    <span class="badge bg-warning text-dark">

                                        Pending

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Cancelled

                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $purchaseReturn->user->name ?? 'System' }}

                            </td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a href="{{ route('purchase-returns.show',$purchaseReturn->id) }}"
                                        class="btn btn-sm btn-info text-white">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a href="{{ route('purchase-returns.edit',$purchaseReturn->id) }}"
                                        class="btn btn-sm btn-primary">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <form action="{{ route('purchase-returns.destroy', $purchaseReturn->id) }}"
                                        method="POST"
                                        class="delete-form d-inline">

                                        @csrf

                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                No purchase returns found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-4 d-flex justify-content-end">

                {{ $purchaseReturns->links() }}

            </div>

        </div>

    </div>

</div>

<script>

document.querySelectorAll('.delete-form').forEach(form => {

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Delete Purchase Return?',

            text: 'This will restore the returned medicines back into stock.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Yes, Delete',

            cancelButtonText: 'Cancel'

        }).then((result) => {

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>

@endsection