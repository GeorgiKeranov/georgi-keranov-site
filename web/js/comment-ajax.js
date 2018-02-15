var commentPrototype = "<div class=\"row margin-top-10\">\n" +
    "        <div class=\"col\">\n" +
    "            <div class=\"row gray\">\n" +
    "                <div class=\"col-md-2 text-center\">\n" +
    "                    <img class=\"profile-pic-comment\"\n" +
    "                         src=\"__PROFILE_PIC_URL__\">\n" +
    "                </div>\n" +
    "                <div class=\"col-md-10\">\n" +
    "                    <div class=\"card border-secondary mb-3\" style=\"width: 100%\">\n" +
    "                        <div class=\"card-header\">\n" +
    "                            <span class=\"float-left\">__FULL_NAME__</span>\n" +
    "                            <span class=\"float-right\">__DATE_CREATED__</span>\n" +
    "                        </div>\n" +
    "                        <div class=\"card-body\">\n" +
    "                            <p class=\"card-text\">__COMMENT__</p>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </div>\n" +
    "            </div>\n" +
    "        </div>\n" +
    "    </div>";

function comment() {

    // Lock textarea and button.
    var comment = $('#new-comment');
    comment.attr('disabled', 'disabled');
    $('.btn-outline-success').attr('disabled', 'disabled');

    var fd = new FormData;
    fd.append('comment', comment.val());

    $.ajax({
        url: window.location.href + "/comment",
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
        success: createNewCommentInDOM
    });
}

function createNewCommentInDOM(data) {

    // Enabling textarea and button for new comment.
    // Also removing text from the comment textarea.
    var comment = $('#new-comment');
    comment.val("");
    comment.removeAttr('disabled');
    $('.btn-outline-success').removeAttr('disabled');

    // Adding new comment in the dom by given data.
    var profilePicUrl = '../../';
    if(data['profilePicture'])
        profilePicUrl += 'uploads/images/' + data['profilePicture'];
    else
        profilePicUrl += 'img/no-profile-picture.png';

    var newComment = commentPrototype;
    newComment = newComment.replace('__PROFILE_PIC_URL__', profilePicUrl);
    newComment = newComment.replace('__FULL_NAME__', data['user']['fullName']);
    newComment = newComment.replace('__DATE_CREATED__', data['created']);
    newComment = newComment.replace('__COMMENT__', data['comment']);

    $('#new-comments-ajax').prepend(newComment);

    // Adding plus one to count of comments.
    var commentsCount = $('#project-comments')[0];
    commentsCount.innerText = (Number(commentsCount.innerText) + 1);
}