@extends('layouts.app')

@section('content')


<div class="container">


<div class="card shadow-lg border-0 rounded-4">


<div class="card-header text-white"
style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">


<h4 class="mb-0">

<i class="fas fa-sliders-h me-2"></i>

Create Stock Adjustment

</h4>


</div>



<div class="card-body">


<form method="POST"
action="{{ route('stock-adjustments.store') }}">

@csrf



<div class="mb-3">


<label class="form-label">

Medicine

</label>


<select name="medicine_id"
class="form-select">


<option value="">

Select Medicine

</option>


@foreach($medicines as $medicine)

<option value="{{ $medicine->id }}">

{{ $medicine->name }}

(Current: {{ $medicine->quantity }})

</option>


@endforeach


</select>


</div>




<div class="mb-3">


<label class="form-label">

Adjustment Type

</label>


<select name="type"
class="form-select">


<option value="increase">

Increase Stock

</option>


<option value="decrease">

Decrease Stock

</option>


</select>


</div>




<div class="mb-3">


<label class="form-label">

Quantity

</label>


<input type="number"
name="quantity"
class="form-control"
min="1">


</div>
@if($errors->has('quantity'))

<div class="alert alert-danger">

    {{ $errors->first('quantity') }}

</div>

@endif




<div class="mb-3">


<label class="form-label">

Reason

</label>


<textarea name="reason"
class="form-control"
rows="3"
placeholder="Damaged, expired, stock count correction..."></textarea>


</div>

<div class="mb-3">

    <label class="form-label">
        Notes (Optional)
    </label>

    <textarea
        name="notes"
        class="form-control"
        rows="3"
        placeholder="Add additional explanation...">{{ old('notes') }}</textarea>

</div>




<button class="btn btn-primary">

<i class="fas fa-save"></i>

Save Adjustment

</button>



<a href="{{ route('stock-adjustments.index') }}"
class="btn btn-secondary">

Back

</a>



</form>


</div>


</div>


</div>


@endsection