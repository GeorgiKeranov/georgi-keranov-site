// Getting the url of the site
// Example: https://site.com/
var url = window.location.href.split("/");
url = url[0] + "//" + url[2] + "/";

function editComment(id) {
    swal({
            title: "Edit the comment!",
            text: "Comment:",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Edit it!"
        },
        function(inputValue) {

            if(inputValue == false) {
                return;
            }

            var fd = new FormData();
            fd.append('comment', inputValue);

            $.ajax({
                url: url + 'admin/comments/' + id + '/edit',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {

                    if(data['error']) {
                        swal('Error', data['message'], 'error');
                    }

                    else {
                        swal('Success', 'Comment edited successfully.', 'success');
                        $('#comment-' + id + '-content')[0].innerText = inputValue;
                    }
                }
            });

        });
}

function deleteComment(id) {
    swal({
        title: "Are you sure?",
        text: "Are you sure you want to delete this comment?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        confirmButtonColor: "#ec6c62"
    }, function() {

        $.ajax({
            url: url + "admin/comments/" + id + "/delete",
            processData: false,
            contentType: false,
            type: 'POST',
            success: function (data) {

                if(data['error'])
                    swal('Error', data['message'], 'error');

                if(!data['error']) {
                    swal('Success', 'Comment is successfully deleted.', 'success');
                    $('#comment-' + id).remove();
                }
            }
        });

    });
}