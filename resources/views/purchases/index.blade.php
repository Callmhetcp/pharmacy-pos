@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white d-flex justify-content-between align-items-center">

            <div>

                <h4 class="mb-0 fw-bold">

                    <i class="fas fa-cart-plus text-primary me-2"></i>

                    Purchase Management

                </h4>

                <small class="text-muted">

                    View and manage all purchase transactions

                </small>

            </div>

            <div class="d-flex gap-2">

            <a href="{{ route('purchase-returns.index') }}"
                class="btn btn-warning">

                <i class="fas fa-undo-alt me-2"></i>

                Purchase Returns

            </a>

            <a href="{{ route('purchase.create') }}"
                class="btn btn-primary">

                <i class="fas fa-plus me-2"></i>

                New Purchase

            </a>

        </div>

        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="row g-3">

                    <div class="col-md-12">

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="fas fa-search"></i>

                            </span>

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                id="search"
                                placeholder="Search Purchase No, Invoice or Supplier..."
                                autocomplete="off"
                                value="{{ request('search') }}">

                        </div>

                    </div>

                   

                </div>

            </div>

        </div>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>#</th>
                            <th>Purchase No</th>
                            <th>Supplier</th>
                            <th>Invoice</th>
                            <th>Date</th>
                            <th class="text-end">Grand Total</th>
                            <th class="text-center">Items</th>
                            <th class="text-center">Actions</th>

                        </tr>

                    </thead>

                    <tbody  id="tableBody">

                        @include('purchases.table')

                    </tbody>

                </table>

            </div>

        </div>

        @if($purchases->hasPages())

        <div class="card-footer bg-white">

            {{ $purchases->links() }}

        </div>

        @endif

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const search = document.getElementById('search');

    search.addEventListener('keyup', function () {

        fetch("{{ route('purchase.index') }}?search=" + encodeURIComponent(this.value), {
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

