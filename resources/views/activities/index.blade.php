@extends('layouts.app')


@section('content')

<div class="container-fluid">


<div class="card shadow">


<div class="card-header">

<h4>
<i class="fas fa-history"></i>
Activity Logs
</h4>


</div>



<div class="card-body">


<form method="GET">


<div class="row g-2 mb-3">


<div class="col-md-3">

<input 
type="text"
name="search"
value="{{ $search }}"
class="form-control"
placeholder="Search..."
>

</div>



<div class="col-md-2">


<select name="action" class="form-control">


<option value="">
All Actions
</option>


@foreach($actions as $item)

<option 
value="{{ $item }}"
{{ $action == $item ? 'selected':'' }}
>

{{ $item }}

</option>


@endforeach


</select>


</div>




<div class="col-md-2">


<select name="module" class="form-control">


<option value="">
All Modules
</option>


@foreach($modules as $item)

<option 
value="{{ $item }}"
{{ $module == $item ? 'selected':'' }}
>

{{ $item }}

</option>


@endforeach


</select>


</div>





<div class="col-md-2">


<input 
type="date"
name="from"
value="{{ $from }}"
class="form-control"
>


</div>





<div class="col-md-2">


<input 
type="date"
name="to"
value="{{ $to }}"
class="form-control"
>


</div>





<div class="col-md-1">


<button class="btn btn-primary">

Filter

</button>


</div>


</div>


<a href="{{ route('activities.index') }}"
class="btn btn-secondary btn-sm">

Clear Filters

</a>


</form>





<div class="table-responsive">


<table class="table table-bordered table-striped">


<thead>

<tr>

<th>#</th>

<th>User</th>

<th>Action</th>

<th>Module</th>

<th>Description</th>

<th>Date</th>


</tr>


</thead>



<tbody>


@forelse($activities as $activity)


<tr>


<td>
{{ $loop->iteration }}
</td>


<td>

{{ $activity->user->name ?? 'System' }}

</td>



<td>

<span class="badge bg-info">

{{ $activity->action }}

</span>

</td>



<td>

{{ $activity->module }}

</td>



<td>

{{ $activity->description }}

</td>



<td>

{{ $activity->created_at->format('d M Y H:i') }}

</td>



</tr>


@empty


<tr>

<td colspan="6" class="text-center">

No activity found.

</td>

</tr>


@endforelse



</tbody>


</table>


</div>


{{ $activities->links() }}


</div>


</div>


</div>


@endsection