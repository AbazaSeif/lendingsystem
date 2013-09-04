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
			  <li><a href="<?php echo base_url();?>borrowers">Borrowers</a> <span class="divider">/</span></li>
			  <li class="active">Loan</li>
			</ul>
			<div id="form_input">
				<?php echo form_open('borrowers/loan'); ?>
				<table width="100%">
					<tr>
						<td>
							<div class="input-prepend input-append">
							  <span class="add-on">P</span>
							  <?php echo form_input('amount',set_value('amount'),'placeholder="Amount" id="appendedPrependedInput" class="input-small"'); ?>
							  <span class="add-on">.00</span>
							</div>
							<span class="input-medium uneditable-input"><?php echo date('M d Y'); ?></span>
						</td>
						
					</tr>
					<tr>
						<td>
							<?php if($bid): ?>
							<?php echo form_dropdown('borrower',$bid,set_value('borrower'),'class="input-medium"'); ?>
							<?php elseif(!$bid): ?>
							<div class="alert alert-info">
								No borrowers registered.
							</div>
							<?php endif; ?>
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
							<?php echo form_submit('submit', 'Add loan', 'class="btn-block btn-primary btn btn-large"'); ?>
						</td>
					</tr>
				</table>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>