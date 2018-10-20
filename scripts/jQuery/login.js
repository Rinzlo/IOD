$(document).ready(function() {
	$('#submitLogin').click(function(e) {
		if($('#chbRememberMe').val() == "on"){
			$.cookie("Username", $('#txtUsername').val(), {expires: 14});
			$.cookie("Password", $('#txtPassword').val(), {expires: 14});
		}
		$.ajax({
			type: 'POST',
			url: 'http://localhost/scripts/php/login.php',
			data: {
				'username':$('#txtUsername').val(),
				'password':$('#txtPassword').val(),
				'remember':$('#chbRememberMe').val()
			}
		}).done( (data) => {
			if(data.indexOf("error") == -1){
				var hash = '#userPage';
				location.hash = hash;
				$(".page").css("display", "none");
				$(location.hash).css("display", "block");
				$('#username').html("Welcome, " + data + " !!");
				$('#userButtonLogin').show();
				$('#userButtonLogout').hide();
				$('#logout').show();
			}else{
				console.log(data);
				$('#messageLogin').html(data);
			}
		}).fail( (data) => {
			$('#messageLogin').html("Connection failed");
		});
		e.preventDefault();
	});
});