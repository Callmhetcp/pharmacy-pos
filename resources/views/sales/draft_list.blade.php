@forelse($drafts as $draft)

<div class="list-group-item draft-item"
     data-id="{{ $draft->id }}">

    <div class="d-flex justify-content-between align-items-center">

        <div style="cursor:pointer" class="load-draft">

            <strong>{{ $draft->draft_number }}</strong>

            <br>

            <small class="text-muted">
                {{ $draft->items->count() }} Items
            </small>

        </div>

        <button
            class="btn btn-sm btn-danger delete-draft"
            data-id="{{ $draft->id }}">

            <i class="fas fa-trash"></i>

        </button>

    </div>

</div>

@empty

<div class="text-center p-4 text-muted">
    No open drafts
</div>

@endforelse