var dataPrototypeImage = '<div class="col margin-bot-10">\n' +
    '        <div class="image-view center">\n' +
    '            <span class="fa fa-remove hidden" onclick="deleteAddedImage(index)"></span>\n' +
    '            <img class="img-thumbnail center" src=""/>\n' +
    '            <input type="file" class="hidden"\n' +
    '                   id="appbundle_project_imageFiles_index"\n' +
    '                   name="appbundle_project[imageFiles][index]"/>\n' +
    '        </div>\n' +
    '    </div>';

// For every new image we are adding + 1 to the index.
var newImagesIndex = 0;

function addNewImage() {

    $('#images').prepend(dataPrototypeImage.replace(/index/g, newImagesIndex));
    var newInput = $('#appbundle_project_imageFiles_' + newImagesIndex);

    // Hiding the new image until we have a file in the input.
    newInput.parent().parent().addClass('hidden');

    addOnChange(newInput);

    newInput.click();

    newImagesIndex++;
}

function addOnChange(input) {

    input[0].onchange = function (evt) {

        var tgt = evt.target || window.event.srcElement, files = tgt.files;
        // FileReader support
        if (FileReader && files && files.length) {
            var fr = new FileReader();
            fr.onload = function () {
                input.parent().find('img').attr('src', fr.result);
                input.parent().parent().removeClass('hidden');
            };
            fr.readAsDataURL(files[0]);
        }
    };
}

function deleteAddedImage(index) {
    $('#appbundle_project_imageFiles_' + index).parent().parent().remove();
}

var dataPrototypeDeleteImage = '' +
    '<input type="text" ' +
    '   id="appbundle_project_deleteImages_index" ' +
    '   name="appbundle_project[deleteImages][index]" ' +
    '   value="__NAME__" class="hidden"/>';

// For every new input for name of the deleted image.
var deleteImagesIndex = 1;

function deleteExistingImage(name, index) {

    var newImageForDelete = dataPrototypeDeleteImage;
    newImageForDelete = newImageForDelete.replace(/index/g, deleteImagesIndex);
    newImageForDelete = newImageForDelete.replace("__NAME__", name);

    deleteImagesIndex++;

    // Creating new input with the name of the image for delete.
    $('#images').append(newImageForDelete);

    // Removing the div with the image by given index.
    $('#existing-image-' + index).remove();
}