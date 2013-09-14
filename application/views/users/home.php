<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>
		<div id="content">
			<?php echo form_open('users'); ?>
			<?php echo form_input('username',set_value('username'),'placeholder="Username"'); ?><br/>
			<?php echo form_password('dummypassword',NULL,'placeholder="Password"'); ?><br/>
			<?php echo form_password('password',NULL,'placeholder="Repeat Password"'); ?><br/>

			<strong>Access:</strong>
			<div class="well well-small">
			<label class="checkbox">
			<?php echo form_checkbox('borrowers',TRUE,TRUE); ?> Borrowers
			</label>

			<label class="checkbox">
			<?php echo form_checkbox('agents',TRUE, TRUE); ?> Agents
			</label>

			<label class="checkbox">
			<?php echo form_checkbox('settings', TRUE, TRUE); ?> Settings
			</label>

			<label class="checkbox">
			<?php echo form_checkbox('users',TRUE,TRUE); ?> Users
			</label>
			</div>
			

			<?php echo form_submit('save','Add User','class="btn btn-primary btn-block btn-large"'); ?>
			<?php if(validation_errors()): ?>
			<div class="alert alert-error">
			<?php echo validation_errors(); ?>
			</div>
			<?php endif; ?>
			<?php echo form_close(); ?>

			<?php if($this->session->flashdata('useradd')): ?>
			<div class="alert alert-success">
				User successfully added!
			</div>
			<?php endif; ?>
			<?php if($this->session->flashdata('userdelete')): ?>
			<div class="alert alert-warning">
				User deleted!
			</div>
			<?php endif; ?>

			<?php if($users): ?>
			<table class="table table-hover table-bordered">
				<thead>
				<tr>
					<th>
						Username
					</th>
					<th>
						Borrowers
					</th>
					<th>
						Agents
					</th>
					<th>
						Settings
					</th>
					<th>
						Users
					</th>
					<th class="span1">
					</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($users as $user): ?>
				<tr>
					<td>
						<?php echo $user->username; ?>
					</td>
					<td>
						<?php echo ($user->borrowers) ? '<i class="icon-ok"></i>' : ''; ?>
					</td>
					<td>
						<?php echo ($user->agents) ? '<i class="icon-ok"></i>' : ''; ?>
					</td>
					<td>
						<?php echo ($user->settings) ? '<i class="icon-ok"></i>' : ''; ?>
					</td>
					<td>
						<?php echo ($user->users) ? '<i class="icon-ok"></i>' : ''; ?>
					</td>
					<td>
						<a href="<?php echo base_url('users/delete/'.$user->id); ?>" onclick="return confirm('Are you sure want to delete?');"><i class="icon-remove"></i></a>
					</td>
				</tr>					
				<?php endforeach; ?>
				</tbody>
			<?php endif; ?>
		</div>
	</div>
</div>