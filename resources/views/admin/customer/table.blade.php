@if (count($customers) > 0)
    @foreach ($customers as $key => $customer)
        <tr>
            <td>{{ $customer->name }}</td>
            <td>{{ $customer->email }}</td>
            <td>{{ $customer->phone }}</td>
            <td>
                @can('edit-users')
                    <div class="button-switch">
                        <input type="checkbox" id="status-toggle-{{ $customer['id'] }}" class="switch toggle-class"
                            data-id="{{ $customer['id'] }}" {{ $customer['status'] ? 'checked' : '' }} />
                        <label for="status-toggle-{{ $customer['id'] }}"></label>
                    </div>
                @else
                    @if ($customer['status'])
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                @endcan
            </td>
            <td>
                <div class="edit-1 d-flex align-items-center justify-content-center">
                    @can('edit-users')
                        <a title="Edit Customer" href="{{ route('customers.edit', $customer->id) }}">
                            <span class="edit-icon"><i class="ph ph-pencil-simple"></i></span></a>
                    @endcan
                    @can('delete-users')
                        <a title="Delete Customer" data-route="{{ route('customers.delete', $customer->id) }}"
                            href="javascipt:void(0);" id="delete"> <span class="trash-icon"><i
                                    class="ph ph-trash"></i></span></a>
                    @endcan
                </div>
            </td>

        </tr>
    @endforeach
    {{-- pagination --}}
    <tr>
        <td colspan="8">
            <div class="d-flex justify-content-center">
                {!! $customers->links() !!}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="8" class="text-center">No Data Found</td>
    </tr>
@endif
