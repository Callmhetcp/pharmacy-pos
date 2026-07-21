<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Customer Management</title>
</head>
<body>

    @extends('layouts.app')

    @section('content')

    
        <x-form-card title="Customer Management">

            <h1>Customer Management</h1>
        
            <form action="/customers" method="POST">
        
                @csrf
        
                <label for="">Customers Name</label>
                <input type="text" name="name"><br><br>
        
                <label for="">Phone Number</label>
                <input type="tel" name="phone_number"><br><br>
        
                <label for="">Address</label>
                <textarea type="text" name="address" id=""></textarea><br><br>
        
                <button type="submit" class="btn btn-primary btn-lg px-5">Submit Customer</button>
        
            </form>
        </x-form-card>


    <x-table-card title="Customers">

        <table>
            <tr>
                <th>S/N</th>
                <th>Customers Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
            
            @foreach ($customers as $customer )
    
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->phone_number }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>
                        <a href="/customers/{{ $customer->id }}/edit" class="btn btn-warning btn-sm">Edit</a>
                    <form action="/customers/{{ $customer->id }}" method="POST">

                            @csrf
                            @method('DELETE')

                            <button type="submit" 
                            onclick="return confirm('Are you sure you want to delete this customer?')"
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