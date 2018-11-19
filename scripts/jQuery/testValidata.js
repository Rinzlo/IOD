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
				required: "Enter Username.<br>"
			},
			loginPassword: {
				minlength: "Enter more than 6 characters.<br>"
			},
			loginEmail: {
				email: "Non valid email.<br>"
			}
		},
		
		errorPlacement: function(err, elem){
			err.appendTo($('p'));
		}
	});
	$('form').validate();
});
