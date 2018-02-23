// Getting the url of the site
// Example: https://site.com/
var url = window.location.href.split("/");
url = url[0] + "//" + url[2] + "/";


function addRole(userId) {

    swal({
            title: "Add new role!",
            text: "Role name:",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            confirmButtonText: "Add it!",
            inputPlaceholder: "Example: ROLE_USER"
        },
        function(inputValue) {

            if(inputValue == false) {
                return;
            }

            var fd = new FormData();
            fd.append('user_id', userId);
            fd.append('role_name', inputValue);

            $.ajax({
                url: url + 'admin/role/add',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {

                    if(data['error']) {
                        swal('Error', data['message'], 'error');
                    }

                    else {
                        swal('Success', 'Role added successfully.', 'success');

                        var userRoles = $('#user-' + userId).find('td')[3];
                        userRoles.innerText += ', ' + inputValue;
                    }
                }
            });

        });
}

function deleteRole(userId) {

    swal({
        title: "Delete role!",
        text: "Role name:",
        type: "input",
        showCancelButton: true,
        closeOnConfirm: false,
        confirmButtonText: "Delete it!",
        confirmButtonColor: 'red',
        inputPlaceholder: "Example: ROLE_USER"
        },
        function(inputValue) {
            if(inputValue == false) {
                return;
            }

            var fd = new FormData();
            fd.append('user_id', userId);
            fd.append('role_name', inputValue);

            $.ajax({
                url: url + 'admin/role/delete',
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {

                    if(data['error']) {
                        swal('Error', data['message'], 'error');
                    }

                    else {
                        swal('Success', 'Role deleted successfully.', 'success');

                        var userRoles = $('#user-' + userId).find('td')[3];
                        var text = userRoles.innerText;

                        text = text.replace(inputValue + ', ', '');
                        text = text.replace(inputValue, '');

                        userRoles.innerText = text;
                    }
                }
            });

        });
}

function deleteUser(userId) {

    swal({
            title: "Are you sure?",
            text: "Are you sure you want to delete this user?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "Delete it!",
            confirmButtonColor: "#ec6c62"
        },
        function() {

            $.ajax({
                url: url + 'admin/user/delete/' + userId,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {

                    if(data['error']) {
                        swal('Error', data['message'], 'error');
                    }

                    else {
                        swal('Success', 'User deleted successfully.', 'success');

                        $('#user-' + userId).remove();
                    }
                }
            });

        });
}
