function addRole(userId) {
    swal({
            title: "Add new role!",
            text: "Role name:",
            type: "input",
            showCancelButton: true,
            inputPlaceholder: "Example: ROLE_USER"
        },
        function(inputValue){
            console.log(inputValue);
        });
}

function deleteRole(userId) {

}