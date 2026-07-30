@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">

                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2"></i>
                        Edit Medicine
                    </h4>

                    <a href="{{ route('medicines.index') }}" class="btn btn-dark btn-sm">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

                <div class="card-body p-4">

                    <form action="{{ route('medicine.update', $medicine->id) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-capsules text-primary me-1"></i>
                                    Medicine Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $medicine->name) }}"
                                    required>

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-layer-group text-success me-1"></i>
                                    Category
                                </label>

                                <select
                                    name="category_id"
                                    class="form-select"
                                    required>

                                    @foreach($categories as $category)

                                        <option
                                            value="{{ $category->id }}"
                                            {{ $medicine->category_id == $category->id ? 'selected' : '' }}>

                                            {{ $category->name }}

                                        </option>

                                    @endforeach

                                </select>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-boxes text-info me-1"></i>
                                    Quantity
                                </label>

                                <input
                                    type="number"
                                    name="quantity"
                                    class="form-control"
                                    value="{{ old('quantity', $medicine->quantity) }}"
                                    required>

                            </div>
                            
                            <div class="col-md-4 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-boxes text-info me-1"></i>
                                    Minimum Stock
                                </label>

                                <input
                                    type="number"
                                    name="minimum_stock"
                                    class="form-control"
                                    value="{{ old('quantity', $medicine->quantity) }}"
                                    required>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-money-bill-wave text-success me-1"></i>
                                    Cost Price
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">₦</span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="cost_price"
                                        class="form-control"
                                        value="{{ old('cost_price', $medicine->cost_price) }}"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-4 mb-3">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-tags text-danger me-1"></i>
                                    Selling Price
                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">₦</span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="selling_price"
                                        class="form-control"
                                        value="{{ old('selling_price', $medicine->selling_price) }}"
                                        required>

                                </div>

                            </div>

                            <div class="col-md-6 mb-4">

                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-warning me-1"></i>
                                    Expiry Date
                                </label>

                                <input
                                    type="date"
                                    name="expiry_date"
                                    class="form-control"
                                    value="{{ old('expiry_date', $medicine->expiry_date) }}"
                                    required>

                            </div>

                            <div class="col-md-6">

                                <label class="form-label fw-semibold">

                                    Barcode

                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    name="barcode"
                                    value="{{ old('barcode',$medicine->barcode ?? '') }}">

                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('medicines.index') }}"
                               class="btn btn-secondary">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-warning text-dark">

                                <i class="fas fa-save me-1"></i>

                                Update Medicine

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

