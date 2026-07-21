<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Category Management</title>
</head>
<body>

     @extends('layouts.app')

    @section('content')

   <x-form-card title="Categories Management">

       <form action="/categories" method="POST">
   
           @csrf
   
           <label for="">Category Name</label>
           <input type="text" name="name"><br><br>
   
           <label for="">Description</label><br>
           <textarea name="description" id=""></textarea><br><br>
   
           <button type="submit" class="btn btn-primary btn-lg px-5">
               Save Category
           </button>
       </form>
   </x-form-card>

    <x-table-card title="Categories">

    </x-table-card>
    <table>
        <tr>
            <th>S/N</th>
            <th>Name</th>
            <th>Description</th>
            <th>Action</th>
        </tr>
        
            @foreach ($categories as $category )
            <tr>
                <td>{{ $loop->iteration}}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description }}</td>
                <td>
                    <a href="/categories/{{ $category->id }}/edit"  class="btn btn-warning btn-sm">Edit</a><br>
                    <form action="/categories/{{ $category->id }}" method="POST">

                        @csrf
                        @method('DELETE')

                        <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this category?')"
                        class="btn btn-danger btn-sm">
                            Delete
                        </button>

                    </form>
                </td>
            </tr>
            @endforeach
        </table>
        @endsection

</body>
</html>