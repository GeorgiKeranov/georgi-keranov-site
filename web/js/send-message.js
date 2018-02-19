function sendMessage() {

    var fd = new FormData();
    fd.append('name', $('#name').val());
    fd.append('email', $('#email').val());
    fd.append('phone', $('#phone').val());
    fd.append('message', $('#message').val());

    $.ajax({
        url: window.location.href + "/message",
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function() {
            $('#name').val('');
            $('#email').val('');
            $('#phone').val('');
            $('#message').val('');
        }
    });
}