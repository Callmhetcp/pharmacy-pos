@extends('layouts.app')

@section('title','Expense Categories')

@section('content')

<div class="container-fluid">


    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="fw-bold">
                Expense Categories
            </h3>

            <small class="text-muted">
                Manage expense categories.
            </small>

        </div>


        <button
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#addCategoryModal">

            <i class="fas fa-plus-circle"></i>
            Add Category

        </button>

    </div>



    {{-- Search --}}
    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form method="GET">

                <div class="input-group">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search category..."
                        value="{{ request('search') }}">


                    <button class="btn btn-primary">

                        <i class="fas fa-search"></i>

                    </button>

                </div>

            </form>

        </div>

    </div>





    {{-- Table --}}
    <div class="card shadow">


        <div class="card-body p-0">


            <table class="table table-hover align-middle mb-0">


                <thead class="table-dark">

                    <tr>

                        <th>#</th>

                        <th>Category</th>

                        <th>Description</th>

                        <th>Status</th>

                        <th width="160">
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>


                @forelse($categories as $category)


                    <tr>


                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>
                            {{ $category->name }}
                        </td>


                        <td>
                            {{ $category->description ?? '-' }}
                        </td>


                        <td>


                            @if($category->status == "Active")

                                <span class="badge bg-success">
                                    Active
                                </span>

                            @else

                                <span class="badge bg-danger">
                                    Inactive
                                </span>

                            @endif


                        </td>



                        <td>


                            {{-- Edit --}}

                            <button
                                class="btn btn-warning btn-sm editCategoryBtn"

                                data-id="{{ $category->id }}"

                                data-name="{{ $category->name }}"

                                data-description="{{ $category->description }}"

                                data-bs-toggle="modal"

                                data-bs-target="#editCategoryModal">


                                <i class="fas fa-edit"></i>


                            </button>



                            {{-- Toggle Status --}}

                            <form
                                action="{{ route('expense-categories.destroy',$category) }}"
                                method="POST"
                                class="d-inline deleteCategoryForm">


                                @csrf
                                @method('DELETE')


                                <button
                                    class="btn btn-danger btn-sm">


                                    <i class="fas fa-power-off"></i>


                                </button>


                            </form>



                        </td>


                    </tr>



                @empty


                    <tr>

                        <td colspan="5"
                            class="text-center py-4">

                            No categories found.

                        </td>

                    </tr>


                @endforelse


                </tbody>


            </table>


        </div>



        <div class="card-footer">

            {{ $categories->links() }}

        </div>


    </div>



</div>





{{-- ===================================================
ADD CATEGORY MODAL
=================================================== --}}


<div class="modal fade"
     id="addCategoryModal"
     tabindex="-1">


<div class="modal-dialog">


<div class="modal-content">


<form action="{{ route('expense-categories.store') }}"
      method="POST">


@csrf


<div class="modal-header">


<h5 class="modal-title">
    Add Expense Category
</h5>


<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>


</div>



<div class="modal-body">


<div class="mb-3">


<label class="form-label">
    Category Name
</label>


<input
type="text"
name="name"
class="form-control"
required>


</div>




<div class="mb-3">


<label class="form-label">
    Description
</label>


<textarea
name="description"
class="form-control"
rows="3"></textarea>


</div>


</div>



<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancel

</button>


<button
class="btn btn-primary">

Save Category

</button>


</div>



</form>


</div>


</div>


</div>





{{-- ===================================================
EDIT CATEGORY MODAL
=================================================== --}}


<div class="modal fade"
     id="editCategoryModal"
     tabindex="-1">


<div class="modal-dialog">


<div class="modal-content">


<form
id="editCategoryForm"
method="POST">


@csrf

@method('PUT')



<div class="modal-header">


<h5 class="modal-title">
    Edit Expense Category
</h5>


<button
type="button"
class="btn-close"
data-bs-dismiss="modal">
</button>


</div>



<div class="modal-body">


<div class="mb-3">


<label class="form-label">
Category Name
</label>


<input
id="editName"
type="text"
name="name"
class="form-control"
required>


</div>



<div class="mb-3">


<label class="form-label">
Description
</label>


<textarea
id="editDescription"
name="description"
class="form-control"
rows="3"></textarea>


</div>



</div>



<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancel

</button>


<button
class="btn btn-success">

Update Category

</button>


</div>



</form>


</div>


</div>


</div>





@endsection





@section('scripts')


<script>


// Edit Category

document.querySelectorAll('.editCategoryBtn')
.forEach(button => {


button.addEventListener('click', function(){


document.getElementById('editName').value =
this.dataset.name;


document.getElementById('editDescription').value =
this.dataset.description ?? '';



document.getElementById('editCategoryForm').action =
"/expense-categories/" + this.dataset.id;



});


});




// SweetAlert Status Toggle

document.querySelectorAll('.deleteCategoryForm')
.forEach(form => {


form.addEventListener('submit', function(e){


e.preventDefault();



Swal.fire({

title:"Change Status?",

text:"Do you want to activate/deactivate this category?",

icon:"warning",

showCancelButton:true,

confirmButtonText:"Yes, continue",

cancelButtonText:"Cancel"


}).then((result)=>{


if(result.isConfirmed){

form.submit();

}


});


});


});


</script>


@endsection