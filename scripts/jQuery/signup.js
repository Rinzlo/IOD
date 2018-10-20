$(document).ready(function() {
	$('#submitSignUp').click(function(e) {
		if(($('#signupPassword').val() === $('#signupPassRepeat').val()) && ($('#signupEmail').val().indexOf("@") != -1)){
			$.ajax({
				type: 'POST',
				url: 'http://localhost/scripts/php/signup.php',
				data: {
					'email':$('#signupEmail').val(),
					'username':$('#signupUsername').val(),
					'password':$('#signupPassword').val()
				}
			}).done( (data) => {
				console.log(data);
				if(data.indexOf("error") == -1){
					var hash = '#signupConfirmPage';
					location.hash = hash;
					$(".page").css("display", "none");
					$(location.hash).css("display", "block");
				}else{
					$('#messageSignUp').html(data);
				}
			}).fail( (data) => {
				$('#messageSignUp').html("Connection failed");
			});
			e.preventDefault();
		}else if($('#signupPassword').val() != $('#signupPassRepeat').val()){
			$('#messageSignUp').html("Passwords are not same.");
		}else{
			$('#messageSignUp').html("Email is not valid.");
		}
	});
});