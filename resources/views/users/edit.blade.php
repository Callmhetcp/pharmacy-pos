@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8 col-xl-7">

            {{-- ========================= --}}
            {{-- EDIT USER --}}
            {{-- ========================= --}}
            <div class="card shadow-lg border-0 mb-4">

                <div class="card-header bg-primary text-white">

                    <h4 class="mb-0">
                        <i class="fas fa-user-edit me-2"></i>
                        Edit User
                    </h4>

                </div>

                <div class="card-body">

                    <form action="{{ route('users.update',$user) }}" method="POST">

                        @csrf
                        @method('PUT')

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name',$user->name) }}"
                                required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email',$user->email) }}"
                                required>

                        </div>

                        <div class="mb-4">

                            <label class="form-label fw-semibold">
                                Role
                            </label>

                            <select
                                name="role"
                                class="form-select">

                                <option value="admin" @selected($user->role=='admin')>
                                    Administrator
                                </option>

                                <option value="pharmacist" @selected($user->role=='pharmacist')>
                                    Pharmacist
                                </option>

                                <option value="cashier" @selected($user->role=='cashier')>
                                    Cashier
                                </option>

                                <option value="storekeeper" @selected($user->role=='storekeeper')>
                                    Storekeeper
                                </option>

                            </select>

                        </div>

                        <a href="{{ route('users.index') }}"
                           class="btn btn-light border w-100 mb-2">

                            <i class="fas fa-arrow-left me-2"></i>

                            Back

                        </a>

                        <button
                            type="submit"
                            class="btn btn-primary w-100">

                            <i class="fas fa-save me-2"></i>

                            Update User

                        </button>

                    </form>

                </div>

            </div>

            {{-- ========================= --}}
            {{-- SECURITY --}}
            {{-- ========================= --}}
            <div class="card shadow-lg border-0">

                <div class="card-header bg-danger text-white">

                    <h5 class="mb-0">

                        <i class="fas fa-lock me-2"></i>

                        Security

                    </h5>

                </div>

                <div class="card-body">

                    <p class="text-muted mb-4">

                        Reset this user's password securely.
                        The user will use the new password the next time they log in.

                    </p>

                    <button
                        class="btn btn-danger w-100"
                        data-bs-toggle="modal"
                        data-bs-target="#resetPasswordModal">

                        <i class="fas fa-key me-2"></i>

                        Reset Password

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>

{{-- ========================================= --}}
{{-- RESET PASSWORD MODAL --}}
{{-- ========================================= --}}

<div class="modal fade"
     id="resetPasswordModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg">

            <form method="POST"
                  action="{{ route('users.resetPassword',$user) }}">

                @csrf
                @method('PATCH')

                <div class="modal-header bg-danger text-white">

                    <h5 class="modal-title">

                        <i class="fas fa-key me-2"></i>

                        Reset Password

                    </h5>

                    <button
                        type="button"
                        class="btn-close btn-close-white"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            New Password

                        </label>

                        <div class="input-group">

                            <input
                                type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required>

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword">

                                <i class="fas fa-eye"></i>

                            </button>

                        </div>

                    </div>

                    <div>

                        <label class="form-label fw-semibold">

                            Confirm Password

                        </label>

                        <input
                            type="password"
                            class="form-control"
                            name="password_confirmation"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-danger">

                        <i class="fas fa-save me-2"></i>

                        Reset Password

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.getElementById('togglePassword').addEventListener('click', function () {

    const password = document.getElementById('password');

    const icon = this.querySelector('i');

    if(password.type === 'password'){

        password.type = 'text';

        icon.classList.remove('fa-eye');

        icon.classList.add('fa-eye-slash');

    }else{

        password.type = 'password';

        icon.classList.remove('fa-eye-slash');

        icon.classList.add('fa-eye');

    }

});

</script>

@endpush