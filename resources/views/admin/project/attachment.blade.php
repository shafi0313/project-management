<div class="tab-pane fade" id="attachment-tab-pane" role="tabpanel" aria-labelledby="attachment-tab" tabindex="0">
    <form action="{{ route('admin.project_files.store') }}" class="dropzone" id="my-dropzone">
        @csrf
        <input type="hidden" name="project_id" id="project_id" value="{{ $project->id }}">
    </form>
<hr>
<div class="mt-4">
    <table id="file_table" class="table table-bordered table-centered mb-0 w-100">
        <thead>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

</div>


<script>
    Dropzone.options.myDropzone = {
        maxFilesize: 2, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,
        init: function() {
            this.on("sending", function(file, xhr, formData) {
                // Append project ID to formData
                formData.append("project_id", document.getElementById("project_id").value);
            });
        },
        success: function(file, response) {
            file.serverFileName = response.filename; // store server response
        },
        removedfile: function(file) {
            var name = file.serverFileName;
            var projectId = document.getElementById("project_id").value;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                type: 'DELETE',
                url: '{{ route('admin.project_files.destroy') }}',
                data: {
                    filename: name,
                    project_id: projectId
                },
                success: function(data) {
                    console.log("File has been successfully removed!!");
                },
                error: function(e) {
                    console.log(e);
                }
            });

            var fileRef;
            return (fileRef = file.previewElement) != null ?
                fileRef.parentNode.removeChild(file.previewElement) : void 0;
        }
    };
</script>
