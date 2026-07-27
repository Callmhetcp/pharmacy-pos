<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">
            <i class="fas fa-file-invoice-dollar me-2"></i>
            Order Summary
        </h5>

    </div>

    <div class="card-body">

        <table class="table table-borderless mb-4">

            <tr>

                <th>Total Items</th>

                <td class="text-end">

                    <span id="totalItems">
                        {{ $currentDraft->items->count() }}
                    </span>

                </td>

            </tr>

            <tr>

                <th>Total Quantity</th>

                <td class="text-end">

                    <span id="totalQuantity">
                        {{ $currentDraft->items->sum('quantity') }}
                    </span>

                </td>

            </tr>

            <tr>

                <th>Subtotal</th>

                <td class="text-end">

                    ₦<span id="subTotal">
                        {{ number_format($currentDraft->items->sum('subtotal'), 2) }}
                    </span>

                </td>

            </tr>

            <tr>

                <th>Discount</th>

                <td class="text-end">

                    ₦<span id="discount">0.00</span>

                </td>

            </tr>

            <tr>

                <th>Tax</th>

                <td class="text-end">

                    ₦<span id="tax">0.00</span>

                </td>

            </tr>

        </table>

        <hr>

        <div class="text-center">

            <small class="text-muted">
                GRAND TOTAL
            </small>

            <h1
                class="display-5 fw-bold text-success"
                id="grandTotal">

                ₦{{ number_format($currentDraft->items->sum('subtotal'), 2) }}

            </h1>

        </div>

    </div>

</div>