var mainImageDiv = $('#main-image-div');
var mainImageInput = mainImageDiv.find('input')[0];

// main-image-div-add opens the input to browse file for main image.
$('#main-image-div-add').click(function () {
    mainImageInput.click();
});

// When mouse is in the main-image-div showing the X for removing the image.
mainImageDiv.mouseenter( function () {
    $(this).find('span').removeClass("hidden");
}).mouseleave(function () {
    $(this).find('span').addClass('hidden');
});

// When X is clicked it is hiding main-image-div and showing main-image-div-add.
// Also deleting value from input file and src from main-image.
mainImageDiv.find('span').click(function () {
    $(this).parent().addClass("hidden");
    $('#main-image-div-add').removeClass("hidden");

    mainImageDiv.find('input').val('');
    mainImageDiv.find('img').attr('src', '');
});

// When image is uploaded to the main image input
// reading it with file reader and setting read information
// to the src of img in main-image-div.
// Also hiding main-image-div-add and showing main-image-div
mainImageInput.onchange = function (evt) {
    var tgt = evt.target || window.event.srcElement, files = tgt.files;

    // FileReader support
    if (FileReader && files && files.length) {
        var fr = new FileReader();
        fr.onload = function () {
            mainImageDiv.find('img').attr('src', fr.result);
            $('#main-image-div-add').addClass("hidden");
            mainImageDiv.removeClass("hidden");
        };
        fr.readAsDataURL(files[0]);
    }
};

// Handling all divs that have class image-add.
// And opening first empty file input from array.
$('.image-add').click(function() {

    // Getting first file input that is empty.
    for(var i = 0; i<6; i++) {
        var currentInput = $("#appbundle_project_imageFiles_" + i);

        if(!currentInput.val()) {
            currentInput.click();
            break;
        }
    }

});

// Getting all the inputs for images
// And setting onchange event for all
$('#images :input').each(function (index) {

    var input = $(this);

    // If the input file is changed hiding the div for adding image
    // And showing the div with img and setting that img from the file.
    input[0].onchange = function (evt) {
        var tgt = evt.target || window.event.srcElement, files = tgt.files;
        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                // Finding the first image-div that is hidden
                for(var i = 1; i <= 6; i++) {
                    var currentImageDiv = $("#image-div-" + i);
                    if(currentImageDiv.hasClass("hidden")) {
                        // Setting the img src from this div
                        // with the input image.
                        currentImageDiv.find('img').attr('src', fr.result);
                        // Hiding the div for adding image.
                        currentImageDiv.parent().find('.image-add').addClass('hidden');
                        // Showing the div with the image.
                        currentImageDiv.removeClass('hidden');

                        break;
                    }
                }
            };
            fr.readAsDataURL(files[0]);
        }
    };
});

var images = $('#images');

// When mouse is on div with .image-view class show X for deleting image.
images.find('.image-view').mouseenter(function() {
    $(this).find('span').removeClass('hidden');
}).mouseleave(function () {
    $(this).find('span').addClass('hidden');
});

// When X is clicked it is hiding parent and showing div with class image-add.
// Also deleting value from input file and src from img.
images.find('span').click(function () {
    $(this).parent().addClass('hidden');
    $(this).parent().parent().find('.image-add').removeClass('hidden');

    $(this).parent().find('input').val('');
    $(this).parent().find('img').attr('src', '');

    // Getting the input of the deleted image
    var deletedInput = $(this).parent().find('input');
    deletedInput.detach().appendTo($("#input-temp"));

    //If next images have inputs put them with 1 element forward.
    var divIdName = $(this).parent().attr('id');
    if(divIdName !== undefined) {
        var divNumber = parseInt(divIdName.charAt(10));

        for(var i = divNumber + 1; i<6; i++) {
            var currentImageDiv = $("#image-div-" + i);

            // If the div has not class 'hidden'.
            if(!currentImageDiv.hasClass('hidden')) {

                var previousImageDiv = $("#image-div-" + (i - 1));

                // Setting img src from this imageDiv to previous imageDiv.
                previousImageDiv.find('img').attr('src', currentImageDiv.find('img').attr('src'));

                // Deleting img src from this imageDiv.
                currentImageDiv.find('img').attr('src', '');

                console.log("Current to previous: " + i + " to " + (i - 1));
                // Moving input from current div to previous one.
                var currentInput = currentImageDiv.find('input');
                currentInput.detach().appendTo(previousImageDiv);

                // Hiding this imageDiv and showing image-add.
                currentImageDiv.addClass('hidden');
                currentImageDiv.parent().find('.image-add').removeClass('hidden');

                // Hiding previous image-add and showing imageDiv.
                previousImageDiv.parent().find('.image-add').addClass('hidden');
                previousImageDiv.removeClass('hidden');
            } else {
                // Moving input from the temp div to the last div that is empty.
                $("#input-temp").find("input").detach().appendTo($("#image-div-" + (i - 1)));
                break;
            }
        }
    }
});