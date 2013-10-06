<div>
	<div id="mainpanel" style="width:300px; margin-left:auto; margin-right:auto;">
		<div id="topbar">
		</div>
		<div id="titlebar" style="height:20px;">
		</div>
		<div id="content">
			<br/>
			<?php if($this->session->flashdata('forgot')): ?>
			<div class="alert alert-info">
				<?php echo $this->session->flashdata('forgot'); ?>
			</div>
			<?php endif; ?>
			<?php echo form_open('login/forgot'); ?>
				<?php echo form_input('email',set_value('email'),'placeholder="Email"'); ?>
				<?php echo form_error('email'); ?>
				<?php echo form_submit('submit','Submit','class="btn"'); ?>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>