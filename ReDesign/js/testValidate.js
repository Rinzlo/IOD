$(document).ready(function() {
	$('#submitLogin').click(function(e) {
		console.log("clicked");
		$('form').validate({
			rules: {
				userName: {
					required: true,
					minlength: 6
				},
				password: {
					required: true,
					minlength: 6
				}
			},
		
			messages:{
				userName: {
					required: "Enter Username."
				},
				password: {
					minlength: "Enter more than 6 characters."
				}
			}
		
		});
		$('form').validate();
		if($('form').valid()){
			var hash = '#homePage';
			location.hash = hash;
			$(".page").css("display", "none");
			$(location.hash).css("display", "block");
			console.log("validated");
		}else{
			console.log("not validated");
		}
	});
});
