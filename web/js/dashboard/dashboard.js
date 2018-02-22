$('#input-image')[0].onchange = function(evt) {
    var tgt = evt.target || window.event.srcElement, files = tgt.files;

    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {

            // Showing remove picture button.
            $('.fa-remove').removeClass('hidden');

            // Setting the image with the input file.
            $('#portfolio-pic').attr('src', fr.result);
        };
        fr.readAsDataURL(files[0]);
    }
};

function removePortfolioPic() {
    // Hiding remove icon
    $('.fa-remove').addClass('hidden');

    // Removing value from input.
    $('#input-image').val('');

    // Setting the image with default image.
    $('#portfolio-pic').attr('src', '/img/georgi-keranov.jpg');
}