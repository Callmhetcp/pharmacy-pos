<x-form-card title="Delete Category">

    <form action="/categories/{{ $category->id }}" method="POST">

        @csrf
        @method('DELETE')

        <button type="submit" 
        onclick="return confirm('Are you sure you want to delete this category?')">
            Delete
        </button>

    </form>

</x-form-card>