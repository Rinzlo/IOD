$(document).ready(function() {
	$( "#lightSlider" ).slider( {
		min: 0,
		max: 255,
		step: 1,
		value: 128,
		//disabled: true,
		slide:function(event, ui) {
			$bri = ui.value;
			$url = 'http://45.63.49.226:8000/api/light1/bri/' + String($bri);
			$('#showBrightness').html($bri);
			$.ajax({
				type: 'GET',
				url: $url,
				status: {
					'bri':$bri,
					'deviceid':"light1",
					'on':true
				}
			}).done( (data) => {
				if(data.indexOf("error") == -1){
					$('#showBrightness').html($bri);
				}else{
					$('#outputData').html("Access failed.");
				}
			}).fail( (data) => {
				$('#outputData').html("Connection failed");
			})
		}
	});
});