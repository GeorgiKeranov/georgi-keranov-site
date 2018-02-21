// When the input file is changed.
$('#appbundle_project_mainImage')[0].onchange = function (evt) {

    var tgt = evt.target || window.event.srcElement, files = tgt.files;

    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {

            // Hiding div for adding image.
            $('#main-image-div-add').addClass("hidden");

            // Showing the div with the new image.
            var mainImageDiv = $('#main-image-div');
            mainImageDiv.find('img').attr('src', fr.result);
            mainImageDiv.removeClass("hidden");
        };
        fr.readAsDataURL(files[0]);
    }
};

// Clicking the input for main image.
function addMainImage() {
    $('#appbundle_project_mainImage')[0].click();
}

function deleteMainImage() {
    var mainImageDiv = $('#main-image-div');
    // Hiding mainImageDiv.
    mainImageDiv.addClass('hidden');
    // Deleting src from main image.
    mainImageDiv.find('img').attr('src', '');
    // Deleting value from the input.
    mainImageDiv.find('input').val('');

    // Showing main-image-div-add
    $('#main-image-div-add').removeClass('hidden');

    $('#appbundle_project_deleteMainImage').prop('checked', true);
}
