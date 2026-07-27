<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

        <h5 class="mb-0">
            <i class="fas fa-shopping-cart me-2"></i>
            Shopping Cart
        </h5>

        <button
            type="button"
            id="clearDraft"
            class="btn btn-light btn-sm">

            <i class="fas fa-trash me-1"></i>

            Clear Draft

        </button>

    </div>

    <div class="card-body p-0">

        <div class="table-responsive">

            <table class="table table-hover align-middle mb-0">

                <thead class="table-light">

                    <tr>

                        <th>Medicine</th>
                        <th width="100">Qty</th>
                        <th width="150">Price</th>
                        <th width="150">Subtotal</th>
                        <th width="80">Action</th>

                    </tr>

                </thead>

                <tbody id="cartTable">

                    @forelse($currentDraft->items as $item)

                    <tr>

                        <td>{{ $item->medicine->name }}</td>

                        <td>{{ $item->quantity }}</td>

                        <td>₦{{ number_format($item->unit_price,2) }}</td>

                        <td>₦{{ number_format($item->subtotal,2) }}</td>

                        <td>

                            <button
                                class="btn btn-danger btn-sm remove-item"
                                data-id="{{ $item->id }}">

                                <i class="fas fa-trash"></i>

                            </button>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="5" class="text-center py-5">

                            Cart is Empty

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>