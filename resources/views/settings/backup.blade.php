@extends('layouts.app')

@section('content')

<div class="container-fluid">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                <i class="fas fa-database text-success"></i>
                Backup & Restore
            </h2>

            <p class="text-muted mb-0">
                Manage your pharmacy database backups and restore points.
            </p>

        </div>

        <a href="{{ route('settings.index') }}"
           class="btn btn-secondary">

            <i class="fas fa-arrow-left"></i>

            Back To Settings

        </a>

    </div>



    {{-- ================= ALERTS ================= --}}

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            <i class="fas fa-check-circle me-2"></i>

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif


    @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            <i class="fas fa-exclamation-triangle me-2"></i>

            {!! session('error') !!}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>

    @endif




    {{-- ================= CREATE BACKUP ================= --}}

    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center">

                <div>

                    <h5 class="fw-bold">

                        <i class="fas fa-download text-success"></i>

                        Create Database Backup

                    </h5>

                    <p class="text-muted mb-0">

                        Generate a complete backup of your pharmacy system.

                    </p>

                </div>

                <a href="{{ route('backup.create') }}"
                   class="btn btn-success">

                    <i class="fas fa-plus"></i>

                    Create Backup

                </a>

            </div>

        </div>

    </div>




    {{-- ================= AVAILABLE BACKUPS ================= --}}

    <div class="card shadow border-0 mb-4">

        <div class="card-body">

            <h5 class="fw-bold mb-3">

                <i class="fas fa-folder-open text-primary"></i>

                Available Backups

            </h5>

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th>File Name</th>

                            <th>Size</th>

                            <th>Date Created</th>

                            <th width="220">Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($files as $file)

                            <tr>

                                <td>

                                    <i class="fas fa-file-archive text-warning me-2"></i>

                                    {{ $file['name'] }}

                                </td>

                                <td>

                                    {{ $file['size'] }}

                                </td>

                                <td>

                                    {{ $file['date'] }}

                                </td>

                                <td>

                                    <a href="{{ route('backup.download', basename($file['path'])) }}"
                                       class="btn btn-primary btn-sm">

                                        <i class="fas fa-download"></i>

                                        Download

                                    </a>

                                    <form
                                        action="{{ route('backup.destroy', basename($file['path'])) }}"
                                        method="POST"
                                        class="d-inline deleteBackupForm">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">

                                            <i class="fas fa-trash"></i>

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="text-center text-muted py-4">

                                    No backups available.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>





    {{-- ================= RESTORE BACKUP ================= --}}

    <div class="card shadow border-0">

        <div class="card-body">

            <h5 class="fw-bold mb-3">

                <i class="fas fa-sync text-warning"></i>

                Restore Backup

            </h5>

            <p class="text-muted">

                Select a backup and restore your database.

            </p>

            <form
                action="{{ route('backup.restore') }}"
                method="POST"
                id="restoreForm">

                @csrf

                <div class="row align-items-end">

                    <div class="col-md-8">

                        <label class="form-label">

                            Backup File

                        </label>

                        <select
                            name="backup_file"
                            class="form-select"
                            required>

                            <option value="">

                                Select Backup

                            </option>

                            @foreach($files as $file)

                                <option value="{{ $file['name'] }}">

                                    {{ $file['name'] }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                    <div class="col-md-4">

                        <button
                            type="submit"
                            class="btn btn-warning w-100"
                            id="restoreBackupBtn">

                            <i class="fas fa-sync"></i>

                            Restore Backup

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

</div>




{{-- <script>

document.getElementById('restoreBackupBtn').addEventListener('click', function(e){

    e.preventDefault();

    Swal.fire({

        title: 'Restore Backup?',

        text: 'Your current database will be replaced with the selected backup.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#f39c12',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Yes, Restore',

        cancelButtonText: 'Cancel'

    }).then((result)=>{

        if(result.isConfirmed){

            document.getElementById('restoreForm').submit();

        }

    });

});


document.querySelectorAll('.deleteBackupForm').forEach(function(form){

    form.addEventListener('submit', function(e){

        e.preventDefault();

        Swal.fire({

            title: 'Delete Backup?',

            text: 'This backup file will be permanently deleted.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Delete',

            cancelButtonText: 'Cancel'

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});

</script> --}}

@endsection