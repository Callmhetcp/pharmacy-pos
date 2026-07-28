 @forelse($suppliers as $supplier)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>

                                        <strong>

                                            {{ $supplier->company }}

                                        </strong>

                                        <br>

                                        <small class="text-muted">

                                            {{ $supplier->address }}

                                        </small>

                                    </td>

                                    <td>{{ $supplier->name }}</td>

                                    <td>{{ $supplier->phone_number }}</td>

                                    <td>{{ $supplier->email }}</td>

                                    <td>

                                        @if($supplier->status == 'Active')

                                            <span class="badge bg-success">

                                                Active

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                Inactive

                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        <a
                                            href="{{ route('suppliers.edit', $supplier->id) }}"
                                            class="btn btn-warning btn-sm">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                       @if($supplier->status == 'Active')

                                            <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-danger btn-sm">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>

                                            @else

                                            <form action="{{ route('suppliers.activate', $supplier->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-success btn-sm">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            @endif

                                    </td>

                                </tr>

                                @empty

                                <tr>

                                    <td colspan="7" class="text-center py-5">

                                        <i class="fas fa-truck fa-3x text-muted mb-3 d-block"></i>

                                        <h5 class="text-muted">

                                            No suppliers found.

                                        </h5>

                                    </td>

                                </tr>

                                @endforelse
