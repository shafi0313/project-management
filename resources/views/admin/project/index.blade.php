@extends('admin.layouts.app')
@section('title', 'Project')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Project', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Projects</h4>
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
    @can('project-add')
        @include('admin.project.create')
    @endcan
    @push('scripts')
        @include('admin.layouts.includes.summer-note-with-image')
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
                    ajax: "{{ route('admin.projects.index') }}",
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
                            title: 'name'
                        },
                        {
                            data: 'user',
                            name: 'user',
                            title: 'user'
                        },
                        {
                            data: 'created_by.name',
                            name: 'created_by.name',
                            title: 'Created By'
                        },
                        {
                            data: 'updated_by.name',
                            name: 'updated_by.name',
                            title: 'updated By'
                        },
                        {
                            data: 'is_active',
                            name: 'is_active',
                            title: 'Status'
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
                    scroller: {
                        loadingIndicator: true
                    }
                });
            });

            $(document).ready(function() {
                $('#user_id').select2({
                    dropdownParent: $('.modal-body'),
                    width: '100%',
                    placeholder: 'Select...',
                    allowClear: true,
                    multiple: true,
                    ajax: {
                        url: window.location.origin + '/dashboard/select-2-ajax',
                        dataType: 'json',
                        delay: 250,
                        cache: true,
                        data: function(params) {
                            return {
                                q: $.trim(params.term),
                                type: 'getUser',
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
