@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-7">

            <div class="card shadow">

                <div class="card-header bg-warning">

                    <h4 class="mb-0">

                        Edit User

                    </h4>

                </div>

                <div class="card-body">

                    <form action="{{ route('users.update',$user) }}"
                          method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label>Name</label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name',$user->name) }}">

                        </div>

                        <div class="mb-3">

                            <label>Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email',$user->email) }}">

                        </div>

                        <div class="mb-4">

                            <label>Role</label>

                            <select
                                name="role"
                                class="form-select">

                                <option value="admin" @selected($user->role=='admin')>Admin</option>

                                <option value="pharmacist" @selected($user->role=='pharmacist')>Pharmacist</option>

                                <option value="cashier" @selected($user->role=='cashier')>Cashier</option>

                                <option value="storekeeper" @selected($user->role=='storekeeper')>Storekeeper</option>

                            </select>

                        </div>

                        <div class="d-flex justify-content-between">

                            <a href="{{ route('users.index') }}"
                               class="btn btn-secondary">

                                Back

                            </a>

                            <button
                                class="btn btn-primary">

                                Update User

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection