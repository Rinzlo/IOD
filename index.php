<!DOCTYPE html>
<html>
<head>
	<title>jQuery - Eight</title>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
	<style type="text/css">
		#box {
			width:100px;
			height:100px;
			background-color:yellow;
			border:1px solid black;
		}
	</style>
</head>
<body>

<div id="box">&nbsp;</div>

<script type="text/javascript">
$(document).ready(function() {
	$("#box").mousemove(function(e) {
		$(this).css('position', 'absolute');
		$(this).css('left', (e.screenX - 50) + 'px');
		$(this).css('top', (e.screenY - 150) + 'px');
	});
});
</script>

</body>
</html>