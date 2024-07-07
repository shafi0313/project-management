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
                    scrollX: true,
                    scrollY: 400,
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
                            data: 'name',
                            name: 'name',
                            title: 'name'
                        },
                        {
                            data: 'content',
                            name: 'content',
                            title: 'content'
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
        </script>
    @endpush
@endsection
