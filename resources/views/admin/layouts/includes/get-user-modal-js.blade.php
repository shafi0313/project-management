<script>
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
