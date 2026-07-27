 @forelse($purchases as $purchase)

                        <tr>

                            <td>

                                {{ $loop->iteration }}

                            </td>

                            <td>

                                <span class="fw-bold text-primary">

                                    {{ $purchase->purchase_number }}

                                </span>

                            </td>

                            <td>

                                <i class="fas fa-truck text-secondary me-1"></i>

                                {{ $purchase->supplier->company }}

                            </td>

                            <td>

                                {{ $purchase->invoice_number ?? 'N/A' }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}

                            </td>

                            <td class="text-end fw-bold text-success">

                                ₦{{ number_format($purchase->grand_total,2) }}

                            </td>

                            <td class="text-center">

                                <span class="badge bg-primary rounded-pill">

                                    {{ $purchase->purchase_items_count }}

                                </span>

                            </td>

                            <td class="text-center">

                                <div class="btn-group">

                                    <a href="{{ route('purchase.show',$purchase->id) }}"
                                       class="btn btn-info btn-sm"
                                       title="View">

                                        <i class="fas fa-eye"></i>

                                    </a>

                                    <a href="{{ route('purchase.edit',$purchase->id) }}"
                                       class="btn btn-warning btn-sm"
                                       title="Edit">

                                        <i class="fas fa-edit"></i>

                                    </a>

                                    <a href="{{ route('purchase.receipt',$purchase->id) }}"
                                       class="btn btn-success btn-sm"
                                       title="Print Receipt">

                                        <i class="fas fa-print"></i>

                                    </a>

                                    <form action="{{ route('purchase.destroy', $purchase->id) }}" method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="button"
                                            class="btn btn-outline-danger btn-sm delete-btn">

                                            <i class="fas fa-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>
                         @empty

                        <tr>

                            <td colspan="8" class="text-center py-5">

                                <i class="fas fa-cart-plus fa-4x text-muted mb-3 d-block"></i>

                                <h5 class="text-muted">

                                    No Purchase Records Found

                                </h5>

                                <p class="text-muted">

                                    Click <strong>New Purchase</strong> to record your first purchase.

                                </p>

                            </td>

                        </tr>

                        @endforelse