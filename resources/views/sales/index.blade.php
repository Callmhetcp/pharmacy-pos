@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">

            <h3 class="mb-0">

                <i class="fas fa-cash-register me-2"></i>

                New Sale

            </h3>
            
            <a href="{{ route('sales-returns.index') }}"
                class="btn btn-warning">

                <i class="fas fa-undo me-1"></i>

                Sales Returns

            </a>
            <a href="{{ route('sales.history') }}"
               class="btn btn-light">

                <i class="fas fa-clock-rotate-left me-2"></i>

                Sales History

            </a>


        </div>
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">

            <h5 class="mb-0">

                <i class="fas fa-file-invoice"></i>

                Open Draft Sales

            </h5>


            <button 
                class="btn btn-success btn-sm"
                id="newDraft">

                <i class="fas fa-plus"></i>

                New Draft

            </button>

            <button
                type="button"
                id="holdDraft"
                class="btn btn-warning">

                <i class="fas fa-pause me-2"></i>
                Hold Draft

            </button>


        </div>

        <div class="card-body">

            <div class="row">

                {{-- Draft Invoice Panel --}}
                <div class="col-lg-3">

                   @include('sales.drafts', ['drafts' => $drafts])

                </div>

                {{-- Main Sales Area --}}
                <div class="col-lg-9">

                    @include('sales.customer')

                    <hr>

                   @include('sales.medicine', [
                        'medicines' => $medicines,
                        'currentDraft' => $currentDraft
                    ])

                    <hr>

                    @include('sales.cart')

                    <hr>

                    <div class="row">

                        <div class="col-lg-6">

                            @include('sales.summary')

                        </div>

                        <div class="col-lg-6">

                            @include('sales.payment')

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


@include('sales.scripts')
@endsection

