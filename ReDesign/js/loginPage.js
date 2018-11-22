var userName = document.getElementById("inputUserName");
var Password = document.getElementById("Password");
var Display = document.getElementById("container");
//var Button = document.getElementById("btn");

function checkLogin(form){
	userName = inputUserName.value;
	Password = Password.value;
	
	if(userName.length > 0 && Password.length > 0){
		window.alert("Login successful");
		window.location = "../html/ReHome.html";
	}
	else{
		window.display("User Name and/or password are needed");
	}
}