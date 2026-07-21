<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Pharmacy Inventory</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/style.css') }}">

</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

<div class="container">

<a class="navbar-brand d-flex align-items-center" href="/">
    <img src="{{ asset('images/pharm_logo.png') }}"
         class="logo me-2"
         alt="Logo">

    <span>Pharmacy Inventory</span>
</a>

</div>

</nav>


<div class="container content">
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif

@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">

        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>
    </div>
@endif
<div class="card">

<div class="card-body p-4">

@yield('content')

</div>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const successAlert = document.querySelector('.alert-success');

    if (successAlert) {
        setTimeout(() => {
            bootstrap.Alert.getOrCreateInstance(successAlert).close();
        }, 3000);
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>