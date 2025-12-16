@if (count($urls) > 0)
    @foreach ($urls as $key => $url)
        <tr>
            <td>{{ $url->name }}</td>
            <td>
                <a href="{{ $url->url }}" target="_blank" class="text-primary text-decoration-none">
                    {{ Str::limit($url->url, 50) }}
                    <i class="ph ph-arrow-square-out"></i>
                </a>
            </td>
            <td>
                <span class="status-badge status-{{ $url->status }}">
                    {{ ucfirst($url->status) }}
                </span>
            </td>
            <td>
                @if ($url->response_time)
                    <span class="badge bg-info">{{ $url->response_time }}ms</span>
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td>
                @if ($url->last_checked_at)
                    {{ $url->last_checked_at->format('M d, Y h:i A') }}
                @else
                    <span class="text-muted">Never</span>
                @endif
            </td>
            <td>
                @if ($url->assignedUsers->count() > 0)
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($url->assignedUsers->take(3) as $user)
                            <span class="badge bg-secondary">{{ $user->name }}</span>
                        @endforeach
                        @if ($url->assignedUsers->count() > 3)
                            <span class="badge bg-dark">+{{ $url->assignedUsers->count() - 3 }} more</span>
                        @endif
                    </div>
                @else
                    <span class="text-muted">No users assigned</span>
                @endif
            </td>
            <td>
                <div class="edit-1 d-flex align-items-center justify-content-center gap-2">
                    <a title="View Details" href="{{ route('url-management.show', $url->encrypted_id) }}">
                        <span class="edit-icon"><i class="ph ph-eye"></i></span>
                    </a>

                    <a title="Check Now" href="javascript:void(0);" class="check-url-btn"
                        data-id="{{ $url->encrypted_id }}">
                        <span class="edit-icon text-info"><i class="ph ph-arrow-clockwise"></i></span>
                    </a>

                    @can('update', $url)
                        <a title="Edit URL" href="{{ route('url-management.edit', $url->encrypted_id) }}">
                            <span class="edit-icon"><i class="ph ph-pencil-simple"></i></span>
                        </a>
                    @endcan

                    @can('delete', $url)
                        <a title="Delete URL" data-route="{{ route('url-management.delete', $url->encrypted_id) }}"
                            href="javascript:void(0);" id="delete">
                            <span class="trash-icon"><i class="ph ph-trash"></i></span>
                        </a>
                    @endcan
                </div>
            </td>
        </tr>
    @endforeach
    {{-- pagination --}}
    <tr>
        <td colspan="7">
            <div class="d-flex justify-content-center">
                {!! $urls->links() !!}
            </div>
        </td>
    </tr>
@else
    <tr>
        <td colspan="7" class="text-center">No Data Found</td>
    </tr>
@endif
