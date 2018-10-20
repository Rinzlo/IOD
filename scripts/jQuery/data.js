$(document).ready(function() {
	$('.light').click(function(e) {
		if($(this).attr('id') === 'onButton'){
			$on = true;
		}else{
			$on = false;
		}
		console.log($on);
		$.ajax({
			type: 'POST',
			url: 'http://localhost/scripts/php/data.php',
			data: {
				'deviceid':"light",
				'on':$on
			}
		}).done( (data) => {
			if(data.indexOf("error") == -1){
				$('#outputData').html("Status:   " + data);
				if($on){
					$("#onButton").prop("disabled", true);
					$("#offButton").prop("disabled", false);
					$("#onButton").css('background-color', '#222222');
					$("#offButton").css('background-color', 'orange');
					$("#onButton").css('box-shadow', 'none');
					$("#offButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
				}else{
					$("#onButton").prop("disabled", false);
					$("#offButton").prop("disabled", true);
					$("#onButton").css('background-color', 'orange');
					$("#offButton").css('background-color', '#222222');
					$("#onButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
					$("#offButton").css('box-shadow', 'none');
					
				}
			}else{
				$('#outputData').html("Access failed.");
			}
		}).fail( (data) => {
			$('#outputData').html("Connection failed");
		});
		e.preventDefault();
	});
});