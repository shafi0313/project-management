@extends('admin.layouts.app')
@section('title', 'Project')
@section('content')
    @include('admin.layouts.includes.breadcrumb', ['title' => ['', 'Project', 'Index']])

    <script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <h3 class="card-title">{{ $project->job_name }}</h3>
                        {{-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button> --}}
                    </div>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->

            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="summary-tab" data-bs-toggle="tab"
                                data-bs-target="#summary-tab-pane" type="button" role="tab"
                                aria-controls="summary-tab-pane" aria-selected="true">Summary</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="task-tab" data-bs-toggle="tab" data-bs-target="#task-tab-pane"
                                type="button" role="tab" aria-controls="task-tab-pane"
                                aria-selected="false">Task</button>
                        </li>
                        {{-- <li class="nav-item" role="presentation">
                          <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Contact</button>
                        </li> --}}
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="attachment-tab" data-bs-toggle="tab"
                                data-bs-target="#attachment-tab-pane" type="button" role="tab"
                                aria-controls="attachment-tab-pane" aria-selected="false">Attachment</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        @include('admin.project.summary')
                        @include('admin.project.task')
                        @include('admin.project.attachment')

                        {{-- <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">...</div> --}}

                    </div>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->


    @include('admin.task.create', ['project_id' => $project->id])
    @push('scripts')
        @include('admin.layouts.includes.summer-note-with-image',['height'=>'150'])
        @include('admin.layouts.includes.get-user-modal-js')
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
            $(function() {
                $('#task_table').DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    // responsive: true,
                    // scrollX: true,
                    // scrollY: 400,
                    ajax: "{{ route('admin.projects.tasks', $project->id) }}",
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
                            title: 'assigned user'
                        },
                        {
                            data: 'priority',
                            name: 'priority',
                            title: 'priority'
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
