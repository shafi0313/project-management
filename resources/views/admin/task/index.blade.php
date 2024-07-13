@extends('admin.layouts.app')
@section('title', 'Task')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Task', 'Index']])

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of Tasks</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModalTask">
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
    @can('task-add')
        @include('admin.task.create')
    @endcan
    @push('scripts')
        {{-- @include('admin.layouts.includes.summer-note-with-image', ['height' => '200px'])
        @include('admin.layouts.includes.get-user-modal-js') --}}
        <script>
            $(function() {
                $('#data_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // responsive: true,
                    // scrollX: true,
                    // scrollY: 400,
                    ajax: "{{ route('admin.tasks.index') }}",
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
                            data: 'task_name',
                            name: 'task_name',
                            title: 'task name'
                        },
                        {
                            data: 'task_description',
                            name: 'task_description',
                            title: 'task description'
                        },
                        {
                            data: 'user',
                            name: 'user',
                            title: 'Assigned to'
                        },
                        {
                            data: 'priority',
                            name: 'priority',
                            title: 'priority'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline',
                            title: 'deadline'
                        },
                        {
                            data: 'created_by.section.name',
                            name: 'created_by.section.name',
                            title: 'Assigned By'
                        },
                        {
                            data: 'updated_by.name',
                            name: 'updated_by.name',
                            title: 'updated By'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Action',
                            className: "text-center",
                            width: "100px",
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    scroller: {
                        loadingIndicator: true
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('#project_id').select2({
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
                                type: 'getProject',
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
