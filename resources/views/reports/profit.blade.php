@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4">

        <i class="fas fa-chart-line text-success"></i>

        Profit Report

    </h3>

    <div class="row">

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body text-center">

                    <h6>Total Revenue</h6>

                    <h3 class="text-primary">

                        ₦{{ number_format($revenue,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body text-center">

                    <h6>Cost of Goods</h6>

                    <h3 class="text-danger">

                        ₦{{ number_format($cost,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body text-center">

                    <h6>Gross Profit</h6>

                    <h3 class="text-success">

                        ₦{{ number_format($profit,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-md-3">

            <div class="card shadow">

                <div class="card-body text-center">

                    <h6>Profit Margin</h6>

                    <h3 class="text-warning">

                        {{ number_format($margin,2) }}%

                    </h3>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection