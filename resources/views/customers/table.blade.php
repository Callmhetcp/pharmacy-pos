
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

                                           <form action="{{ route('customers.toggleStatus', $customer->id) }}" method="POST" class="d-inline customer-status-form">

                                            @csrf
                                            @method('PATCH')

                                            @if($customer->status == 'Active')

                                                <button 
                                                    type="button"
                                                    class="btn btn-danger btn-sm status-btn"
                                                    data-message="Deactivate this customer?">

                                                    <i class="fas fa-user-slash"></i>

                                                </button>

                                            @else

                                                <button 
                                                    type="button"
                                                    class="btn btn-success btn-sm status-btn"
                                                    data-message="Activate this customer?">

                                                    <i class="fas fa-user-check"></i>

                                                </button>

                                            @endif

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
