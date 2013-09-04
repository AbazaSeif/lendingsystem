<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>
		<div id="content">
			<ul class="breadcrumb">
			  <li><a href="<?php echo base_url();?>agents">Agents</a> <span class="divider">/</span></li>
			  <li class="active">Add Agent</li>
			</ul>


			<div id="form_input">
			<?php echo form_open('agents/add'); ?>
			<table width="100%" align="center" cellpadding="5">
				<tr>
					<td>
					<?php echo form_input('lastname', set_value('lastname'),'placeholder="Last Name"'); ?>
					<?php echo form_input('firstname', set_value('firstname'),'placeholder="First Name"'); ?>
					<?php echo form_input('middlename', set_value('middlename'),'placeholder="Middle Name"'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo form_textarea('address', set_value('address'),'placeholder="Address" style="width:450px;"'); ?>
					</td>
				</tr>
				<tr>
					<td>
						<div class="input-prepend">
						<span class="add-on">+63</span>
						<?php echo form_input('contact', set_value('contact'), 'id="appendedInput" placeholder="Contact Number" style="width:210px;"'); ?>
						</div>
					</td>
				</tr>
				<tr>
					<td>
				<?php if(validation_errors()): ?>
	  			<div class="alert alert-error">
	  			<?php echo validation_errors(); ?>
	  			</div>
	  			<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo form_submit('save', 'Submit','class="btn btn-primary btn-block btn-large"'); ?>
					</td>
				</tr>
			</table>
			<?php echo form_close(); ?>
			</div>
			<?php if($agents): ?>
			<h2>Agents List :</h2>
			<table class="table table-hover">
				<tr>
					<th>
						ID
					</th>
					<th>
						Name
					</th>
					<th>
						Address
					</th>
					<th>
						Actions
					</th>
				</tr>
				
				<?php foreach($agents as $row): ?>
				<tr>
					<td>
						<b><?php echo $row->id ;?></b>
					</td>
					<td>
						<?php echo $row->lastname; ?>,  
						<?php echo $row->firstname; ?>,  
						<?php echo $row->middlename; ?>
					</td>
					<td>
						<?php echo $row->address; ?>
					</td>
					<td>
						<a href="<?php echo base_url(); ?>agents/view/<?php echo $row->id; ?>"><i class="icon-th-large" title="View"></i></a>						
						<a href="<?php echo base_url(); ?>agents/delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure want to delete?');"><i class="icon-remove" title="Delete"></i></a>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>

		</div>
	</div>
</div>