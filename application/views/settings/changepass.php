<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>
		<div id="content">
			
		<div class="well">
			<?php if($this->session->flashdata('changepass')): ?>
			<div class="alert alert-success">
				Password <strong>Changed!</strong>
			</div>
			<?php endif; ?>
		<?php echo form_open('settings/profile'); ?>

		<?php echo form_label('New Password:','newpassword'); ?>
		<?php echo form_password('newpassword',set_value('newpassword'),'class="input-medium"'); ?>
		<?php echo form_error('newpassword'); ?>
		<?php echo form_label('Repeat:','repeat'); ?>
		<?php echo form_password('repeat',set_value('repeat'),'class="input-medium"'); ?>
		<?php echo form_error('repeat'); ?>
		<br/>
		<?php echo form_submit('save','Save','class="btn btn-primary"'); ?>
		<?php echo form_close(); ?>
		</div>
		</div>
	</div>
</div>