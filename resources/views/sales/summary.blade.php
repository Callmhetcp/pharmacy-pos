<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">
            <i class="fas fa-file-invoice-dollar me-2"></i>
            Order Summary
        </h5>

    </div>

    <div class="card-body">

        <table class="table table-borderless mb-3">

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
                        {{ number_format($currentDraft->items->sum('subtotal'),2) }}
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
                <th>
                    VAT ({{ $setting->tax ?? 0 }}%)
                </th>
                <td class="text-end">
                    ₦<span id="tax">
                        {{ number_format(($currentDraft->items->sum('subtotal') * ($setting->tax ?? 0))/100,2) }}
                    </span>
                </td>
            </tr>

        </table>

        <hr>

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <small class="text-muted text-uppercase">
                    Grand Total
                </small>

                <h2
                    id="grandTotal"
                    class="fw-bold text-success mb-0">

                    ₦{{ number_format(
                        $currentDraft->items->sum('subtotal')
                        + (($currentDraft->items->sum('subtotal') * ($setting->tax ?? 0))/100),
                    2) }}

                </h2>

            </div>

            <div class="text-end">

                <small class="text-muted">

                    VAT Included

                </small>

            </div>

        </div>

    </div>

</div>