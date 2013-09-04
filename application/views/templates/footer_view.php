</div>
</div>

<div id="footer">
	<br/><br/><br/><br/>
</div>



<script type="text/javascript">
$(document).ready(function() {
	$("#main").draggable({containment : "body", handle : "#topbar", stop: function(event, ui) {
		$.ajax({
			url : "<?php echo base_url('home/ajaxPosition'); ?>",
			type : 'POST',
			data : {
				'top' : $("#main").css('top'),
				'left' : $("#main").css('left'),
			}
		});
	}});

	$("#main").css({
		position : 'relative',
		top : "<?php echo $this->session->userdata('top'); ?>",
		left : "<?php echo $this->session->userdata('left'); ?>"
	});
});
</script>
</body>
</html>