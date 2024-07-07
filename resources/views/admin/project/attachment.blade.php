<div class="tab-pane fade" id="attachment-tab-pane" role="tabpanel" aria-labelledby="attachment-tab" tabindex="0">
    <form action="{{ route('admin.dropzone.store') }}" class="dropzone" id="my-dropzone">
        @csrf
    </form>
</div>


<script>
    Dropzone.options.myDropzone = {
        maxFilesize: 2, // MB
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        addRemoveLinks: true,
        success: function(file, response) {
            file.serverFileName = response.filename; // store server response
        },
        removedfile: function(file) {
            var name = file.serverFileName;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                        'content')
                },
                type: 'DELETE',
                url: '{{ route('admin.dropzone.destroy') }}',
                data: {
                    filename: name
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
