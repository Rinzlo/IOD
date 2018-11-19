$(document).ready(function() {
	$('#passwordResetButton').click(function(e) {
		if($('#resetPassword').val() === $('#resetPasswordRepeat').val()){
			$.ajax({
				type: 'POST',
				url: 'http://localhost/scripts/php/resetPassword.php',
				data: {
					'username':$('#resetUsername').val(),
					'password':$('#resetPassword').val()
				}
			}).done( (data) => {
				console.log(data);
				if(data.indexOf("error") == -1){
					var hash = '#resetConfirmPage';
					location.hash = hash;
					$(".page").css("display", "none");
					$(location.hash).css("display", "block");
				}else{
					$('#messageReset').html(data);
				}
			}).fail( (data) => {
				$('#messageReset').html("Connection failed");
			});
			e.preventDefault();
		}else{
			$('#messageReset').html("Passwords are not same.");
		}
	});
});