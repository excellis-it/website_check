@extends('admin.layouts.master')
@section('title')
    Roles Management - {{ env('APP_NAME') }}
@endsection
@push('styles')
    <style>
        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
    </style>
@endpush
@section('head')
    Roles Management
@endsection
@section('create_button')
    @can('create-roles')
        <a href="{{ route('roles.create') }}" class="btn-3">+ Create Role</a>
    @endcan
@endsection
@section('content')
    <section id="loading">
        <div id="loading-content"></div>
    </section>
    <div class="main-content">
        <div class="inner_page">
            <div class="card table_sec stuff-list-table">
                <div class="row justify-content-end">
                    <div class="col-md-6">
                        <div class="row g-1 justify-content-end">
                            <div class="col-md-8 pr-0">
                                <div class="search-field prod-search">
                                    <input type="text" name="search" id="search" placeholder="Search roles..."
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
                                    Role Name
                                    <span class="sort-icon" id="name_icon">
                                        <i class="fa-solid fa-sort"></i>
                                    </span>
                                </th>
                                <th>Permissions</th>
                                <th>Users Count</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @include('admin.roles.table')
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
                    text: "To delete this role.",
                    type: "warning",
                    confirmButtonText: "Yes",
                    showCancelButton: true
                })
                .then((result) => {
                    if (result.value) {
                        window.location = $(this).data('route');
                    } else if (result.dismiss === 'cancel') {
                        swal(
                            'Cancelled',
                            'Your stay here :)',
                            'error'
                        )
                    }
                })
        });
    </script>

    <script>
        $(document).ready(function() {

            function clear_icon() {
                $('#name_icon').html('');
            }

            function fetch_data(page, sort_type, sort_by, query) {
                $.ajax({
                    url: "{{ route('roles.fetch-data') }}",
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
                var query = $('#search').val();
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var page = $('#hidden_page').val();
                fetch_data(page, sort_type, column_name, query);
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';

                $('.sorting').removeClass('active');
                $(this).addClass('active');

                $('#' + column_name + '_icon').addClass('sorting-active');
                setTimeout(function() {
                    $('#' + column_name + '_icon').removeClass('sorting-active');
                }, 400);

                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    clear_icon();
                    $('#' + column_name + '_icon').html('<i class="fa-solid fa-sort-down"></i>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    clear_icon();
                    $('#' + column_name + '_icon').html('<i class="fa-solid fa-sort-up"></i>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();
                var query = $('#search').val();
                fetch_data(page, reverse_order, column_name, query);
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                var query = $('#search').val();

                $('li').removeClass('active');
                $(this).parent().addClass('active');
                fetch_data(page, sort_type, column_name, query);
            });

        });
    </script>
@endpush
