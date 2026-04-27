
function validateStudentId(id){
    let regex = /S\d\d\d\d\d\d\d\d/;
    let result = regex.exec(id.value);
    let errorMsg = document.querySelector("#iderror");
    errorMsg.innerHTML = "";

    if(result === null){
        errorMsg.innerHTML = "ID must begin with S followed by 8 numbers ex) S0123456"
        return false;
    }

    return true;

}

function validatePassword(password){
    let regex = /[a-zA-Z0-9\S]+/;
    let min = 8;
    let result = regex.exec(password.value);
    let errorMsg = document.querySelector("#passworderror");
    errorMsg.innerHTML = "";

    if(result === null || result[0].length < min){
        errorMsg.innerHTML = "Password must contain atleast 8 characters with no whitespace"
        return false;
    }

    return true;
}

function validateConfirmation(password, confirmation){
    let errorMsg = document.querySelector("#confirmationerror");
    errorMsg.innerHTML = "";

    if(confirmation.value === "" || password === null){errorMsg.innerHTML = "A password must be entered"; return false;}
    else if((password.value !== confirmation.value)){
        console.log("The password was " + password.value + " with confirmation " + confirmation.value)
        errorMsg.innerHTML = "Passwords must match";
        return false;
    }

    return true;

}

function accountVerification(event){

    if(this.id ==="newuser"){
        let studentId = document.getElementById("newid");
        let password = document.getElementById("createpassword");
        let confirmation = document.getElementById("confirmpassword");
        let idValid = true;
        let passwordValid = true;
        let confirmValid = true;
        event.preventDefault();

        idValid = validateStudentId(studentId);
        passwordValid = validatePassword(password);
        confirmValid = validateConfirmation(password, confirmation);

        if(idValid && passwordValid && confirmValid){
            //alert("Account created successfully!\nPlease login via Existing User Login.");
            newUserForm.submit();
        }

        
    }
    else if(this.id ==="existinguser"){

    }


}

function resetErrors(){
    let idErrorMsg = document.querySelector("#iderror");
    idErrorMsg.innerHTML = "";
    let passwordErrorMsg = document.querySelector("#passworderror");
    passwordErrorMsg.innerHTML = "";
    let confirmationErrorMsg = document.querySelector("#confirmationerror");
    confirmationErrorMsg.innerHTML = "";
}

let newUserForm = document.querySelector("#newuser");
let existingUserForm = document.querySelector("#existinguser");
newUserForm.addEventListener("submit", accountVerification);
newUserForm.addEventListener("reset", resetErrors);
existingUserForm.addEventListener("submit", accountVerification);
existingUserForm.addEventListener("reset", resetErrors);