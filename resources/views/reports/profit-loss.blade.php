@extends('layouts.app')

@section('title','Profit & Loss Report')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">

                <i class="fas fa-chart-line text-success"></i>

                Profit & Loss Report

            </h2>

            <p class="text-muted mb-0">

                Financial performance of your pharmacy.

            </p>

        </div>

        <a href="#"
           class="btn btn-danger">

            <i class="fas fa-file-pdf"></i>

            Export PDF

        </a>

    </div>

    {{-- Filter --}}

    <div class="card shadow-sm border-0 mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="row g-3">

                    <div class="col-md-5">

                        <label class="form-label">

                            From

                        </label>

                        <input
                            type="date"
                            name="from"
                            value="{{ \Carbon\Carbon::parse($from)->format('Y-m-d') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-5">

                        <label class="form-label">

                            To

                        </label>

                        <input
                            type="date"
                            name="to"
                            value="{{ \Carbon\Carbon::parse($to)->format('Y-m-d') }}"
                            class="form-control">

                    </div>

                    <div class="col-md-2 d-grid">

                        <button
                            class="btn btn-primary mt-md-4">

                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    {{-- Summary Cards --}}

    <div class="row g-4 mb-4">

        <div class="col-lg-3">

            <div class="card border-0 shadow h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Revenue

                    </small>

                    <h3 class="fw-bold text-primary">

                        ₦{{ number_format($totalSales,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Cost of Goods Sold

                    </small>

                    <h3 class="fw-bold text-danger">

                        ₦{{ number_format($cogs,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Gross Profit

                    </small>

                    <h3 class="fw-bold text-success">

                        ₦{{ number_format($grossProfit,2) }}

                    </h3>

                </div>

            </div>

        </div>

        <div class="col-lg-3">

            <div class="card border-0 shadow h-100">

                <div class="card-body">

                    <small class="text-muted">

                        Net Profit

                    </small>

                    <h3 class="fw-bold {{ $netProfit >= 0 ? 'text-success' : 'text-danger' }}">

                        ₦{{ number_format($netProfit,2) }}

                    </h3>

                </div>

            </div>

        </div>

    </div>
    <div class="row g-4">

    <div class="col-lg-8">

        <div class="card shadow border-0">

            <div class="card-header bg-white fw-bold">

                Revenue vs Expenses vs Profit

            </div>

            <div class="card-body">

                <canvas id="profitChart" height="120"></canvas>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card shadow border-0">

            <div class="card-header bg-white fw-bold">

                Expense Categories

            </div>

            <div class="card-body">

                <canvas id="expenseChart"></canvas>

            </div>

        </div>

    </div>

</div>

<div class="card shadow border-0 mt-4">

    <div class="card-header bg-white fw-bold">

        Financial Summary

    </div>

    <div class="card-body p-0">

        <table class="table table-striped mb-0">

            <tbody>

                <tr>

                    <th>Total Revenue</th>

                    <td class="text-end">

                        ₦{{ number_format($totalSales,2) }}

                    </td>

                </tr>

                <tr>

                    <th>Cost of Goods Sold</th>

                    <td class="text-end">

                        ₦{{ number_format($cogs,2) }}

                    </td>

                </tr>

                <tr>

                    <th>Gross Profit</th>

                    <td class="text-end fw-bold text-success">

                        ₦{{ number_format($grossProfit,2) }}

                    </td>

                </tr>

                <tr>

                    <th>Operating Expenses</th>

                    <td class="text-end">

                        ₦{{ number_format($totalExpenses,2) }}

                    </td>

                </tr>

                <tr class="table-success">

                    <th>Net Profit</th>

                    <th class="text-end">

                        ₦{{ number_format($netProfit,2) }}

                    </th>

                </tr>

            </tbody>

        </table>

    </div>

</div>

</div>
@push('scripts')

<script>

new Chart(document.getElementById('profitChart'),{

type:'bar',

data:{

labels:@json($chartLabels),

datasets:[

{

label:'Revenue',

data:@json($salesChart)

},

{

label:'Expenses',

data:@json($expenseChart)

},

{

label:'Profit',

data:@json($profitChart)

}

]

}

});



new Chart(document.getElementById('expenseChart'),{

type:'doughnut',

data:{

labels:[

@foreach($expenseCategories as $category)

"{{ $category->category->name }}",

@endforeach

],

datasets:[{

data:[

@foreach($expenseCategories as $category)

{{ $category->total }},

@endforeach

]

}]

}

});

</script>

@endpush
@endsection