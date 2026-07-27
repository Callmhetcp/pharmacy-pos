@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-undo me-2"></i>

                    Sales Returns

                </h4>

                <small class="opacity-75">

                    Manage customer medicine returns

                </small>

            </div>

            <a href="{{ route('sales-returns.create') }}"
                class="btn btn-light">

                <i class="fas fa-plus me-1"></i>

                New Sales Return

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
                            placeholder="Return No / Customer"
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

                            <option
                                value="Completed"
                                {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                Completed
                            </option>

                            <option
                                value="Pending"
                                {{ request('status') == 'Pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option
                                value="Cancelled"
                                {{ request('status') == 'Cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>

                    </div>

                    <div class="col-md-2">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-filter me-1"></i>

                            Filter

                        </button>

                    </div>

                    <div class="col-md-1">

                        <a
                            href="{{ route('sales-returns.index') }}"
                            class="btn btn-secondary w-100">

                            <i class="fas fa-sync-alt"></i>

                        </a>

                    </div>

                </div>

            </form>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>Return No.</th>

                            <th>Sale No.</th>

                            <th>Customer</th>

                            <th>Date</th>

                            <th>Total</th>

                            <th>Status</th>

                            <th width="170">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($salesReturns as $salesReturn)

                        <tr>

                            <td>

                                {{ $salesReturn->return_number }}

                            </td>

                            <td>

                                {{ $salesReturn->sale->receipt_number ?? '-' }}

                            </td>

                            <td>

                                {{ $salesReturn->customer->name ?? '-' }}

                            </td>

                            <td>

                                {{ $salesReturn->return_date->format('d M Y') }}

                            </td>

                            <td>

                                ₦{{ number_format($salesReturn->total_amount,2) }}

                            </td>

                            <td>

                                @if($salesReturn->status == 'Completed')

                                    <span class="badge bg-success">

                                        Completed

                                    </span>

                                @elseif($salesReturn->status == 'Pending')

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

                              <div class="d-flex gap-2">

                                    <a href="{{ route('sales-returns.show',$salesReturn->id) }}"
                                        class="btn btn-sm btn-info text-white">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a href="{{ route('sales-returns.edit',$salesReturn->id) }}"
                                        class="btn btn-sm btn-primary">

                                        <i class="fas fa-edit"></i>

                                    </a>

                            <form action="{{ route('sales-returns.destroy', $salesReturn) }}"
                                method="POST"
                                class="delete-form d-inline">

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
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

                                No Sales Returns Found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3 d-flex justify-content-end">

                {{ $salesReturns->links() }}

            </div>

        </div>

    </div>

</div>
<script>
    document.querySelectorAll('.delete-form').forEach(form => {

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Delete Sales Return?',

            text: 'This will remove the returned medicines from stock.',

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