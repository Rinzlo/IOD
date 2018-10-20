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
				$('#outputData').html(data);
				if($on){
					$("#onButton").prop("disabled", true);
					$("#offButton").prop("disabled", false);
				}else{
					$("#offButton").prop("disabled", true);
					$("#onButton").prop("disabled", false);
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