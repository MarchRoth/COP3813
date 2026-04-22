
function validateStudentId(id, event){
    let regex = /S\d\d\d\d\d\d\d\d/;
    let result = regex.exec(id.value);
    let errorMsg = document.querySelector("#iderror");
    errorMsg.innerHTML = "";

    if(result === null){
        event.preventDefault();
        errorMsg.innerHTML = "ID must begin with S followed by 8 numbers ex) S0123456"
    }

}

function validatePassword(password, event){
    let regex = /[a-zA-Z0-9\S]+/;
    let min = 8;
    let result = regex.exec(password.value);
    let errorMsg = document.querySelector("#passworderror");
    errorMsg.innerHTML = "";

    if(result === null || password.value.length < min){
        event.preventDefault();
        errorMsg.innerHTML = "Password must contain atleast 8 characters with no whitespace"
    }
    

}

function accountVerification(event){

    if(this.id ==="newuser"){
        let studentId = document.getElementById("newid");
        let password = document.getElementById("createpassword");

        validateStudentId(studentId, event);
        validatePassword(password, event);




    }
    else if(this.id ==="existinguser"){

    }


}

function resetErrors(){
    let idErrorMsg = document.querySelector("#iderror");
    idErrorMsg.innerHTML = "";
    let passwordErrorMsg = document.querySelector("#passworderror");
    passwordErrorMsg.innerHTML = "";
}

let newUserForm = document.querySelector("#newuser");
let existingUserForm = document.querySelector("#existinguser");
newUserForm.addEventListener("submit", accountVerification);
newUserForm.addEventListener("reset", resetErrors);
existingUserForm.addEventListener("submit", accountVerification);
existingUserForm.addEventListener("reset", resetErrors);