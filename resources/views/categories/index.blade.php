@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row">

        <!-- Add Category -->
        <div class="col-lg-4 mb-4">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-layer-group me-2"></i>

                        Add New Category

                    </h5>

                </div>

                <div class="card-body">

                    <form action="{{ route('categories.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label fw-semibold">

                                Category Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                placeholder="Enter category name"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"
                                placeholder="Enter category description"></textarea>

                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-save me-2"></i>

                            Save Category

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- Category List -->
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>

                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-layer-group text-primary me-2"></i>
                                Category List
                            </h5>

                            <small class="text-muted">
                                Total Categories: {{ $categories->total() }}
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
                            placeholder="Search category name or description..."
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
                                    <th>Category</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>

                                </tr>

                            </thead>

                            <tbody id="tableBody">

                                @include('categories.table')

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

        fetch("{{ route('categories.index') }}?search=" + encodeURIComponent(this.value), {
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

