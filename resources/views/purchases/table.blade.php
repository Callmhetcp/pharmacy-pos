 @forelse($purchases as $purchase)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <span class="fw-bold text-primary">

                                    {{ $purchase->purchase_number }}

                                </span>

                            </td>

                            <td>

                                <i class="fas fa-truck text-secondary me-1"></i>

                                {{ $purchase->supplier->company }}

                            </td>

                            <td>

                                {{ $purchase->invoice_number ?? 'N/A' }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}

                            </td>

                           <td class="text-end">
    ₦{{ number_format($purchase->grand_total,2) }}
</td>

<td class="text-end text-success">
    ₦{{ number_format($purchase->amount_paid,2) }}
</td>

<td class="text-end text-danger">
    ₦{{ number_format($purchase->balance,2) }}
</td>

<td class="text-center">

    @if($purchase->payment_status == 'Paid')

        <span class="badge bg-success">
            Paid
        </span>

    @elseif($purchase->payment_status == 'Partial')

        <span class="badge bg-warning text-dark">
            Partial
        </span>

    @else

        <span class="badge bg-danger">
            Unpaid
        </span>

    @endif

</td>

<td class="text-center">
    {{ $purchase->items->count() }}
</td>

                            <td class="text-center">

                                <div class="btn-group">

                                    <a href="{{ route('purchase.show',$purchase->id) }}"
                                       class="btn btn-info btn-sm"
                                       title="View">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a href="{{ route('purchase.edit',$purchase->id) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <a href="{{ route('purchase.receipt',$purchase->id) }}"
                                       class="btn btn-success btn-sm"
                                       title="Print Receipt">

                                        <i class="fas fa-print"></i>

                                    </a>

                                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm delete-btn">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>
                         @empty

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <i class="fas fa-cart-plus fa-4x text-muted mb-3 d-block"></i>

                                <h5 class="text-muted">

                                    No Purchase Records Found

                                </h5>

                                <p class="text-muted">

                                    Click <strong>New Purchase</strong> to record your first purchase.

                                </p>

                            </td>

                        </tr>

                        @endforelse
                        <script>
                            document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".delete-btn").forEach(button => {

        button.addEventListener("click", function () {

            const form = this.closest("form");

            Swal.fire({

                title: "Delete Purchase?",

                text: "This purchase will be permanently deleted.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#d33",

                cancelButtonColor: "#3085d6",

                confirmButtonText: "Yes, delete it!"

            }).then((result) => {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        });

    });

});
                        </script>