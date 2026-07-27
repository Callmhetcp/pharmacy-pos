@forelse($medicines as $medicine)

<tr>

    <td>{{ $loop->iteration }}</td>

    <td class="fw-semibold">

        {{ $medicine->name }}

    </td>

    <td>

        {{ $medicine->category->name ?? '-' }}

    </td>

    <td>

        {{ $medicine->quantity }}

    </td>

    <td>

        {{ $medicine->minimum_stock }}

    </td>

    <td>

        {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}

    </td>

    <td>

        @if($medicine->expiry_date < now())

            <span class="badge bg-dark">
                Expired
            </span>

        @elseif($medicine->quantity == 0)

            <span class="badge bg-danger">
                Out of Stock
            </span>
         @elseif($medicine->quantity <= $medicine->minimum_stock)

            <span class="badge bg-warning text-dark">
                Low Stock
            </span>

        @else

            <span class="badge bg-success">
                In Stock
            </span>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="7" class="text-center py-5 text-muted">

        <i class="fas fa-box-open fa-3x mb-3"></i>

        <br>

        No medicines found.

    </td>

</tr>

@endforelse

