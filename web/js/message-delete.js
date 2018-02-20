function deleteMessage(id) {
    swal({

        title: "Are you sure?",
        text: "Are you sure you want to delete this message?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        confirmButtonColor: "#ec6c62"

    }, function() {

        $.ajax({
            url: window.location.href + '/' + id + '/delete',
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(data) {
                // No error from the server.
                if(!data['error']) {
                    // Delete the row from the table
                    $('#message-' + id).remove();

                    swal('Success', 'Your message was deleted!', 'success');
                }

                // Error from the server.
                else {
                    swal('Error', data['message'], 'error');
                }
            },
            error: function() {
                swal('Error', 'Server error, try deleting message later.', 'warning');
            }
        });
    });
}