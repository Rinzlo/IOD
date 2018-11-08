$(document).ready(function() {
	$bri = 128;
	$on = true;
	$.ajax({
		type: 'GET',
		url: 'http://45.63.49.226:8000/api/light1/status',
		status: {
			'bri':$bri,
			'deviceid':"light1",
			'on':$on
		}
	}).done( (data) => {
		$('#outputData').html("Status:   " + data);
		if(data.indexOf("true") != -1){
			$("#onButton").prop("disabled", true);
			$("#offButton").prop("disabled", false);
			$("#onButton").css('background-color', '#222222');
			$("#offButton").css('background-color', 'orange');
			$("#onButton").css('box-shadow', 'none');
			$("#offButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
			$('#lightSlider').slider({
				disabled:false
			});
		}else{
			$("#onButton").prop("disabled", false);
			$("#offButton").prop("disabled", true);
			$("#onButton").css('background-color', 'orange');
			$("#offButton").css('background-color', '#222222');
			$("#onButton").css('box-shadow', '0px 4px 0 rgba(0, 0, 0, .7)');
			$("#offButton").css('box-shadow', 'none');
			$('#lightSlider').slider({
				disabled:true
			});
		}
	}).fail( (data) => {
		$('#outputData').html("Connection failed");
	});
	e.preventDefault();
});
