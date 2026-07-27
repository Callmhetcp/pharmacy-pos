<div class="card border-0 shadow-sm mb-4">

    <div class="card-header bg-info text-white">

        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>
            Customer
        </h5>

    </div>

    <div class="card-body">

        <select
            id="customerSelect"
            name="customer_id"
            class="form-select form-select-lg"
            form="checkoutForm">

            <option value="">Select Customer</option>

            @foreach($customers as $customer)

                <option value="{{ $customer->id }}">
                    {{ $customer->name }}
                </option>

            @endforeach

        </select>

    </div>

</div>