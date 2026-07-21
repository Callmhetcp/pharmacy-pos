<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sales Management</title>
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="container mt-4">

        <div class="card-header bg-primary text-white">
            <h3>New Sale</h3>

        </div>

        <div class="card-body">
            {{-- Customer Section --}}
            
            <form action="{{ route('sales.addToCart') }}" method="POST">
                @csrf
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="" class="form-label"><strong>Customer</strong></label>

                    <select name="customer_id" id="" class="form-control">
                        <option value="">Select Customer</option>

                        @foreach ($customers as $customer )

                        <option value="{{ $customer->id }}">
                            {{ $customer->name }}
                        </option>
                            
                        @endforeach
                    </select>

                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">&nbsp;</label>
                    <button class="btn btn-success w-100">
                        +New Customer

                    </button>

                </div>
                <div class="col-md-3">
                    <label for="" class="form-label">&nbsp;</label>
                    <div class="form-check mt-2">
                        <input type="checkbox" class="form-check-input">
                        <label for="" class="form-check-label">
                            Walk-in Customer
                        </label>

                    </div>
                </div>

            </div>
            <hr>
            {{-- Medicine Section --}}

               

                <div class="row mb-4">
    
                    <div class="col-md-6">
                        <label for="" class="form-label"><strong>Medicine</strong></label>
                        <select name="medicine_id" id="" class="form-control">
                            <option value="">Select Medicine</option>
                            @foreach ($medicines as $medicine )
                            <option value="{{ $medicine->id }}">
                                {{ $medicine->name }}
                            </option>
                                
                            @endforeach
    
                        </select>
                    </div>
    
                    <div class="col-md-3">
                        <label for="" class="form-label"><strong>Quantity</strong></label>
                        <input type="number"
                            name="quantity"
                            class="form-control"
                            value="1"
                            min="1">
    
                    </div>
                    <div class="col-md-3">
                        <label for="" class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" type="submit">
                            Add to Cart
                        </button>

                        <a href="{{ route('sales.clearCart') }}" class="btn btn-danger">
                            Clear Cart
                        </a>
    
                    </div>
    
                </div>
            </form>
            
            <hr>
            {{-- Cart --}}
            <h4>Cart</h4>

            <table class="table table-bordered table-striped">

                <thead class="table-dark">
                    <tr>
                        <th>Medicine</th>
                        <th>Qty</th>
                        <th>Unit Price</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @if (count($cart) > 0)

                    @foreach ($cart as $item )
                        <tr>
                            <td>
                                {{ $item['name'] }}
                            </td>
                            <td>
                                {{ $item['quantity'] }}
                            </td>
                            <td>#{{ number_format($item['price'],2)  }}</td>
                            <td>
                                #{{ number_format($item['subtotal'],2) }}
                            </td>
                            <td>
                                Remove
                            </td>
                        </tr>

                        
                    @endforeach

                    @else


                    <tr>

                        <td colspan="5" class="text-center">
                            No medicine added yet.
                        </td>
                    </tr>
                        
                    @endif
                </tbody>

            </table>

            <div class="row mt-4">
                <div class="col-md-6">

                </div>
                <div class="col-md-6 text-end">
                    <h3>
                        Grand Total:
                        <span class="text-success">
                                #{{ number_format(collect($cart)->sum('subtotal'),2) }}
                        </span>
                    </h3>

                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-lg mt-2" type="submit">
                            Complete Sale
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>
    @endsection
    
</body>
</html>