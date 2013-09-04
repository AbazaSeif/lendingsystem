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
			  <li class="active">Payment</li>
			</ul>
			<div id="form_input">
				<?php echo form_open('borrowers/payment'); ?>
				<table width="100%">
					<tr>
						<td class="span3">
						<?php if($loanid): ?>
						<?php echo form_label('Loan ID:', 'loandid'); ?>
						<?php echo form_dropdown('loanid',$loanid,set_value('loanid'),'class="input-small"'); ?>
						<?php else: ?>
						<div class="alert alert-info">
						No Active Loans
						</div>
						<?php endif; ?>
						</td>
					<tr>
					</tr>
						<td class="span3">
						<div class="input-prepend input-append">
							<span class="add-on">P</span>
							<?php echo form_input('amount',set_value('amount'),'placeholder="Amount" id="appendedPrependedInput" class="input-small"'); ?>
							<span class="add-on">.00</span>
						</div>
						<?php if(form_error('amount')): ?>
						<div class="alert alert-error">
						<?php echo form_error('amount'); ?>
						</div>
						<?php endif; ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php echo form_submit('submit', 'Confirm','class="btn btn-block btn-primary btn-large"'); ?>
						</td>
					</tr>
				</table>
				<?php echo form_close(); ?>
			</div>

			<div class="tab-pane" id="loans">
				<?php if($loans): ?>
				<table class="table">
					<tr>
						<th>
							Loan
						</th>
						<th>
							Borrower
						</th>
						<th>
							Amount
						</th>
						<th>
							Per Day
						</th>
						<th>
							Started
						</th>
						<th>
							Due
						</th>
						<th>
							Status
						</th>
						<th>
							Progress
						</th>
					</tr>
					<?php foreach($loans as $row): ?>

					<tr class="<?php if($row->status == 1) { echo 'success';} else if($row->status == 2) { echo 'info';} else if($row->status == 3) { echo 'error'; }?>">
						<td>
							<b><?php echo $row->id; ?></b>
						</td>
						<td>
							<b><?php echo $row->borrowerid;?></b>
						</td>
						<td>
							<b>P</b><?php echo $row->amount; ?>
						</td>
						<td>
							<b>P</b><?php echo round($row->amount/30,2,PHP_ROUND_HALF_UP); ?>
						</td>
						<td>
							<?php echo $row->date; ?>
						</td>
						<td>
							<?php echo $row->duedate; ?>
						</td>
						<td><b>
							<?php if($row->status == 1) {
								echo 'Active';
							}
							else if($row->status == 2) {
								echo 'Complete';
							}
							else if($row->status == 3) {
								echo 'Unpaid';
							}
							?>
							</b>
						</td>
						<td id="progress<?php echo $row->id;?>" data-toggle="tooltip" title="<b>P</b><?php echo $row->total; ?> / <b>P</b><?php echo $row->amountdue; ?>" data-placement="right">
						<?php $percent = round(($row->total/$row->amountdue)*100,2) ?>
						<div class="progress progress-striped<?php if($row->status == 1) { echo ' progress-warning'; } else if($row->status == 3) { echo ' progress-danger'; } ?>" style="text-align:center;">
						<div class="bar" style="width: <?php echo $percent; ?>%;"><?php echo $percent; ?>%</div>
						</div>
						</td>

					</tr>
					<?php endforeach; ?>
				</table>
				<?php endif; ?>
				</div>
		</div>
	</div>
</div>

<script type="text/javascript">
<?php foreach($loans as $row): ?>
	$('#progress<?php echo $row->id;?>').hover(function() {
		$(this).tooltip('show');
	});
<?php endforeach; ?>
</script>