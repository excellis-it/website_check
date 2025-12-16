@if (count($roles) > 0)
    @foreach ($roles as $key => $role)
        <tr>
            <td><strong>{{ $role->name }}</strong></td>
            <td>
                @if ($role->permissions->count() > 0)
                    <div class="badge-group">
                        @foreach ($role->permissions->take(5) as $permission)
                            <span class="badge bg-secondary">{{ $permission->name }}</span>
                        @endforeach
                        @if ($role->permissions->count() > 5)
                            <span class="badge bg-dark text-white">+{{ $role->permissions->count() - 5 }} more</span>
                        @endif
                    </div>
                @else
                    <span class="text-muted">No permissions assigned</span>
                @endif
            </td>
            <td>
                <span class="badge bg-info">{{ $role->users()->count() }}</span>
            </td>
            <td>
                <div class="edit-1 d-flex align-items-center justify-content-center gap-2">
                    @can('view-roles')
                        <a title="View Role" href="{{ route('roles.show', $role->id) }}">
                            <span class="edit-icon"><i class="ph ph-eye"></i></span>
                        </a>
                    @endcan

                    @can('edit-roles')
                        <a title="Edit Role" href="{{ route('roles.edit', $role->id) }}">
                            <span class="edit-icon"><i class="ph ph-pencil-simple"></i></span>
                        </a>
                    @endcan

                    @can('delete-roles')
                        @if (!in_array($role->name, ['ADMIN', 'CUSTOMER']))
                            <a title="Delete Role" data-route="{{ route('roles.delete', $role->id) }}"
                                href="javascript:void(0);" id="delete">
                                <span class="trash-icon"><i class="ph ph-trash"></i></span>
                            </a>
                        @endif
                    @endcan

                </div>
            </td>
        </tr>
    @endforeach
    <tr>
        <td colspan="4">
            <div class="d-flex justify-content-center">
                {!! $roles->links() !!}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="4" class="text-center">No Data Found</td>
    </tr>
@endif
