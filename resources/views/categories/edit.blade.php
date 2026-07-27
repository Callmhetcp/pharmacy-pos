@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">

                    <h4 class="mb-0 fw-bold">

                        <i class="fas fa-edit me-2"></i>

                        Edit Category

                    </h4>

                    <a href="{{ route('categories.index') }}"
                       class="btn btn-dark btn-sm">

                        <i class="fas fa-arrow-left me-1"></i>

                        Back

                    </a>

                </div>

                <div class="card-body p-4">

                    <form action="{{ route('categories.update', $category->id) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                <i class="fas fa-layer-group text-primary me-1"></i>

                                Category Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $category->name) }}"
                                placeholder="Enter category name"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">

                                <i class="fas fa-align-left text-success me-1"></i>

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"
                                placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('categories.index') }}"
                               class="btn btn-secondary">

                                <i class="fas fa-times me-1"></i>

                                Cancel

                            </a>

                            <button
                                type="submit"
                                class="btn btn-warning text-dark">

                                <i class="fas fa-save me-1"></i>

                                Update Category

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

