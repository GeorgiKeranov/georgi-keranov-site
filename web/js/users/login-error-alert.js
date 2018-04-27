$(document).ready(function() {
    var error = $('#error');

    // Check if element exists
    if(error.length) {
        // Showing error with sweetalert.
        swal(error[0].innerText, "", "error");
    }
});