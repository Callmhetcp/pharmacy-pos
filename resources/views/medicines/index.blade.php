<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medicine Management</title>
</head>
<body>
    @extends('layouts.app')

    @section('content')

    
    <x-form-card title="Medicine Mangement">

        <form action="/medicines" method="POST">
            @csrf
            
            <label for="">Medicine Name</label>
            <input type="text" name="name" class="form-control"><br><br>
    
            <label for="">Quantity</label>
            <input type="number" name="quantity"><br><br>
    
            <label for="">Cost Price</label>
            <input type="number" name="cost_price"><br><br>
    
             <label for="">Selling Price</label>
            <input type="number" name="selling_price"><br><br>
    
            <label for="">Expiry Date</label>
            <input type="date" name="expiry_date"><br><br>
    
            <label for="">Category</label>
            <select name="category_id" id="">
                @foreach ( $categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
                
    
            </select><br><br>
    
            <button type="submit" class="btn btn-primary">
                Save Medicine
            </button>
    
    
        </form>
    </x-form-card>

    

    
    <x-table-card title="Medicines">

        <table>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Cost Price</th>
                <th>Selling Price</th>
                <th>Expiry Date</th>
                <th>Category</th>
                <th>Action</th>
            </tr>
    
            @foreach ($medicines as $medicine)
    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $medicine->name }}</td>
                    <td>{{ $medicine->quantity }}</td>
                    <td>{{$medicine->cost_price }}</td>
                    <td>{{ $medicine->selling_price }}</td>
                    <td>{{ $medicine->expiry_date }}</td>
                    <td>{{ $medicine->category->name }}</td>
                    <td>
                        <a href="/medicines/{{ $medicine->id }}/edit"  class="btn btn-warning btn-sm">Edit</a>
                        <form action="/medicines/{{ $medicine->id }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this supplier?')"
                        class="btn btn-danger btn-sm">
                            Delete
                        </button>
                        </form>
                    </td>
                </tr>
                
            @endforeach
        </table>
    </x-table-card>
    @endsection
    
</body>
</html>