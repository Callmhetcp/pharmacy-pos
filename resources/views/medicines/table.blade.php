 @forelse($medicines as $medicine)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>

                                        <strong>

                                            {{ $medicine->name }}

                                        </strong>

                                    </td>

                                    <td>

                                        @if($medicine->quantity > 50)

                                            <span class="badge bg-success">

                                                {{ $medicine->quantity }}

                                            </span>

                                        @elseif($medicine->quantity > 10)

                                            <span class="badge bg-warning text-dark">

                                                {{ $medicine->quantity }}

                                            </span>

                                        @else

                                            <span class="badge bg-danger">

                                                {{ $medicine->quantity }}

                                            </span>

                                        @endif

                                    </td>

                                    <td>

                                        ₦{{ number_format($medicine->cost_price,2) }}

                                    </td>

                                    <td>

                                        ₦{{ number_format($medicine->selling_price,2) }}

                                    </td>

                                    <td>

                                        {{ \Carbon\Carbon::parse($medicine->expiry_date)->format('d M Y') }}

                                    </td>

                                    <td>

                                        <span class="badge bg-info">

                                            {{ $medicine->category->name }}

                                        </span>

                                    </td>

                                    <td class="text-center">

                                        <a href="{{ route('medicine.edit',$medicine->id) }}"
                                           class="btn btn-warning btn-sm">

                                            <i class="fas fa-edit"></i>

                                        </a>

                                        <form action="{{ route('medicine.destroy',$medicine->id) }}"
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

                                    <td colspan="8" class="text-center py-5 text-muted">

                                        <i class="fas fa-capsules fa-3x mb-3 d-block"></i>

                                        No medicines found.

                                    </td>

                                </tr>

                                @endforelse