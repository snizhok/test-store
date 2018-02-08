$(document).ready(function () {

    $('#browse-button').on('click', function (e) {
        e.preventDefault();
        $('#file-input').trigger('click');
    });

    $('#file-input').on('change', function (e) {
        if (!this.files[0]) {
            return false;
        }
        $('#browse-button').button('loading');
        var url = $('#upload-form').attr('action');
        var data = new FormData();
        data.append('file', this.files[0]);
        $.ajax({
            url: url,
            type: "POST",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function(response){
                $('#browse-button').button('reset');
                if (!response.success) {
                    alert(response.message);
                } else {
                    $.get('/',{}, function(response){
                        $('tbody').replaceWith($(response).find('tbody'));
                    });
                }
            }
        });
        $(this).val('');
    })

});