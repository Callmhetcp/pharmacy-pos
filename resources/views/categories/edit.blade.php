@extends('layouts.app')

    @section('content')

    <x-form-card title="Edit Category">
        <form action="/categories/{{ $category->id }}" method="POST">
            @csrf
            @method('PUT')

            <label for="">Category Name</label>
            <input type="text" 
            name="name" 
            value="{{ $category->name }}"
            >
            
            <label for="">Description</label>
            <input type="text" 
            name="description" 
            value="{{ $category->description }}"
            >
        
            <button type="submit" class="btn btn-primary">
                Update Category
            </button>
        
        </form>

    </x-form-card>

@endsection