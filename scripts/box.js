<script type="text/javascript">
$(document).ready(function() {
	$("#box").mousemove(function(e) {
		$(this).css('position', 'absolute');
		$(this).css('left', (e.screenX - 50) + 'px');
		$(this).css('top', (e.screenY - 150) + 'px');
	});
});
</script>