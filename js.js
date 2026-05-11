//kiểm tra độ dài và trùng khớp mật khẩu

function checkPassword() {

    if (password.value.length < 8) {

        err_pass.textContent =
            "Mật khẩu phải từ 8 ký tự";

    }

    else if (
        confirm_password.value !== "" &&
        password.value !== confirm_password.value
    ) {

        err_pass.textContent =
            "Mật khẩu không khớp";
    }

    else {

        err_pass.textContent = "";
    }
}

if(typeof password !== "undefined"){

    password.addEventListener(
        "input",
        checkPassword
    );

    confirm_password.addEventListener(
        "input",
        checkPassword
    );
}


//tab 
function ShowSection(sectionId){
    let sections = document.querySelectorAll(".section");
    sections.forEach(section => {
        section.style.display = "none";
    });
    document.getElementById(sectionId)
        .style.display = "block";
}

/* LOAD */
window.onload = function(){

    let hash = window.location.hash;
    /* có hash */
    if(hash){
        ShowSection(
            hash.replace("#", "")
        );
    }
    /* mặc định */
    else{
        ShowSection("order_section");
    }
}
/* hiện form */

function showForm(){
    document.getElementById(
        "productForm"
    ).style.display = "block";
}

function closeForm(){
    document.getElementById(
        "productForm"
    ).style.display = "none";
}

// mo sửa role
function openRoleForm( id,name,role){
    document.getElementById(
        "roleForm"
    ).style.display = "block";
    document.getElementById(
        "role_user_id"
    ).value = id;
    document.getElementById(
        "role_name"
    ).value = name;
    document.getElementById(
        "role_select"
    ).value = role;
}

function closeRoleForm(){
    document.getElementById(
        "roleForm"
    ).style.display = "none";
}

