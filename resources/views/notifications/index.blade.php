@extends('layouts.app')

@section('title','Notifications')

@section('content')

<div class="container-fluid">

    <div class="card shadow-sm">

        <div class="card-header d-flex justify-content-between align-items-center">

            <h4 class="mb-0">

                <i class="fas fa-bell me-2"></i>

                Notifications

            </h4>

            <form action="{{ route('notifications.clearRead') }}"
                  method="POST">

                @csrf
                @method('DELETE')

                <button
                    class="btn btn-danger">

                    <i class="fas fa-trash"></i>

                    Delete Read

                </button>

            </form>

        </div>

        <div class="card-body">

            <form method="GET"
                  class="mb-4">

                <div class="input-group">

                    <input
                        type="text"
                        class="form-control"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search notifications...">

                    <button
                        class="btn btn-primary">

                        <i class="fas fa-search"></i>

                    </button>

                </div>

            </form>

            @forelse($notifications as $notification)

                <div class="card mb-3 border-start border-4

                    @switch($notification->title)

                        @case('Low Stock')
                            border-warning
                            @break

                        @case('Medicine Expired')
                            border-danger
                            @break

                        @case('Medicine Expiring Soon')
                            border-warning
                            @break

                        @default
                            border-primary

                    @endswitch

                ">

                    <div class="card-body">

                        <div class="d-flex justify-content-between">

                            <div>

                                <h5>

                                    {{ $notification->title }}

                                    @if(!$notification->is_read)

                                        <span class="badge bg-primary">

                                            New

                                        </span>

                                    @endif

                                </h5>

                                <p class="mb-1">

                                    {{ $notification->message }}

                                </p>

                                <small class="text-muted">

                                    {{ $notification->created_at->diffForHumans() }}

                                </small>

                            </div>

                            <div>

                                @if(!$notification->is_read)

                                <form
                                    action="{{ route('notifications.read',$notification) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        class="btn btn-success btn-sm">

                                        Read

                                    </button>

                                </form>

                                @endif

                                <form
                                    action="{{ route('notifications.destroy',$notification) }}"
                                    method="POST"
                                    class="d-inline">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="btn btn-danger btn-sm">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center py-5">

                    <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>

                    <h4>

                        No Notifications

                    </h4>

                    <p class="text-muted">

                        Everything looks good.

                    </p>

                </div>

            @endforelse

            {{ $notifications->links() }}

        </div>

    </div>

</div>

@endsection