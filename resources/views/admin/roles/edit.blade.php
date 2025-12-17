@extends('admin.layouts.master')
@section('title')
    {{ env('APP_NAME') }} | Edit Role
@endsection
@push('styles')
    <style>
    :root{
        --bg: #ffffff;
        --muted: #94a3b8;
        --muted-2: #cbd5e1;
        --primary: #0d6efd;
        --card-border: #e6eefc;
        --radius: 12px;
        --shadow-sm: 0 4px 10px rgba(16,24,40,0.04);
        --shadow-lg: 0 10px 20px rgba(16,24,40,0.06);
    }

    .permission-card {
        border: 1px solid var(--muted-2);
        border-radius: var(--radius);
        box-shadow: var(--shadow-sm);
        transition: all .18s ease;
        background: var(--bg);
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    .permission-card:hover{
        box-shadow: var(--shadow-lg);
        transform: translateY(-3px);
        border-color: #c3d6ff;
    }

    .permission-header{
        background: #fbfdff;
        padding: 14px 18px;
        border-bottom: 1px solid var(--muted-2);
        display:flex;
        align-items:center;
        justify-content:space-between;
    }
    .permission-body{ padding: 18px; flex:1; }

    .group-title { font-weight:700; color:#0f172a; margin:0; font-size:1rem; text-transform:capitalize; }

    /* Switch (accessible) */
    .switch {
        --w:48px; --h:26px; --pad:3px;
        position:relative;
        width:var(--w);
        height:var(--h);
        display:inline-block;
    }
    .switch input { appearance:none; width:100%; height:100%; border-radius:999px; outline:none; cursor:pointer; position:relative; }
    .switch input::before{
        content:"";
        position:absolute;
        inset:0;
        border-radius:999px;
        background:#e6eefc;
        border:2px solid var(--muted-2);
        transition: background .15s ease, border-color .15s ease;
    }
    .switch input::after{
        content:"";
        position:absolute;
        width:calc(var(--h) - var(--pad) * 2);
        height:calc(var(--h) - var(--pad) * 2);
        left:var(--pad);
        top:var(--pad);
        border-radius:50%;
        background:#fff;
        box-shadow:0 2px 4px rgba(2,6,23,0.08);
        transition: left .15s ease, transform .12s ease;
    }
    .switch input:checked::before{
        background:var(--primary);
        border-color:transparent;
    }
    .switch input:checked::after{ left: calc(100% - (var(--h) - var(--pad))); }

    .perm-item{ margin-bottom:12px; display:flex; align-items:center; gap:12px; }
    .perm-label{ font-size:.95rem; color: #334155; cursor:pointer; user-select:none; flex:1; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; text-transform:capitalize; }

    /* Layout */
    .perm-grid { display:grid; grid-template-columns: repeat(2, 1fr); gap:10px; }
    @media (max-width: 768px){ .perm-grid { grid-template-columns: repeat(1,1fr); } .permission-header{ padding:12px; } .permission-body{ padding:14px; } }

    /* Search */
    .perm-search { display:flex; gap:8px; align-items:center; margin-bottom:12px; }
    .perm-search input{ width:100%; padding:8px 12px; border:1px solid var(--muted-2); border-radius:8px; }

    /* Header controls */
    .controls { display:flex; gap:8px; align-items:center; }
    .btn-ghost { background:transparent; border:1px solid var(--muted-2); padding:8px 12px; border-radius:8px; cursor:pointer; }
</style>
@endpush
@section('head')
    Edit Role
@endsection

@section('content')
   <div class="main-content">
    <div class="container-fluid px-4 pt-4">
        <form action="{{ route('roles.update', $role->id) }}" method="post" id="roleForm" aria-labelledby="roleTitle">
            @csrf
            @method('PUT')

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 id="roleTitle" class="mb-1 fw-bold text-dark">Edit Role: {{ $role->name }}</h4>
                    <p class="text-muted mb-0 small">Update permissions for this role.</p>
                </div>
                <div class="controls">
                    <a href="{{ route('roles.index') }}" class="btn btn-light border me-2 fw-medium btn-ghost">
                        <i class="ph ph-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary fw-medium px-4">
                        <i class="ph ph-check me-1"></i> Update Role
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-uppercase text-muted">Role Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ph ph-shield-check fs-5"></i></span>
                                <input type="text" class="form-control form-control-lg bg-light" value="{{ $role->name }}" readonly aria-readonly="true" disabled>
                            </div>
                            <div class="form-text mt-2"><i class="ph ph-info me-1"></i> Role names cannot be changed once created.</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls: select all + search -->
            <div class="d-flex justify-content-between align-items-center mb-3 gap-3 flex-wrap">
                <h5 class="fw-bold text-dark mb-0">Assign Permissions</h5>
                <div style="display:flex; align-items:center; gap:14px;">
                    <div class="form-check" style="display:flex; align-items:center; gap:8px;">
                        <label class="switch" title="Select all permissions">
                            <input id="selectAllGlobal" type="checkbox" aria-label="Select all permissions">
                        </label>
                        <label for="selectAllGlobal" class="form-check-label fw-bold text-primary" style="cursor:pointer">Select All Permissions</label>
                    </div>
                    <div style="min-width:220px;">
                        <input id="permSearch" type="search" placeholder="Search permissions..." class="form-control" aria-label="Search permissions">
                    </div>
                </div>
            </div>

            <div class="row g-4 pb-5">
                @php
                    $oldPerms = old('permissions', $rolePermissions ?? []);
                @endphp

                @foreach ($groupedPermissions as $group => $permissions)
                    @php $slug = Str::slug($group); @endphp
                    <div class="col-xl-4 col-md-6 mb-3">
                        <div class="permission-card" role="group" aria-labelledby="group-{{ $slug }}">
                            <div class="permission-header">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ph ph-folder-notch text-primary" aria-hidden="true"></i>
                                    <span id="group-{{ $slug }}" class="group-title">{{ $group }}</span>
                                </div>

                                <div class="form-check form-switch mb-0">
                                    <label class="switch" title="Select all in {{ $group }}">
                                        <input class="group-select-all" type="checkbox" data-group="{{ $slug }}" id="group_{{ $slug }}" aria-label="Select all {{ $group }}">
                                    </label>
                                </div>
                            </div>

                            <div class="permission-body">
                                <div class="perm-search" style="display:none">
                                    <input class="group-filter" placeholder="Filter in {{ $group }}" />
                                </div>

                                <div class="perm-grid" data-group-grid="{{ $slug }}">
                                    @foreach ($permissions as $permission)
                                        @php
                                            // Cleaner display: take everything except the trailing resource slug
                                            // e.g. 'view-users' -> 'View', 'create-blog-posts' -> 'Create blog'
                                            $nameParts = preg_split('/[-_]+/', $permission->name);
                                            if (count($nameParts) > 1) {
                                                array_pop($nameParts); // drop last (resource)
                                            }
                                            $displayName = ucfirst(implode(' ', $nameParts));
                                        @endphp

                                        <div class="perm-item" data-perm-name="{{ strtolower($permission->name) }}">
                                            <div class="form-check" style="width:100%; display:flex; align-items:center; gap:12px;">
                                                <label class="switch" for="permission_{{ $permission->id }}">
                                                    <input
                                                        class="perm-checkbox group-{{ $slug }}"
                                                        type="checkbox"
                                                        name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        id="permission_{{ $permission->id }}"
                                                        {{ in_array($permission->id, (array)$oldPerms) ? 'checked' : '' }}
                                                        aria-checked="{{ in_array($permission->id, (array)$oldPerms) ? 'true' : 'false' }}"
                                                    >
                                                </label>

                                                <label class="perm-label" for="permission_{{ $permission->id }}" title="{{ $permission->name }}">
                                                    {{ $displayName }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
   <script>
    (function(){
        const globalCheckbox = document.getElementById('selectAllGlobal');
        const permSearch = document.getElementById('permSearch');

        function getAllCheckboxes(){
            return Array.from(document.querySelectorAll('.perm-checkbox'));
        }
        function getGroupCheckboxes(slug){
            return Array.from(document.querySelectorAll('.group-' + slug));
        }
        function updateGroupState(slug){
            const groupBox = document.querySelector('#group_' + slug);
            const boxes = getGroupCheckboxes(slug);
            const checked = boxes.filter(c => c.checked).length;
            if (checked === 0){
                groupBox.indeterminate = false;
                groupBox.checked = false;
            } else if (checked === boxes.length){
                groupBox.indeterminate = false;
                groupBox.checked = true;
            } else {
                groupBox.checked = false;
                groupBox.indeterminate = true;
            }
        }
        function updateGlobalState(){
            const all = getAllCheckboxes();
            const checked = all.filter(c => c.checked).length;
            if (checked === 0){
                globalCheckbox.indeterminate = false;
                globalCheckbox.checked = false;
            } else if (checked === all.length){
                globalCheckbox.indeterminate = false;
                globalCheckbox.checked = true;
            } else {
                globalCheckbox.checked = false;
                globalCheckbox.indeterminate = true;
            }
        }

        // Initialize group names
        const groupSelectors = Array.from(document.querySelectorAll('.group-select-all'));
        groupSelectors.forEach(g => {
            const slug = g.dataset.group;
            // wire group toggle
            g.addEventListener('change', () => {
                const boxes = getGroupCheckboxes(slug);
                boxes.forEach(b => { b.checked = g.checked; b.setAttribute('aria-checked', b.checked); });
                updateGlobalState();
            });
        });

        // wire permission checkboxes to update states
        getAllCheckboxes().forEach(cb => {
            cb.addEventListener('change', (e) => {
                const classes = Array.from(cb.classList).filter(c => c.startsWith('group-'));
                classes.forEach(c => {
                    const slug = c.replace('group-','');
                    updateGroupState(slug);
                });
                cb.setAttribute('aria-checked', cb.checked);
                updateGlobalState();
            });
        });

        // wire global
        globalCheckbox.addEventListener('change', () => {
            const all = getAllCheckboxes();
            all.forEach(c => { c.checked = globalCheckbox.checked; c.setAttribute('aria-checked', c.checked); });
            // update all group toggles
            groupSelectors.forEach(g => { g.checked = globalCheckbox.checked; g.indeterminate = false; });
        });

        // On load compute initial states
        window.addEventListener('DOMContentLoaded', () => {
            // init groups
            groupSelectors.forEach(g => {
                updateGroupState(g.dataset.group);
            });
            updateGlobalState();
        });

        // Permission search (global)
        permSearch.addEventListener('input', (e) => {
            const q = e.target.value.trim().toLowerCase();
            const items = document.querySelectorAll('[data-perm-name]');
            items.forEach(item => {
                const name = item.dataset.permName;
                item.style.display = name.includes(q) ? '' : 'none';
            });
        });

        // Optional: add simple keyboard focus styles for accessibility
        document.addEventListener('focusin', (e) => {
            const t = e.target;
            if (t.matches('.perm-checkbox, .group-select-all, #selectAllGlobal')) {
                t.closest('.permission-card')?.classList.add('focus-ring');
            }
        });
        document.addEventListener('focusout', (e) => {
            document.querySelectorAll('.permission-card.focus-ring').forEach(el => el.classList.remove('focus-ring'));
        });
    })();
</script>
@endpush
