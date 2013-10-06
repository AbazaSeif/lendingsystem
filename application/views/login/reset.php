<div>
	<div id="mainpanel" style="width:300px; margin-left:auto; margin-right:auto;">
		<div id="topbar">
		</div>
		<div id="titlebar" style="height:20px;">
		</div>
		<div id="content">
			<br/>
			<h3>Reset Password</h3>
			<?php echo form_open('login/reset'.$this->uri->segment(3)); ?>
				<?php echo form_password('dummypassword',NULL,'placeholder="Password"'); ?>
				<?php echo form_error('dummypassword'); ?>

				<?php echo form_password('password', NULL, 'placeholder="Retype Password"'); ?>
				<?php echo form_error('password'); ?>

				<?php echo form_submit('submit','Submit','class="btn"'); ?>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>