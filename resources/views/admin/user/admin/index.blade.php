@extends('admin.layouts.app')
@section('title', 'Admin User')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['Admin', 'Admin Users', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Admin Users</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button>
                    </div>
                    <table id="data_table" class="table table-bordered bordered table-centered mb-0 w-100">
                        <thead>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
    @can('admin-user-add')
        @include('admin.user.admin.create')
    @endcan
    @push('scripts')
        <script>
            $(function() {
                $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // responsive: true,
                    scrollX: true,
                    scrollY: 400,
                    ajax: "{{ route('admin.admin-users.index') }}",
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'SL',
                            className: "text-center",
                            width: "17px",
                            searchable: false,
                            orderable: false,
                        },
                        {
                            data: 'name',
                            name: 'name',
                            title: 'Name'
                        },
                        {
                            data: 'email',
                            name: 'email',
                            title: 'Email'
                        },
                        {
                            data: 'mobile',
                            name: 'mobile',
                            title: 'mobile'
                        },
                        {
                            data: 'gender',
                            name: 'gender',
                            title: 'gender'
                        },
                        {
                            data: 'section.name',
                            name: 'section.name',
                            title: 'section'
                        },
                        {
                            data: 'sub_section.name',
                            name: 'sub_section.name',
                            title: 'sub section'
                        },
                        {
                            data: 'permission',
                            name: 'permission',
                            title: 'Permission'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            title: 'Status'
                        },
                        {
                            data: 'image',
                            name: 'image',
                            title: 'Image'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            className: "text-center",
                            width: "60px",
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    // fixedColumns: false,
                    scroller: {
                        loadingIndicator: true
                    }
                });
            });
            $(document).ready(function() {
                $('#section_id').select2({
                    dropdownParent: $('.modal-body'),
                    width: '100%',
                    placeholder: 'Select...',
                    allowClear: true,
                    ajax: {
                        url: window.location.origin + '/dashboard/select-2-ajax',
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                q: $.trim(params.term),
                                type: 'getSection',
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });

                $('#sub_section_id').select2({
                    dropdownParent: $('.modal-body'),
                    width: '100%',
                    placeholder: 'Select section first...',
                    allowClear: true,
                    ajax: {
                        url: window.location.origin + '/dashboard/select-2-ajax',
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                q: $.trim(params.term),
                                type: 'getSubSectionBySection',
                                section_id: $('#section_id').find(":selected").val()
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        }
                    }
                });
            })
        </script>
    @endpush
@endsection
