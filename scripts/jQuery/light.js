$(document).ready(function() {
	$('.light').click(function(e) {
		$bri = $('#lightSlider').val();
		if($(this).attr('id') === 'onButton'){
			$on = true;
			$url = 'http://45.63.49.226:8000/api/light1/on';
			$('#lightSlider').slider({
				disabled:false
			});
		}else{
			$on = false;
			$url = 'http://45.63.49.226:8000/api/light1/off';
			$('#lightSlider').slider({
				disabled:true
			});
		}
		$.ajax({
			type: 'GET',
			url: $url,
			status: {
				'bri':$bri,
				'deviceid':"light1",
				'on':$on
			}
		}).done( (data) => {
			if($on){
				$('#outputData').html("Status:  Light is turned on.");
				$("#onButton").prop("disabled", true);
				$("#offButton").prop("disabled", false);
				$("#onButton").css('background-color', '#222222');
				$("#offButton").css('background-color', 'orange');
				$("#onButton").css('box-shadow', 'none');
				$("#offButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
			}else{
				$('#outputData').html("Status:  Light is turned off.");
				$("#onButton").prop("disabled", false);
				$("#offButton").prop("disabled", true);
				$("#onButton").css('background-color', 'orange');
				$("#offButton").css('background-color', '#222222');
				$("#onButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
				$("#offButton").css('box-shadow', 'none');
			}
		}).fail( (data) => {
			$('#outputData').html("Connection failed");
		});
		e.preventDefault();
	});
});