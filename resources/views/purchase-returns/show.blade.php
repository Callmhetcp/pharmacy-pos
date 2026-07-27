@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
             style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">

                    <i class="fas fa-undo-alt me-2"></i>

                    Purchase Return Details

                </h4>

                <small class="opacity-75">

                    {{ $purchaseReturn->return_number }}

                </small>

            </div>

            <div class="d-flex gap-2">

                <a href="{{ route('purchase-returns.index') }}"
                   class="btn btn-light">

                    <i class="fas fa-arrow-left me-1"></i>

                    Back

                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-4">

                    <strong>Return Number</strong>

                    <p>{{ $purchaseReturn->return_number }}</p>

                </div>

                <div class="col-md-4">

                    <strong>Purchase No.</strong>

                    <p>{{ $purchaseReturn->purchase->purchase_number }}</p>

                </div>

                <div class="col-md-4">

                    <strong>Return Date</strong>

                    <p>{{ $purchaseReturn->return_date->format('d M Y') }}</p>

                </div>

                <div class="col-md-4">

                    <strong>Supplier</strong>

                    <p>{{ $purchaseReturn->supplier->name }}</p>

                </div>

                <div class="col-md-4">

                    <strong>Reason</strong>

                    <p>{{ $purchaseReturn->reason }}</p>

                </div>

                <div class="col-md-4">

                    <strong>Status</strong>

                    <p>

                        @if($purchaseReturn->status == 'Completed')

                            <span class="badge bg-success">

                                Completed

                            </span>

                        @elseif($purchaseReturn->status == 'Pending')

                            <span class="badge bg-warning text-dark">

                                Pending

                            </span>

                        @else

                            <span class="badge bg-danger">

                                Cancelled

                            </span>

                        @endif

                    </p>

                </div>

            </div>

            <div class="table-responsive">

                <table class="table table-bordered align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>#</th>

                            <th>Medicine</th>

                            <th class="text-center">Quantity</th>

                            <th class="text-end">Cost Price</th>

                            <th class="text-end">Subtotal</th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($purchaseReturn->items as $item)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                {{ $item->medicine->name }}

                            </td>

                            <td class="text-center">

                                {{ number_format($item->quantity) }}

                            </td>

                            <td class="text-end">

                                ₦{{ number_format($item->cost_price,2) }}

                            </td>

                            <td class="text-end fw-bold">

                                ₦{{ number_format($item->subtotal,2) }}

                            </td>

                        </tr>

                        @endforeach

                    </tbody>

                    <tfoot>

                        <tr>

                            <th colspan="4" class="text-end">

                                Total Amount

                            </th>

                            <th class="text-end text-danger">

                                ₦{{ number_format($purchaseReturn->total_amount,2) }}

                            </th>

                        </tr>

                    </tfoot>

                </table>

            </div>

            <div class="row mt-4">

                <div class="col-md-6">

                    <strong>Processed By</strong>

                    <p>{{ $purchaseReturn->user->name ?? 'System' }}</p>

                </div>

                <div class="col-md-6 text-end">

                    <strong>Created At</strong>

                    <p>{{ $purchaseReturn->created_at->format('d M Y h:i A') }}</p>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection