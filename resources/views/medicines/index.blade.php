
@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <!-- Add Medicine -->
        <div class="col-lg-4 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">
                        <i class="fas fa-capsules me-2"></i>
                        Add New Medicine
                    </h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('medicine.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Medicine Name
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Enter medicine name"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Quantity
                            </label>

                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   placeholder="0"
                                   required>

                        </div>

                         <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Minimum Stock
                            </label>

                            <input type="number"
                                   name="minimum_stock"
                                   class="form-control"
                                    value="{{ old('minimum_stock', $medicine->minimum_stock ?? 10) }}">

                        </div>



                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Cost Price
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="cost_price"
                                   class="form-control"
                                   placeholder="0.00"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Selling Price
                            </label>

                            <input type="number"
                                   step="0.01"
                                   name="selling_price"
                                   class="form-control"
                                   placeholder="0.00"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Expiry Date
                            </label>

                            <input type="date"
                                   name="expiry_date"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Category
                            </label>

                            <select name="category_id"
                                    class="form-select"
                                    required>

                                <option value="">
                                    Select Category
                                </option>

                                @foreach($categories as $category)

                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>

                                @endforeach

                            </select>

                        </div>

                        <button class="btn btn-primary w-100">

                            <i class="fas fa-save me-2"></i>

                            Save Medicine

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Medicine List -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-pills text-primary me-2"></i>
                                Medicine Inventory
                            </h5>

                            <small class="text-muted">
                                Total Medicines: {{ $medicines->total() }}
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
                            placeholder="Search medicine, category, price or stock..."
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
                                    <th>Medicine</th>
                                    <th>Stock</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Expiry</th>
                                    <th>Category</th>
                                    <th>Barcode</th>
                                    <th class="text-center">Actions</th>

                                </tr>

                            </thead>

                            <tbody id="tableBody">

                             @include('medicines.table')

                            </tbody>

                        </table>

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

        fetch("{{ route('medicines.index') }}?search=" + encodeURIComponent(this.value), {
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

