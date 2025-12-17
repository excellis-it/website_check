@extends('admin.layouts.master')
@section('title')
    Permissions Management - {{ env('APP_NAME') }}
@endsection
@section('head')
    Permissions Management
@endsection

@section('content')
    <div class="main-content">
        <div class="container-fluid px-4 pt-4">

            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 fw-bold text-dark">Permissions Registry</h4>
                    <p class="text-muted mb-0 small">Manage system-wide permissions.</p>
                </div>
                <div>
                    @can('create-permissions')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary fw-medium px-4">
                            <i class="ph ph-plus me-1"></i> Create Permission
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Table Card -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-0">
                    <!-- Toolbox / Search -->
                    <div class="p-3 border-bottom bg-light d-flex justify-content-end">
                        <div class="search-field prod-search position-relative" style="width: 300px;">
                            <input type="text" name="search" id="search" placeholder="Search permissions..."
                                class="form-control rounded-pill ps-5" style="border:1px solid #e2e8f0; height: 42px;">
                            <span class="position-absolute top-50 translate-middle-y text-muted" style="left: 15px;">
                                <i class="ph ph-magnifying-glass fs-5"></i>
                            </span>
                        </div>
                    </div>

                    <div class="table-responsive custom-table-wrapper">
                        <table class="table custom-table mb-0 align-middle" id="myTable">
                            <thead class="bg-light text-muted small text-uppercase fw-bold">
                                <tr>
                                    <th class="sorting py-3 px-4" data-tippy-content="Sort by Name" data-sorting_type="desc"
                                        data-column_name="name" style="cursor: pointer;">
                                        Permission Name
                                        <span class="sort-icon ms-1" id="name_icon">
                                            <i class="fa-solid fa-sort text-muted op-5"></i>
                                        </span>
                                    </th>
                                    <th class="py-3 px-4">Roles Using</th>
                                    <th class="text-end py-3 px-4">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @include('admin.permissions.table')
                            </tbody>
                        </table>
                    </div>

                    <div class="p-3 border-top">
                        <input type="hidden" id="hidden_page" value="1">
                        <input type="hidden" id="hidden_column_name" value="name">
                        <input type="hidden" id="hidden_sort_type" value="asc">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#delete', function(e) {
            e.preventDefault();
            var route = $(this).data('route');
            swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this permission!",
                    type: "warning",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "Cancel",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                })
                .then((result) => {
                    if (result.value) {
                        window.location = route;
                    }
                })
        });

        $(document).ready(function() {
            function clear_icon() {
                $('#name_icon').html('<i class="fa-solid fa-sort text-muted op-5"></i>');
            }

            function fetch_data(page, sort_type, sort_by, query) {
                $.ajax({
                    url: "{{ route('permissions.fetch-data') }}",
                    data: {
                        page: page,
                        sortby: sort_by,
                        sorttype: sort_type,
                        query: query
                    },
                    success: function(data) {
                        $('tbody').html(data.data);
                    }
                });
            }

            $(document).on('keyup', '#search', function() {
                fetch_data($('#hidden_page').val(), $('#hidden_sort_type').val(),
                    $('#hidden_column_name').val(), $(this).val());
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = order_type == 'asc' ? 'desc' : 'asc';

                $(this).data('sorting_type', reverse_order);
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);

                clear_icon();
                if (reverse_order == 'asc') {
                    $('#' + column_name + '_icon').html('<i class="fa-solid fa-sort-up text-primary"></i>');
                } else {
                    $('#' + column_name + '_icon').html(
                        '<i class="fa-solid fa-sort-down text-primary"></i>');
                }

                fetch_data($('#hidden_page').val(), reverse_order, column_name, $('#search').val());
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                fetch_data(page, $('#hidden_sort_type').val(), $('#hidden_column_name').val(), $('#search')
                    .val());
            });
        });
    </script>
@endpush
