<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createModalLabel">Add {{ $pageTitle }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'createModal')" action="{{ route('admin.' . $route . '.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="section_id" class="form-label required">Section </label>
                            <select name="section_id" class="form-select" required>
                                <option value="">Select Section</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Name </label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="col-md-4 form-check form-switch">
                            <label for="is_active" class="form-label status_label d-block required">Status </label>
                            <input class="form-check-input" type="checkbox" id="is_active_input" value="1"
                                name="is_active" checked>
                            <label class="form-check-label" for="is_active_input" id="is_active_label">Active</label>
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
