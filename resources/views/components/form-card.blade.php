<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-header bg-primary text-white py-3">
                    <h2 class="mb-0">{{ $title }}</h2>
                </div>

                <div class="card-body p-4">
                    {{ $slot }}
                </div>

            </div>

        </div>
    </div>
</div>