var imagesCount = $('.small-project-image').length;

if($('#project-image-0').length)
    imagesCount--;

var currentImageNum;

// Open the Modal
function openModal() {
    // Get clicked image src and set it on the lightbox.
    currentImageNum = $('#big-project-image').attr('data-selected');
    var imageSrc = $('#project-image-' + currentImageNum).attr('src');

    $('#lightbox').attr('src', imageSrc);

    // Set number of image selected text.
    $('.number-text').text(currentImageNum + ' / ' + imagesCount);

    $('.modal').css("display", "block");
}

// Close the Modal
function closeModal() {
    $('.modal').css("display", "none");
}

function setImageLightbox(index) {

    // Getting image src.
    var imageSrc = $('#project-image-' + index).attr('src');
    // Setting lightbox image src.
    $('#lightbox').attr('src', imageSrc);

    // Set number of image selected text.
    $('.number-text').text(index + ' / ' + imagesCount);
}

function nextImageLightbox() {

    currentImageNum++;

    // Check if we have image with index plus 1.
    if($('#project-image-' + currentImageNum).length) {
        setImageLightbox(currentImageNum);
    } else {
        if($('#project-image-0').length)
            currentImageNum = 0;
        else
            currentImageNum = 1;

        setImageLightbox(currentImageNum);
    }
}

function previousImageLightbox() {

    currentImageNum--;

    // Check if we have image with index minus 1.
    if($('#project-image-' + currentImageNum).length) {
        setImageLightbox(currentImageNum);
    } else {

        currentImageNum = imagesCount;
        setImageLightbox(currentImageNum);
    }
}
