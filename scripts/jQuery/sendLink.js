$(document).ready(function() {
	$('#sendLink').click(function(e) {
		if($('#txtEmail').val() != ""){
			$.ajax({
				type: 'POST',
				url: 'http://localhost/scripts/php/sendLink.php',
				data: {
					'email':$('#txtEmail').val(),
				}
			}).done( (data) => {
				console.log(data);
				if(data.indexOf("error") == -1){
					var hash = '#emailSentPage';
					location.hash = hash;
					$(".page").css("display", "none");
					$(location.hash).css("display", "block");
				}else{
					$('#messageEmail').html(data);
				}
			}).fail( (data) => {
				$('#messageEmail').html("Connection failed");
			});
			e.preventDefault();
		}else{
			$('#messageEmail').html("Please enter your email.");
		}
	});
});