@extends('layouts.app')

@section('content')


<div class="container">


<div class="card shadow-lg border-0 rounded-4">


<div class="card-header text-white"
style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">


<h4 class="mb-0">

<i class="fas fa-edit me-2"></i>

Edit Stock Adjustment

</h4>


</div>



<div class="card-body">


<form method="POST"
action="{{ route('stock-adjustments.update',$stockAdjustment->id) }}">

@csrf

@method('PUT')



<div class="mb-3">

<label class="form-label">

Medicine

</label>


<select name="medicine_id"
class="form-select">


@foreach($medicines as $medicine)

<option value="{{ $medicine->id }}"
{{ $stockAdjustment->medicine_id == $medicine->id ? 'selected' : '' }}>


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


<option value="increase"
{{ $stockAdjustment->type == 'increase' ? 'selected' : '' }}>

Increase Stock

</option>



<option value="decrease"
{{ $stockAdjustment->type == 'decrease' ? 'selected' : '' }}>

Decrease Stock

</option>


</select>


</div>




<div class="mb-3">

<label class="form-label">

Quantity

</label>


<input
type="number"
name="quantity"
class="form-control"
value="{{ $stockAdjustment->quantity }}"
min="1">


</div>




<div class="mb-3">

<label class="form-label">

Reason

</label>


<input
type="text"
name="reason"
class="form-control"
value="{{ $stockAdjustment->reason }}">


</div>




<div class="mb-3">

<label class="form-label">

Notes

</label>


<textarea
name="notes"
class="form-control"
rows="3">{{ $stockAdjustment->notes }}</textarea>


</div>




<button class="btn btn-primary">

<i class="fas fa-save"></i>

Update Adjustment

</button>



<a href="{{ route('stock-adjustments.index') }}"
class="btn btn-secondary">

Cancel

</a>



</form>


</div>


</div>


</div>


@endsection