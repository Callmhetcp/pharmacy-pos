@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-lg rounded-4">

        <div class="card-header text-white d-flex justify-content-between align-items-center"
            style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">

            <div>

                <h4 class="mb-0">
                    <i class="fas fa-eye me-2"></i>
                    Stock Adjustment Details
                </h4>

                <small class="opacity-75">
                    View complete adjustment information
                </small>

            </div>

            <div>

                <a href="{{ route('stock-adjustments.edit', $stockAdjustment->id) }}"
                    class="btn btn-warning">

                    <i class="fas fa-edit me-1"></i>
                    Edit

                </a>

                <a href="{{ route('stock-adjustments.index') }}"
                    class="btn btn-light">

                    <i class="fas fa-arrow-left me-1"></i>
                    Back

                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <table class="table table-bordered">

                        <tr>

                            <th width="35%">Reference Number</th>

                            <td>
                                {{ $stockAdjustment->reference_number }}
                            </td>

                        </tr>

                        <tr>

                            <th>Medicine</th>

                            <td>
                                {{ $stockAdjustment->medicine->name ?? 'N/A' }}
                            </td>

                        </tr>

                        <tr>

                            <th>Adjustment Type</th>

                            <td>

                                @if($stockAdjustment->type == 'increase')

                                    <span class="badge bg-success">
                                        Increase
                                    </span>

                                @else

                                    <span class="badge bg-danger">
                                        Decrease
                                    </span>

                                @endif

                            </td>

                        </tr>

                        <tr>

                            <th>Quantity</th>

                            <td>

                                {{ number_format($stockAdjustment->quantity) }}

                            </td>

                        </tr>

                        <tr>

                            <th>Reason</th>

                            <td>

                                {{ $stockAdjustment->reason }}

                            </td>

                        </tr>

                        <tr>

                            <th>Notes</th>

                            <td>

                                {{ $stockAdjustment->notes ?? '-' }}

                            </td>

                        </tr>

                    </table>

                </div>

                <div class="col-md-6">

                    <table class="table table-bordered">

                        <tr>

                            <th width="35%">Old Stock</th>

                            <td>

                                {{ number_format($stockAdjustment->old_quantity) }}

                            </td>

                        </tr>

                        <tr>

                            <th>New Stock</th>

                            <td>

                                {{ number_format($stockAdjustment->new_quantity) }}

                            </td>

                        </tr>

                        <tr>

                            <th>Adjusted By</th>

                            <td>

                                {{ $stockAdjustment->user->name ?? 'System' }}

                            </td>

                        </tr>

                        <tr>

                            <th>Date</th>

                            <td>

                                {{ $stockAdjustment->created_at->format('d M Y') }}

                            </td>

                        </tr>

                        <tr>

                            <th>Time</th>

                            <td>

                                {{ $stockAdjustment->created_at->format('h:i A') }}

                            </td>

                        </tr>

                        <tr>

                            <th>Status</th>

                            <td>

                                <span class="badge bg-primary">

                                    Completed

                                </span>

                            </td>

                        </tr>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection