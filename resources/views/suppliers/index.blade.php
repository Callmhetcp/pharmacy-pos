<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Supplier Management</title>
</head>
<body>

    @extends('layouts.app')
    @section('content')

    <x-form-card title="Suppliers Management">
        <form action="/suppliers" method="POST">
            @csrf
    
            <label for="">Supplier Name</label>
            <input type="text" name="name"><br><br>
    
            <label for="">Address</label>
           <textarea name="address" id=""></textarea><br><br>
    
            <label for="">Phone Number</label>
            <input type="tel" name="phone_number"><br><br>
    
            <button type="submit" class="btn btn-primary">
                Save Supplier
            </button>
        </form>

    </x-form-card>
    

    
    <x-table-card title="Suppliers">

        <table>
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Action</th>
            </tr>
            @foreach ($suppliers as $supplier )
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->address }}</td>
                <td>{{ $supplier->phone_number }}</td>
                <td>
                    <a href="/suppliers/{{ $supplier->id }}/edit" class="btn btn-warning btn-sm">Edit</a><br><br>
                       <form action="/suppliers/{{ $supplier->id }}" method="POST">

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