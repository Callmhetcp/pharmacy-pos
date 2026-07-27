@forelse($ledger as $movement)

<tr>

    <td>
        {{ $movement->created_at->format('d M Y') }}
        <br>
        <small class="text-muted">
            {{ $movement->created_at->format('h:i A') }}
        </small>
    </td>


    <td>
        {{ $movement->medicine->name ?? 'N/A' }}
    </td>


    <td>
        {{ $movement->reference_number }}
    </td>


    <td>
        {{-- Your movement badge here --}}
        {{ $movement->type }}
    </td>


    {{-- Stock In --}}
    <td class="text-success fw-bold text-center">

        @if($movement->quantity_in > 0)

            +{{ number_format($movement->quantity_in) }}

        @else

            -

        @endif

    </td>


    {{-- Stock Out --}}
    <td class="text-danger fw-bold text-center">

        @if($movement->quantity_out > 0)

            -{{ number_format($movement->quantity_out) }}

        @else

            -

        @endif

    </td>


    <td class="text-center fw-bold">

        {{ number_format($movement->balance) }}

    </td>


    <td>

        {{ $movement->user->name ?? 'System' }}

    </td>


</tr>


@empty

<tr>

    <td colspan="8" class="text-center py-5">

        No stock movements found.

    </td>

</tr>

@endforelse