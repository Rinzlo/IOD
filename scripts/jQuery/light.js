$(document).ready(function() {
	$('.light').click(function(e) {
		$bri = $('#lightSlider').val();
		if($(this).attr('id') === 'onButton'){
			$on = true;
			$url = 'http://45.63.49.226:8000/api/light1/on';
		}else{
			$on = false;
			$url = 'http://45.63.49.226:8000/api/light1/off';
		}
		console.log($bri);
		$.ajax({
			type: 'POST',
			url: $url,
			dataType: 'jsonp',
			status: {
				'bri':$bri,
				'deviceid':"light1",
				'on':$on
			}
		}).done( (data) => {
			if(data.indexOf("error") == -1){
				$('#outputData').html("Status:   " + data);
			}else{
				$('#outputData').html("Access failed.");
			}
		}).fail( (data) => {
			$('#outputData').html("Connection failed");
		});
		e.preventDefault();
	});
});