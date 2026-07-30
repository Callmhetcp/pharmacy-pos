@forelse($notifications as $notification)

<div class="dropdown-item p-0 {{ !$notification->is_read ? 'bg-light' : '' }}">

    <div class="d-flex align-items-start">

        {{-- Mark as Read --}}
        <form action="{{ route('notifications.read', $notification) }}"
              method="POST"
              class="flex-grow-1">

            @csrf
            @method('PATCH')

            <button
                type="submit"
                class="btn w-100 text-start border-0 bg-transparent p-3">

                <div class="d-flex">

                    <div class="me-3">

                        @switch($notification->title)

                            @case('Low Stock')

                                <i class="fas fa-box-open text-warning fs-4"></i>

                                @break

                            @case('Medicine Expiring Soon')

                                <i class="fas fa-hourglass-half text-warning fs-4"></i>

                                @break

                            @case('Medicine Expired')

                                <i class="fas fa-skull-crossbones text-danger fs-4"></i>

                                @break

                            @default

                                <i class="fas fa-bell text-primary fs-4"></i>

                        @endswitch

                    </div>

                    <div class="flex-grow-1">

                        <div>

                            {{ $notification->title }}

                            @if(!$notification->is_read)

                                <span class="badge bg-primary ms-1">

                                    New

                                </span>

                            @endif

                        </div>

                        @if($notification->medicine)

                            <small class="text-primary">

                                {{ $notification->medicine->name }}

                            </small>

                            <br>

                        @endif

                        <small class="text-muted">

                            {{ $notification->message }}

                        </small>

                        <br>

                        <small class="text-secondary">

                            {{ $notification->created_at->diffForHumans() }}

                        </small>

                    </div>

                </div>

            </button>

        </form>

        {{-- Delete Notification --}}
        <form action="{{ route('notifications.destroy', $notification) }}"
              method="POST"
              class="deleteNotificationForm">

            @csrf
            @method('DELETE')

            <button
                type="submit"
                class="btn btn-sm btn-outline-danger delete-notification">

                <i class="fas fa-trash"></i>

            </button>

        </form>

    </div>

</div>

@empty

<div class="text-center py-5">

    <i class="fas fa-bell-slash fa-3x text-muted mb-2"></i>

    <div>

        You're all caught up.

    </div>

</div>


@endforelse
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.addEventListener("submit", function(e){

        const form = e.target.closest(".deleteNotificationForm");

        if(!form) return;

        e.preventDefault();

        Swal.fire({

            title: "Delete Notification?",

            text: "This notification will be permanently deleted.",

            icon: "warning",

            showCancelButton: true,

            confirmButtonColor: "#d33",

            cancelButtonColor: "#3085d6",

            confirmButtonText: "Yes, delete it!"

        }).then((result)=>{

            if(result.isConfirmed){

                form.submit();

            }

        });

    });

});
</script>