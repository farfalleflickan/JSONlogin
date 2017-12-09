var modal = document.getElementById("myModal");
var modalText = document.getElementById("myModalMessage");

function printModal(text) {
    showModal();
    modalText.innerHTML = text;
}

function showModal() {
    modal.style.display = "block";
}

function hideModal() {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target === modal) {
        modal.style.display = "none";
    }
};

function cgiScript(newUrl) {
    var cgiRequest = new XMLHttpRequest();
    cgiRequest.open("POST", "login.php", true);
    cgiRequest.responseType = 'text';
    cgiRequest.onload = function () {
        if (cgiRequest.status === 200) {
            var res = cgiRequest.responseText;
            if (res === "0") {
                document.location.href = newUrl;
            } else if (res === "2") {
                printModal("Your user is awaiting confirmation, contact your webadmin for more information");
            } else if (res === "1") {
                printModal("Wrong username or password!");
            } else if (res === "-1") {
                printModal("Something went wrong...<br>Reloading page...");
                location.reload();
            } else {
                printModal(res);
            }
        }
    };
    cgiRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var usrName = "", usrPwd = "", mode = 0;
    usrName = document.getElementById("usrname").value;
    usrPwd = document.getElementById("usrpwd").value;
    if (usrName !== "" && usrPwd !== ""){
        if (document.getElementById("myCheckBox").checked) {
            mode = 1;
        } else {
            mode = 0;
        }
        cgiRequest.send("usrName=" + usrName + "&usrPwd=" + usrPwd + "&phpMode=" + mode);
    } else {
        printModal("Username and/or password can't be empty");
    } 
}

document.addEventListener("keydown", function (e) {
	if (e.keyCode === 13) {
		document.getElementById("formSubmitButton").click();
	} else if (e.keyCode === 27) {
		modal.style.display = "none";
	}
});
