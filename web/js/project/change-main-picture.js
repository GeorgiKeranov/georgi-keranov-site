// Setting the bigger image as clicked small image.
function setImage(index) {
    // Getting the small image.
    var smallImage = $('#project-image-' + index);
    var bigImage = $('#big-project-image');

    // Setting the bigger image src as small image src.
    bigImage.attr('src', smallImage.attr('src'));
    // Setting the bigger image data-selected to the given index.
    bigImage.attr('data-selected', index);

    // Clearing last selected image class if have.
    $('.selected-project-image').removeClass('selected-project-image');
    // Setting the selected class to the new image.
    smallImage.addClass('selected-project-image');
}

function nextImage() {

    var bigProjectImg = $('#big-project-image');
    // Getting the image index of selected.
    var index = bigProjectImg.attr('data-selected');

    // Checking if there is image +1 to that index.
    index++;
    if($('#project-image-' + index).length) {
        setImage(index);
    } else {
        if($('#project-image-0').length)
            setImage(0);
        else
            setImage(1);
    }

}

function previousImage() {

    var bigProjectImg = $('#big-project-image');
    // Getting the image index of selected.
    var index = bigProjectImg.attr('data-selected');

    // Checking if there is image -1 to that index.
    index--;
    if($('#project-image-' + index).length) {
        setImage(index);
    } else {

        var smallPicturesCount = $('.small-project-image').length;

        if(index == 0)
            setImage(smallPicturesCount);
        else
            setImage(smallPicturesCount - 1);
    }

}

$(document).ready(function() {

    // If project-image with id 0 exists set this to the bigger image.
    if($('#project-image-0').length) {
        setImage(0);
    }

    // Else if project image with id 1 exists set this to the bigger image.
    else if($('#project-image-1').length) {
        setImage(1);
    }

    // Else set no image picture to the bigger image.
    else {
        $('#big-project-image').attr('src', '../../img/no-image-available.png');
    }

});