@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row">

        <div class="col-lg-4">

            <div class="card shadow border-0">

                <div class="card-body text-center">

                    @if(auth()->user()->avatar)

                        <img src="{{ asset('storage/'.auth()->user()->avatar) }}"
                             class="rounded-circle border border-3 border-primary shadow"
                             width="160"
                             height="160"
                             style="object-fit:cover;">

                    @else

                        <img src="{{ asset('images/default-user.png') }}"
                             class="rounded-circle border border-3 border-primary shadow"
                             width="160"
                             height="160">

                    @endif

                    <h3 class="mt-3 mb-1">

                        {{ auth()->user()->name }}

                    </h3>

                    <span class="badge bg-primary">

                        {{ ucfirst(auth()->user()->role) }}

                    </span>

                    <hr>

                    <p class="text-muted mb-1">

                        <i class="fas fa-envelope me-2"></i>

                        {{ auth()->user()->email }}

                    </p>

                    <p class="text-muted">

                        <i class="fas fa-user-shield me-2"></i>

                        {{ ucfirst(auth()->user()->status) }}

                    </p>

                </div>

            </div>

        </div>

        <div class="col-lg-8">

            <div class="card shadow border-0 mb-4">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-user-edit me-2"></i>

                        Update Profile

                    </h5>

                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('profile.update') }}"
                          enctype="multipart/form-data">

                        @csrf
                        @method('PUT')

                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label>Name</label>

                                <input type="text"
                                       name="name"
                                       class="form-control"
                                       value="{{ auth()->user()->name }}">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label>Email</label>

                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       value="{{ auth()->user()->email }}">

                            </div>

                        </div>

                        <div class="mb-3">

                            <label>Profile Picture</label>

                            <input type="file"
                                   class="form-control"
                                   name="avatar">

                        </div>

                        <button class="btn btn-primary">

                            <i class="fas fa-save me-2"></i>

                            Save Changes

                        </button>

                    </form>

                </div>

            </div>

            <div class="card shadow border-0">

                <div class="card-header bg-danger text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-lock me-2"></i>

                        Change Password

                    </h5>

                </div>

                <div class="card-body">

                    <form method="POST"
                          action="{{ route('profile.password') }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label>Current Password</label>

                            <input type="password"
                                   class="form-control"
                                   name="current_password">

                        </div>

                        <div class="mb-3">

                            <label>New Password</label>

                            <input type="password"
                                   class="form-control"
                                   name="password">

                        </div>

                        <div class="mb-4">

                            <label>Confirm Password</label>

                            <input type="password"
                                   class="form-control"
                                   name="password_confirmation">

                        </div>

                        <button class="btn btn-danger">

                            <i class="fas fa-key me-2"></i>

                            Change Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection