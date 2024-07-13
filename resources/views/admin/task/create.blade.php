<div class="modal fade" id="createModalTask" tabindex="-1" aria-labelledby="createModalTaskLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalTaskLabel">Add Task</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'createModalTask')" action="{{ route('admin.tasks.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row gy-2">
                        @if (isset($project_id))
                            <input type="hidden" name="project_id" value="{{ $project_id }}">
                        @else
                            <div class="col-md-12">
                                <label for="name" class="form-label required">Project </label>
                                <select name="project_id" class="form-select" id="project_id" required>
                                </select>
                            </div>
                        @endif
                        <div class="col-md-12">
                            <label for="task_name" class="form-label required">task Name </label>
                            <input type="text" name="task_name" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="task_description" class="form-label">task description </label>
                            <textarea name="task_description" class="form-control note_content"></textarea>
                        </div>
                        <div class="col-md-8">
                            <label for="name" class="form-label required">Assigned to </label>
                            <select name="user_id[]" class="form-select" id="user_id" required>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="priority" class="form-label required">Priority </label>
                            <select name="priority" class="form-select" required>
                                <option value="">Select</option>
                                @foreach (config('var.priorities') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="start_date" class="form-label required">start date </label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label for="deadline" class="form-label">Deadline </label>
                            <input type="date" name="deadline" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label required">Status </label>
                            <select name="status" class="form-select" required>
                                <option value="">Select</option>
                                @foreach (config('var.projectStatus') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
