$(document).ready(function() {
	$('form').validate({
		rules: {
			loginUsername: {
				required: true,
				minlength: 6
			},
			loginPassword: {
				required: true,
				minlength: 6
			},
			loginEmail: {
				required: true,
				email: true
			}
		},
		
		messages:{
			loginUsername: {
				required: "Enter Username."
			},
			loginPassword: {
				minlength: "Enter more than 6 characters."
			},
			loginEmail: {
				email: "Non valid email."
			}
		},
		
	});
	$('form').validate();
});
