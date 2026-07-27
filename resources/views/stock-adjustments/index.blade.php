@extends('layouts.app')

@section('content')

<div class="container-fluid">


<div class="card shadow-lg border-0 rounded-4">


<div class="card-header text-white d-flex justify-content-between align-items-center"
style="background:linear-gradient(135deg,#0d6efd,#0b5ed7);">


<h4 class="mb-0">

<i class="fas fa-sliders-h me-2"></i>

Stock Adjustments

</h4>


<a href="{{ route('stock-adjustments.create') }}"
class="btn btn-light">

<i class="fas fa-plus"></i>

New Adjustment

</a>


</div>



<div class="card-body">


<div class="table-responsive">


<table class="table table-hover align-middle">


<thead class="table-primary">

<tr>

<th>Date</th>

<th>Medicine</th>

<th>Type</th>

<th>Quantity</th>

<th>Old Stock</th>

<th>New Stock</th>

<th>Reason</th>

<th>Note</th>

<th>User</th>

<th>Actions</th>

</tr>

</thead>


<tbody>


@forelse($adjustments as $adjustment)


<tr>


<td>

{{ $adjustment->created_at->format('d M Y') }}

</td>


<td>

{{ $adjustment->medicine->name ?? 'N/A' }}

</td>



<td>


@if($adjustment->type == 'increase')

<span class="badge bg-success">

Increase

</span>


@else

<span class="badge bg-danger">

Decrease

</span>


@endif


</td>



<td>

{{ number_format($adjustment->quantity) }}

</td>



<td>

{{ number_format($adjustment->old_quantity) }}

</td>



<td>

{{ number_format($adjustment->new_quantity) }}

</td>



<td>

{{ $adjustment->reason }}

</td>

<td>
    {{ $adjustment->notes ?? '-' }}

</td>



<td>

{{ $adjustment->user->name ?? 'System' }}

</td>

<td>

<div class="d-flex gap-2">

    <a href="{{ route('stock-adjustments.show', $adjustment->id) }}"
       class="btn btn-sm btn-info text-white">

        <i class="fas fa-eye"></i>

    </a>

    <a href="{{ route('stock-adjustments.edit', $adjustment->id) }}"
       class="btn btn-sm btn-primary">

        <i class="fas fa-edit"></i>

    </a>

    <form action="{{ route('stock-adjustments.destroy', $adjustment->id) }}"
          method="POST"
          class="delete-form">

        @csrf
        @method('DELETE')

        <button type="submit"
                class="btn btn-sm btn-danger">

            <i class="fas fa-trash"></i>

        </button>

    </form>

</div>
</td>


</tr>


@empty


<tr>

<td colspan="8" class="text-center py-5">

No adjustments found.

</td>

</tr>


@endforelse


</tbody>


</table>


</div>


<div class="mt-3 d-flex justify-content-end">

{{ $adjustments->links() }}

</div>


</div>


</div>


</div>

<script>

document.querySelectorAll('.delete-form').forEach(form => {


    form.addEventListener('submit', function(e){


        e.preventDefault();


        Swal.fire({

            title: 'Are you sure?',

            text: 'Deleting this adjustment will restore the previous stock quantity.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonColor: '#dc3545',

            cancelButtonColor: '#6c757d',

            confirmButtonText: 'Yes, delete it'


        }).then((result)=>{


            if(result.isConfirmed){

                form.submit();

            }


        });


    });


});


</script>


@endsection