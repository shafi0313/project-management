@include('admin.project.css')
<style>
    .setting {
        list-style: none;
        padding: 0;
    }

    .setting li {
        word-wrap: break-word;
        background-color: #e4e8ec;
        border-color: #e4e8ec;
        border-radius: 3px;
        color: #3e4852;
        font-size: 13px;
        margin-bottom: 10px;
        min-height: 31px;
        padding: 5px 5px 0;
    }

    .info {
        width: 100%;
        background: #e4e8ec;
        border-radius: 5px;
        padding: 8px;
    }

    .info tr {
        padding: 5px;
    }
</style>
<div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="showModalLabel">Show Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gx-5">
                    <div class="col-md-8">
                        <div class="row">
                            <p class="fs-4">Project Name: <b>{{ $task->project->job_name }}</b></p>
                            <p class="fs-4">Task Name: <b>{{ $task->task_name }}</b></p>

                            <div class="col-md-12">
                                <p class="border-bottom">Description:</p>
                                <p>{!! $task->task_description !!}</p>
                            </div>
                            <div class="col-md-12">
                                <p class="border-bottom">Attachments:</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Action</h4>
                        <ul class="member">
                            @foreach ($task->users as $user)
                                <li class="d-flex align-items-center">
                                    <div>
                                        <img src="{{ imagePath('user', $user->image) }}" alt="">
                                    </div>
                                    <div>
                                        <span class="fw-bold">{{ $user->name }}</span>
                                        <br>
                                        <span>{{ $user->email }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <h4>Settings</h4>
                        <ul class="setting">
                            <li>Start Date: <b>{{ bdDate($task->due_date) }}</b></li>
                            <li>Due Date: <b>{{ bdDate($task->due_date) }}</b></li>
                            <li>Status: <b>{!! taskStatus($task->status) !!}</b></li>
                            <li>Priority: <b>{!! priority($task->priority) !!}</b></li>
                        </ul>

                        <h4>Information</h4>
                        <div class="info">
                            <table>
                                <tr>
                                    <td>Assigned By:</td>
                                    <th>{{ $task->createdBy->name }}</th>
                                </tr>
                                <tr>
                                    <td>Created On:</td>
                                    <th>{{ bdDate($task->created_at) }}</th>
                                </tr>
                                <tr>
                                    <td>Last Update By:</td>
                                    <th>{{ $task->updatedBy->name }}</th>
                                </tr>
                                <tr>
                                    <td>Updated On:</td>
                                    <th>{{ bdDate($task->updated_at) }}</th>
                                </tr>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                {{-- <button type="submit" class="btn btn-primary">Save changes</button> --}}
            </div>
        </div>
    </div>
</div>
