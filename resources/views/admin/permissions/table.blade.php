@if (count($permissions) > 0)
    @foreach ($permissions as $permission)
        <tr>
            <td><strong>{{ $permission->name }}</strong></td>
            <td>
                @php
                    $roles = $permission->roles;
                @endphp
                @if ($roles->count() > 0)
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($roles->take(3) as $role)
                            <span class="badge bg-secondary">{{ $role->name }}</span>
                        @endforeach
                        @if ($roles->count() > 3)
                            <span class="badge bg-dark">+{{ $roles->count() - 3 }} more</span>
                        @endif
                    </div>
                @else
                    <span class="text-muted">Not used</span>
                @endif
            </td>
            <td>
                <div class="edit-1 d-flex align-items-center justify-content-center gap-2">
                    @can('edit-permissions')
                        <a title="Edit Permission" href="{{ route('permissions.edit', $permission->id) }}">
                            <span class="edit-icon"><i class="ph ph-pencil-simple"></i></span>
                        </a>
                    @endcan

                    @can('delete-permissions')
                        <a title="Delete Permission" data-route="{{ route('permissions.delete', $permission->id) }}"
                            href="javascript:void(0);" id="delete">
                            <span class="trash-icon"><i class="ph ph-trash"></i></span>
                        </a>
                    @endcan
                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3">
            <div class="d-flex justify-content-center">
                {!! $permissions->links() !!}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="3" class="text-center">No Data Found</td>
    </tr>
@endif
