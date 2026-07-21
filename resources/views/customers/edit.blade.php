@extends('layouts.app')

    @section('content')


<x-form-card title="Edit Customer">
    <form action="/customers/{{ $customer->id }}" method="POST">
    
        @csrf
        @method('PUT')
    
        <label for="">Customers Name</label>
        <input type="text" name="name" value="{{ $customer->name }}">
    
        <label for="">Address</label>
        <input type="text" name="address" value="{{ $customer->address }}">
    
        <label for="">Phone Number</label>
        <input type="tel" name="phone_number" value="{{ $customer->phone_number }}">
    
        <button type="submit"  class="btn btn-primary">Update Customer</button>
    
    </form>

</x-form-card>
@endsection