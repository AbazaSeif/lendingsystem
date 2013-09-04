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
			  <li class="active">Search</li>
			</ul>
			<div>
				<?php echo form_open('agents/search'); ?>
	  			<?php echo form_input('search', set_value('search'),'class="span2" placeholder="Search..." '); ?>
	  			<?php $data = array(
	  				0 => 'Search by',
	  				'lastname' => 'Last Name',
	  				'firstname' => 'First Name',
	  				'middlename' => 'Middle Name',
	  				'contact' => 'Contact Number',
	  				'address' => 'Address'
	  				);
	  				?>
	  			<?php echo form_dropdown('search_by', $data ,set_value('search_by'), ''); ?>
	  			<?php if(validation_errors()): ?>
	  			<div class="alert alert-error">
	  			<?php echo validation_errors(); ?>
	  			</div>
	  			<?php endif; ?>
	  			<?php echo form_submit('submit', 'Search', 'class="btn-block btn-primary btn btn-large"'); ?>
	  			

	  			<?php echo form_close(); ?>
  			</div>
			<?php if($agents): ?>
			<h2>Result:</h2>
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
						<a href="<?php echo base_url(); ?>agents/delete/<?php echo $row->id; ?>"><i class="icon-remove" title="Delete"></i></a>
						
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>

		</div>
	</div>
</div>