
     @extends('layouts.app')

    @section('content')
        <x-form-card title="Edit Medicine">

            <form action="/medicines/{{ $medicine->id }}"method="POST">
            
                @csrf
                @method('PUT')
            
                <label for="">Name</label>
                <input type="text" name="name" value="{{ $medicine->name }}"><br><br>
            
                <label for="">Quantity</label>
                <input type="number" name="quantity" value="{{ $medicine->quantity }}"><br><br>
            
                <label for="">Cost Price</label>
                <input type="number" name="cost_price" value="{{ $medicine->cost_price }}"><br><br>
            
                <label for="">Selling Price</label>
                <input type="number" name="selling_price" value="{{ $medicine->selling_price }}"><br>
            
                <label for="">Expiry Date</label>
                <input type="date" name="expiry_date" value="{{ $medicine->expiry_date }}"><br><br>
            
                <label for="">Category</label>
                <select name="category_id" id="">
                    @foreach ($categories as $category )
                            <option value="{{ $category->id }}" {{ $medicine->category_id ==$category->id ? 'selected' : ''}}>
                                {{ $category->name }}
                            </option>
                        
                    @endforeach
            
                </select>
            
                <button type="submit" class="btn btn-primary">Update Medicine</button>
            
                
            
            </form>
    
        </x-form-card>
     @endsection