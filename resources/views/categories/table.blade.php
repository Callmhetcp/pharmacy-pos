 @forelse($categories as $category)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            <strong>

                                                {{ $category->name }}

                                            </strong>

                                        </td>

                                        <td>

                                            {{ $category->description ?: 'No description available.' }}

                                        </td>

                                        <td class="text-center">

                                            <a
                                                href="{{ route('categories.edit', $category->id) }}"
                                                class="btn btn-warning btn-sm">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <form
                                                action="{{ route('categories.destroy', $category->id) }}"
                                                method="POST"
                                                class="d-inline">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-danger btn-sm delete-btn">

                                                    <i class="fas fa-trash"></i>

                                                </button>

                                            </form>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="4" class="text-center py-5 text-muted">

                                            <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>

                                            No categories found.

                                        </td>

                                    </tr>

                                @endforelse
