var commentPrototype = "<div id=\"comment-__COMMENT_ID__\" class=\"row margin-top-10\">\n" +
    "        <div class=\"col\">\n" +
    "            <div class=\"row gray\">\n" +
    "                <div class=\"col-md-2 text-center\">\n" +
    "                    <img class=\"profile-pic-comment\"\n" +
    "                         src=\"__PROFILE_PIC_URL__\">\n" +
    "                </div>\n" +
    "                <div class=\"col-md-10\">\n" +
    "                    <div id=\"comment-details-__COMMENT_ID__\" class=\"card border-secondary mb-3\" style=\"width: 100%\">\n" +
    "                        <div class=\"card-header\">\n" +
    "                            <span class=\"float-left\">__FULL_NAME__ commented on __DATE_CREATED__</span>\n" +
    "                            <span class=\"float-right\">\n" +
    "                                <i class=\"fa fa-edit edit-comment\" onclick=\"showEditComment(__COMMENT_ID__)\"></i> <i class=\"fa fa-remove delete-comment\" onclick=\"deleteComment(__COMMENT_ID__)\"></i>\n" +
    "                            </span>" +
    "                        </div>\n" +
    "                        <div class=\"card-body\">\n" +
    "                            <p class=\"card-text\">__COMMENT__</p>\n" +
    "                        </div>\n" +
    "                    </div>\n" +
    "                </div>\n" +
    "            </div>\n" +
    "        </div>\n" +
    "    </div>";

var editCommentPrototype = "\n" +
    "                    <div id=\"edit-comment-__COMMENT_ID__\">\n" +
    "                        <textarea class=\"form-control\" type=\"text\">__COMMENT_CONTENT__</textarea>\n" +
    "                        <div class=\"margin-top-10\">\n" +
    "                            <button class=\"btn btn-outline-warning\" onclick=\"editComment(__COMMENT_ID__)\">Edit</button>\n" +
    "                            <button class=\"btn btn-outline-danger\" onclick=\"cancelEditComment(__COMMENT_ID__)\">Cancel</button>\n" +
    "                        </div>\n" +
    "                    </div>";


// Getting the url of the site
// Example: https://site.com/
var url = window.location.href.split("/");
url = url[0] + "//" + url[2] + "/";

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
    newComment = newComment.replace(/__COMMENT_ID__/g, data['id']);
    newComment = newComment.replace('__PROFILE_PIC_URL__', profilePicUrl);
    newComment = newComment.replace('__FULL_NAME__', data['user']['fullName']);
    newComment = newComment.replace('__DATE_CREATED__', data['created']);
    newComment = newComment.replace('__COMMENT__', data['comment']);

    $('#new-comments-ajax').prepend(newComment);

    // Adding plus one to count of comments.
    var commentsCount = $('#project-comments')[0];
    commentsCount.innerText = (Number(commentsCount.innerText) + 1);
}

function showEditComment(commentId) {
    var commentDetails = $('#comment-details-' + commentId);
    var commentContent = commentDetails.find('p').text();

    var editComment = editCommentPrototype;
    editComment = editComment.replace(/__COMMENT_ID__/g, commentId);
    editComment = editComment.replace('__COMMENT_CONTENT__', commentContent);

    commentDetails.addClass('hidden');
    $('#comment-' + commentId).find('.col-md-10').append(editComment);
}

function editComment(commentId) {

    var editedComment = $('#edit-comment-' + commentId).find('textarea').val();

    var fd = new FormData();
    fd.append('comment', editedComment);

    $.ajax({
        url: url + "comment/" + commentId + "/edit",
        data: fd,
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (data) {
            $('#edit-comment-' + commentId).remove();
            var commentDetails = $('#comment-details-' + commentId);
            commentDetails.find('p').text(editedComment);
            commentDetails.removeClass('hidden');
        }
    });
}

function cancelEditComment(commentId) {
    $('#edit-comment-' + commentId).remove();
    $('#comment-details-' + commentId).removeClass('hidden');
}

function deleteComment(commentId) {
    $.ajax({
        url: url + "comment/" + commentId + "/delete",
        processData: false,
        contentType: false,
        type: 'POST',
        success: function (data) {

            if(data['error'])
                console.log(data['message']);

            if(!data['error']) {
                $('#comment-' + commentId).remove();
                var commentsCount = $('#project-comments')[0];
                commentsCount.innerText = (Number(commentsCount.innerText) - 1);
            }
        }
    });
}
