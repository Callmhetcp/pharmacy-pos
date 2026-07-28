@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h2 class="fw-bold">
                <i class="fas fa-cogs text-primary"></i>
                System Settings
            </h2>

            <p class="text-muted mb-0">
                Manage your pharmacy information and receipt settings.
            </p>

        </div>

    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert">
            </button>

        </div>
    @endif

    <div class="card shadow border-0">

        <div class="card-body">

            <form action="{{ route('settings.store') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Pharmacy Name
                        </label>

                        <input
                            type="text"
                            name="pharmacy_name"
                            class="form-control"
                            value="{{ old('pharmacy_name', $setting->pharmacy_name ?? '') }}"
                            required>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Phone Number
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="{{ old('phone', $setting->phone ?? '') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            value="{{ old('email', $setting->email ?? '') }}">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Currency Symbol
                        </label>

                        <input
                            type="text"
                            name="currency"
                            class="form-control"
                            value="{{ old('currency', $setting->currency ?? '₦') }}">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Address
                        </label>

                        <textarea
                            name="address"
                            rows="3"
                            class="form-control">{{ old('address', $setting->address ?? '') }}</textarea>

                    </div>

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            VAT / Tax (%)
                        </label>

                        <input
                            type="number"
                            step="0.01"
                            name="tax"
                            class="form-control"
                            value="{{ old('tax', $setting->tax ?? 0) }}">

                    </div>

                    <div class="col-md-8 mb-3">

                        <label class="form-label">
                            Receipt Footer
                        </label>

                        <textarea
                            name="receipt_footer"
                            rows="2"
                            class="form-control">{{ old('receipt_footer', $setting->receipt_footer ?? '') }}</textarea>

                    </div>

                    <div class="col-md-6 mb-4">

                        <label class="form-label">
                            Pharmacy Logo
                        </label>

                        <input
                            type="file"
                            name="logo"
                            class="form-control">

                    </div>

                    @if(!empty($setting?->logo))

                        <div class="col-md-6 mb-4 text-center">

                            <label class="form-label d-block">
                                Current Logo
                            </label>

                            <img
                                src="{{ asset('storage/'.$setting->logo) }}"
                                class="img-thumbnail"
                                style="max-height:120px;">

                        </div>

                    @endif

                </div>

                <div class="text-end">

                    <button
                        class="btn btn-primary">

                        <i class="fas fa-save"></i>

                        Save Settings

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection