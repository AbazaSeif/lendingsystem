<div>
	<div id="mainpanel" style="width:300px; margin-left:auto; margin-right:auto;">
		<div id="topbar">
		</div>
		<div id="titlebar" style="height:20px;">
		</div>
		<div id="content">
			<br/>
			<?php if($this->session->flashdata('reset')): ?>
			<div class="alert alert-info">
				<?php echo $this->session->flashdata('reset'); ?>
			</div>
			<?php endif; ?>
			<?php echo form_open('login/index'); ?>
				<?php $data = array('name' => 'username','value' => set_value('username'), 'placeholder' => 'Username');
				echo form_input($data); ?>
				<br/>
				<?php echo form_error('username'); ?>

				<?php $data = array('name' => 'password', 'placeholder' => 'Password');
				echo form_password($data); ?>
				<br/>
				<?php echo form_error('password'); ?>
				
				<?php echo form_submit('login','Login','class="btn"'); ?>
				<?php echo $error; ?>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>