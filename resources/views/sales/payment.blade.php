<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-success text-white">

        <h5 class="mb-0">

            <i class="fas fa-credit-card me-2"></i>

            Payment

        </h5>

    </div>

    <div class="card-body">

       <form 
            id="checkoutForm"
            action="{{ route('sales.store') }}"
            method="POST">

            @csrf

           

            <div class="mb-3">

                <label class="form-label fw-semibold">

                    Payment Method

                </label>

                <input
                type="hidden"
                name="draft_id"
                id="checkoutDraftId"
                value="{{ $currentDraft->id ?? '' }}">
                <select
                    name="payment_method"
                    class="form-select form-select-lg">

                    <option value="Cash">Cash</option>

                    <option value="POS">POS</option>

                    <option value="Transfer">Bank Transfer</option>

                    <option value="Credit">Credit</option>

                </select>

            </div>

            <div class="mb-3">

                <label class="form-label fw-semibold">

                    Amount Paid

                </label>

                <input
                    type="number"
                    id="amount_paid"
                    name="amount_paid"
                    class="form-control form-control-lg"
                    step="0.01"
                    min="0"
                    placeholder="0.00"
                    required>

            </div>

            <div class="mb-3">

                <label class="form-label fw-semibold">

                    Change

                </label>

                <input
                    type="text"
                    id="change"
                    class="form-control form-control-lg bg-light text-success fw-bold"
                    value="₦0.00"
                    readonly>

            </div>

            <button
                type="submit"
                id="completeSale"
                class="btn btn-success btn-lg w-100"
                disabled>

                <i class="fas fa-check-circle me-2"></i>

                Complete Sale

            </button>

        </form>

    </div>

</div>