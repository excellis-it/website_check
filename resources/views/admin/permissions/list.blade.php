@extends('admin.layouts.master')
@section('title')
    Permissions Management - {{ env('APP_NAME') }}
@endsection
@section('head')
    Permissions Management
@endsection
@section('create_button')
    @can('create-permissions')
        <a href="{{ route('permissions.create') }}" class="btn-3">+ Create Permission</a>
    @endcan
@endsection
@section('content')
    <div class="main-content">
        <div class="inner_page">
            <div class="card table_sec stuff-list-table">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="row g-1 justify-content-end">
                            <div class="col-md-8 pr-0">
                                <div class="search-field prod-search">
                                    <input type="text" name="search" id="search" placeholder="Search permissions..."
                                        required class="form-control">
                                    <a href="javascript:void(0)" class="prod-search-icon"><i
                                            class="ph ph-magnifying-glass"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive custom-table-wrapper">
                    <table class="table custom-table" id="myTable">
                        <thead>
                            <tr>
                                <th class="sorting" data-tippy-content="Sort by Name" data-sorting_type="desc"
                                    data-column_name="name">
                                    Permission Name
                                    <span class="sort-icon" id="name_icon">
                                        <i class="fa-solid fa-sort"></i>
                                    </span>
                                </th>
                                <th>Roles Using</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @include('admin.permissions.table')
                        </tbody>
                    </table>

                    <input type="hidden" id="hidden_page" value="1">
                    <input type="hidden" id="hidden_column_name" value="name">
                    <input type="hidden" id="hidden_sort_type" value="asc">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#delete', function(e) {
            swal({
                    title: "Are you sure?",
                    text: "To delete this permission.",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        window.location = $(this).data('route');
                    } else if (result.dismiss === 'cancel') {
                        swal('Cancelled', 'Your stay here :)', 'error')
                    }
                })
        });

        $(document).ready(function() {
            function clear_icon() {
                $('#name_icon').html('');
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
                $('#' + column_name + '_icon').html(
                    order_type == 'asc' ? '<i class="fa-solid fa-sort-down"></i>' :
                    '<i class="fa-solid fa-sort-up"></i>'
                );

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
