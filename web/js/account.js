$(document).ready(function() {
    var error = $('#error');

    // Check if element exists
    if(error.length) {
        // Showing error with sweetalert.
        swal("Error", error[0].innerText, "error");
    }
});

$('#appbundle_user_profilePictureFile')[0].onchange = function(evt) {
    var tgt = evt.target || window.event.srcElement, files = tgt.files;

    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {

           $('#image-img').attr('src', fr.result);
           $('#image-delete').removeClass('hidden');

           $('#appbundle_user_deleteProfilePicture').prop('checked', true);
        };
        fr.readAsDataURL(files[0]);
    }
};


$('#image-delete').click(function() {
    $('#image-img').attr('src', '../img/no-profile-picture.png');
    $('#image-delete').addClass('hidden');

    $('#appbundle_user_profilePictureFile').val('');
    $('#appbundle_user_deleteProfilePicture').prop('checked', true);
});

function submitForm() {

    // Check if current password is not entered.
    if($('#appbundle_user_confirmPassword')[0].value == '') {
        swal('Error', 'Your current password is requried field.', 'error');
    }

    else {
        swal({
            title: 'Are you sure?',
            text: "Are you sure you want to save changes?",
            type: "info",
            showCancelButton: true,
            confirmButtonText: "Save!"
        }, function () {
            $('form')[0].submit();
        });
    }
}