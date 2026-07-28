@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">

        <i class="fas fa-calendar-times text-danger"></i>

        Expiry Report

    </h3>

    <div class="card shadow">

        <div class="card-body table-responsive">

            <table class="table table-hover">

                <thead>

                    <tr>

                        <th>Medicine</th>
                        <th>Batch</th>
                        <th>Quantity</th>
                        <th>Expiry Date</th>
                        <th>Days Left</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($items as $item)

                    @php

                        $days = now()->diffInDays($item->expiry_date, false);

                    @endphp

                    <tr>

                        <td>{{ optional($item->medicine)->name }}</td>

                        <td>{{ $item->batch_number }}</td>

                        <td>{{ $item->quantity }}</td>

                        <td>{{ $item->expiry_date }}</td>

                        <td>{{ $days }}</td>

                        <td>

                            @if($days < 0)

                                <span class="badge bg-danger">

                                    Expired

                                </span>

                            @elseif($days <= 30)

                                <span class="badge bg-warning text-dark">

                                    Expiring Soon

                                </span>

                            @elseif($days <= 90)

                                <span class="badge bg-info">

                                    90 Days

                                </span>

                            @else

                                <span class="badge bg-success">

                                    Good

                                </span>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center">

                            No medicines found.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection