@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Add New User
                    </h4>

                </div>

                <div class="card-body">

                    <form action="{{ route('users.store') }}" method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">Full Name</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Email Address</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Role</label>

                            <select
                                name="role"
                                class="form-select"
                                required>

                                <option value="">Select Role</option>

                                <option value="admin">Admin</option>

                                <option value="pharmacist">Pharmacist</option>

                                <option value="cashier">Cashier</option>

                                <option value="storekeeper">Storekeeper</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label">Confirm Password</label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required>

                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('users.index') }}"
                               class="btn btn-secondary">

                                Back

                            </a>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                <i class="fas fa-save me-1"></i>

                                Save User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection