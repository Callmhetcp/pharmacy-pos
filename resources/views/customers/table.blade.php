
                                @forelse($customers as $customer)

                                    <tr>

                                        <td>

                                            {{ $loop->iteration }}

                                        </td>

                                        <td>

                                            <strong>

                                                {{ $customer->name }}

                                            </strong>

                                        </td>

                                        <td>

                                            {{ $customer->phone_number }}

                                        </td>

                                        <td>

                                            {{ $customer->address }}

                                        </td>

                                        <td class="text-center">

                                            <a
                                                href="{{ route('customers.edit', $customer->id) }}"
                                                class="btn btn-warning btn-sm">

                                                <i class="fas fa-edit"></i>

                                            </a>

                                            <form
                                                action="{{ route('customers.destroy', $customer->id) }}"
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

                                        <td colspan="5" class="text-center py-5 text-muted">

                                            <i class="fas fa-users fa-3x mb-3 d-block"></i>

                                            <h5>No customers found.</h5>

                                            <p>Add your first customer to begin.</p>

                                        </td>

                                    </tr>

                                @endforelse
