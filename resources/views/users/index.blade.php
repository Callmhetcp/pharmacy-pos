@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h3 class="fw-bold">
            <i class="fas fa-users me-2"></i>
            User Management
        </h3>

        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>
            Add User
        </a>

    </div>

    <div class="card shadow">

        <div class="card-body p-0">

            <table class="table table-hover mb-0">

                <thead class="table-primary">

                    <tr>

                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th width="180">Action</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr>

                        <td>{{ $loop->iteration }}</td>

                        <td>{{ $user->name }}</td>

                        <td>{{ $user->email }}</td>

                        <td>

                            <span class="badge bg-primary">
                                {{ ucfirst($user->role) }}
                            </span>

                        </td>

                        <td>

                            <a href="{{ route('users.edit',$user) }}"
                               class="btn btn-warning btn-sm">

                                <i class="fas fa-edit"></i>

                            </a>

                           <form action="{{ route('users.toggleStatus', $user) }}"
                            method="POST"
                            class="d-inline toggle-user-form">

                            @csrf
                            @method('PATCH')

                            @if($user->status == 'active')

                                <button
                                    type="submit"
                                    class="btn btn-warning btn-sm">

                                    <i class="fas fa-user-slash"></i>

                                </button>

                            @else

                                <button
                                    type="submit"
                                    class="btn btn-success btn-sm">

                                    <i class="fas fa-user-check"></i>

                                </button>

                            @endif

                        </form>

                        </td>
                      <td>

                    @if($user->status == 'active')

                        <span class="badge bg-success">
                            <i class="fas fa-circle me-1"></i>
                            Active
                        </span>

                    @else

                        <span class="badge bg-secondary">
                            <i class="fas fa-circle me-1"></i>
                            Inactive
                        </span>

                    @endif

                    </td>
                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center py-4">

                            No users found.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div class="mt-3">

        {{ $users->links() }}

    </div>

</div>

@push('scripts')
<script>

document.querySelectorAll('.toggle-user-form').forEach(form => {

    form.addEventListener('submit', function(e){

        e.preventDefault();

        const isActive =
            form.querySelector('.btn-warning') !== null;

        Swal.fire({

            title: isActive
                ? 'Deactivate User?'
                : 'Activate User?',

            text: isActive
                ? 'The user will no longer be able to log in.'
                : 'The user will be able to log in again.',

            icon: 'question',

            showCancelButton: true,

            confirmButtonText: isActive
                ? 'Deactivate'
                : 'Activate',

            confirmButtonColor: isActive
                ? '#f0ad4e'
                : '#198754'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script>
@endpush
@endsection