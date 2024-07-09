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
        @include('admin.layouts.includes.summer-note-with-image', ['height' => '150px'])
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
                            data: 'job_name',
                            name: 'job_name',
                            title: 'job name'
                        },
                        {
                            data: 'job_description',
                            name: 'job_description',
                            title: 'job description'
                        },
                        {
                            data: 'users',
                            name: 'users',
                            title: 'action'
                        },
                        {
                            data: 'sub_sections',
                            name: 'sub_sections',
                            title: 'Section'
                        },
                        {
                            data: 'created_by.name',
                            name: 'created_by.name',
                            title: 'Assigned By'
                        },
                        {
                            data: 'progress',
                            name: 'progress',
                            title: 'progress'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline',
                            title: 'deadline'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Functions',
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
        $('#sub_section_id').select2({
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
                        type: 'getSubSection',
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
