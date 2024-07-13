@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    {{-- <div class="d-flex justify-content-between mb-2">
                        <h4 class="card-title">List of {{ $pageTitle }}s</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                            <i class="fa-solid fa-plus"></i> Add New
                        </button>
                    </div> --}}

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="all-tab" data-bs-toggle="tab"
                                data-bs-target="#all-tab-pane" type="button" role="tab" aria-controls="all-tab-pane"
                                aria-selected="true">All</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="ddps-tab" data-bs-toggle="tab" data-bs-target="#ddps-tab-pane"
                                type="button" role="tab" aria-controls="ddps-tab-pane"
                                aria-selected="false">DDPS</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sops-tab" data-bs-toggle="tab" data-bs-target="#sops-tab-pane"
                                type="button" role="tab" aria-controls="sops-tab-pane"
                                aria-selected="false">SO(PS)</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="sd-tab" data-bs-toggle="tab" data-bs-target="#sd-tab-pane"
                                type="button" role="tab" aria-controls="sd-tab-pane" aria-selected="false"
                                sd>SD(STAT)</button>
                        </li>
                    </ul>
                    <div class="tab-content mt-2" id="myTabContent">
                        <div class="tab-pane fade show active" id="all-tab-pane" role="tabpanel" aria-labelledby="all-tab"
                            tabindex="0">
                            @include('admin.project-table')
                        </div>
                        <div class="tab-pane fade" id="ddps-tab-pane" role="tabpanel" aria-labelledby="ddps-tab"
                            tabindex="0">
                            @include('admin.project-table')
                        </div>
                        <div class="tab-pane fade" id="sops-tab-pane" role="tabpanel" aria-labelledby="sops-tab"
                            tabindex="0">
                            @include('admin.project-table')
                        </div>
                        <div class="tab-pane fade" id="sd-tab-pane" role="tabpanel" aria-labelledby="sd-tab" tabindex="0">
                            @include('admin.project-table')
                        </div>
                    </div>
                    <!-- end row-->
                </div> <!-- end card-body -->
            </div> <!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    @push('scripts')
        <script>
            $(document).ready(function() {
                getData(1, 'all-tab-pane');

                // Event handler for tab button clicks
                $('#myTab button').on('click', function(event) {
                    var section;
                    var tab = $(this).attr('id') + '-pane';
                    switch ($(this).attr('id')) {
                        case 'all-tab':
                            section = 1;
                            break;
                        case 'ddps-tab':
                            section = 2;
                            break;
                        case 'sops-tab':
                            section = 3;
                            break;
                        case 'sd-tab':
                            section = 4;
                            break;
                        default:
                            section = 1;
                    }
                    getData(section, tab);
                });
            });

            function getData(section, tab) {
                var tableElement = $('#' + tab + ' .table');

                // Destroy existing DataTable instance if exists
                if ($.fn.DataTable.isDataTable(tableElement)) {
                    tableElement.DataTable().destroy();
                }

                tableElement.DataTable({
                    processing: true,
                    serverSide: true,
                    deferRender: true,
                    ordering: true,
                    scrollX: true,
                    scrollY: 400,
                    ajax: {
                        url: "{{ route('admin.dashboard.projects') }}",
                        data: function(d) {
                            d.section_id = section;
                        }
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            title: 'SL',
                            className: "text-center",
                            width: "17px",
                            searchable: false,
                            orderable: false
                        },
                        {
                            data: 'job_name',
                            name: 'job_name',
                            title: 'Job Name'
                        },
                        {
                            data: 'job_description',
                            name: 'job_description',
                            title: 'Job Description'
                        },
                        {
                            data: 'users',
                            name: 'users',
                            title: 'Action'
                        },
                        {
                            data: 'sub_sections',
                            name: 'sub_sections',
                            title: 'Section'
                        },
                        {
                            data: 'created_by.section.name',
                            name: 'created_by.section.name',
                            title: 'Assigned By'
                        },
                        {
                            data: 'progress',
                            name: 'progress',
                            title: 'Progress'
                        },
                        {
                            data: 'deadline',
                            name: 'deadline',
                            title: 'Deadline'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            title: 'Functions',
                            className: "text-center",
                            width: "100px",
                            orderable: false,
                            searchable: false
                        }
                    ],
                    scroller: {
                        loadingIndicator: true
                    }
                });
            }
        </script>
    @endpush
@endsection
