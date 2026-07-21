@extends('layouts.app')

 @section('content')

 <x-form-card title="Edit Suppliers">
     <form action="/suppliers/{{ $supplier->id }}" method="POST">
         @csrf
         @method('PUT')
        
         <label for="">Suppliers Name</label>
         <input type="text" 
         name="name" 
         value="{{ $supplier->name }}">
        
         <label for="">Address</label>
         <input type="text"
          name="address" 
          value="{{ $supplier->address }}">
        
          <label for="">Phone Number</label>
         <input type="tel" 
         name="phone_number" 
         value="{{ $supplier->phone_number }}">
     
         <button type="submit" class="btn btn-primary" >
             Update Supplier
         </button>
     
     </form>

 </x-form-card>

@endsection