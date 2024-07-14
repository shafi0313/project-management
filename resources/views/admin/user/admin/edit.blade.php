<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editModalLabel">Edit Admin User</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form onsubmit="ajaxStoreModal(event, this, 'editModal')"
                action="{{ route('admin.admin-users.update', $admin_user->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="row gy-2">
                        <div class="col-md-6">
                            <label for="section_id" class="form-label required">Section </label>
                            <select name="section_id" id="edit_section_id" class="form-control" required>
                                <option value="{{ user()->section_id }}">{{ user()->section->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="sub_section_id" class="form-label">Sub Section </label>
                            <select name="sub_section_id" id="edit_sub_section_id" class="form-control sub_section_id">
                                <option value="{{ user()->sub_section_id }}">{{ user()->subSection?->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="name" class="form-label required">Name </label>
                            <input type="text" name="name" value="{{ old('name') ?? $admin_user->name }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label required">Email </label>
                            <input type="email" name="email" value="{{ old('email') ?? $admin_user->email }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="user_name" class="form-label">user name </label>
                            <input type="text" name="user_name"
                                value="{{ old('name_name') ?? $admin_user->name_name }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="mobile" class="form-label required">mobile </label>
                            <input type="text" name="mobile" value="{{ old('mobile') ?? $admin_user->mobile }}"
                                class="form-control" oninput="phoneIn(event)">
                        </div>
                        <div class="col-md-6">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" name="gender">
                                <option selected disabled value="">Choose...</option>
                                @foreach ($genders as $key => $gender)
                                    <option value="{{ $key }}" @selected($key == $admin_user->gender)>
                                        {{ $gender }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="address" class="form-label required">address </label>
                            <input type="text" name="address" value="{{ old('address') ?? $admin_user->address }}"
                                class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Old Image </label>
                            <img src="{{ imagePath('user', $admin_user->image) }}" width="100px">
                        </div>
                        <div class="col-md-6">
                            <label for="image" class="form-label">image </label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Old Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                        <div class="col-md-12 text-center mt-2">
                            <button type="reset" class="btn btn-danger">Reset</button>
                            <button type="submit" class="btn btn-warning">Update</button>
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
<script>
    $(document).ready(function() {
        $('#edit_section_id').select2({
            dropdownParent: $('#editModal'),
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

        $('#edit_sub_section_id').select2({
            dropdownParent: $('#editModal'),
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
                        section_id: $('#edit_section_id').find(":selected").val()
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
