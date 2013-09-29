<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>
		<div id="content">
			<div class="btn-group">
			<button type="submit" id="search_toggle" class="btn"><i class="icon-search">&nbsp;</i>Search</button>
			<button type="submit" id="toggle" class="btn"><i class="icon-plus">&nbsp;</i>Borrower</button>
			<button type="submit" id="loan_toggle" class="btn"><i class="icon-plus">&nbsp;</i>Loan</button>
			<button type="submit" id="payment_toggle" class="btn"><i class="icon-plus">&nbsp;</i> Payment</button>
			</div>


			<div id="form_search" class="form_search">
			<?php echo form_open('borrowers/search'); ?>			
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
  			<?php echo form_submit('submit', 'Search', 'class="btn-block btn-primary btn btn-large"'); ?>
  			<?php echo form_close(); ?>
  			</div>

			<div id="form_input" class="form_input">
			<?php echo form_open('borrowers/add'); ?>
			<?php $data = array(
				'male' => 'Male',
				'female' => 'Female'
			); ?>
			<table width="100%">
				<tr>
					<td>
					<?php echo form_input('lastname', set_value('lastname'),'placeholder="Last Name"'); ?>
					<?php echo form_input('firstname', set_value('firstname'),'placeholder="First Name"'); ?>
					<?php echo form_input('middlename', set_value('middlename'),'placeholder="Middle Name"'); ?>
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
					<?php echo form_textarea('address', set_value('address'),'placeholder="Address" style="width:450px;"'); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php echo form_dropdown('gender', $data,''); ?>
					</td>
				</tr>
				<tr>
					<td>
					<?php if($aid): ?>
					<?php echo form_dropdown('aid', $aid, ''); ?>
					<?php elseif(!$aid): ?>
					<div class="alert alert-info">
						No agent registered.
					</div>
					<?php endif; ?>
					</td>
				</tr>
			</table>
			<?php echo form_submit('save', 'Add borrower','class="btn btn-primary btn-block btn-large"'); ?>
			<?php echo form_close(); ?>
			</div>



			<div id="form_input" class="form_loan">
				<?php echo form_open('borrowers/loan'); ?>
				<table width="100%">
					<tr>
						<td>
							<div class="input-prepend input-append">
							  <span class="add-on">P</span>
							  <?php echo form_input('amount',set_value('amount'),'placeholder="Amount" id="appendedPrependedInput" class="input-small"'); ?>
							  <span class="add-on">.00</span>
							</div>
							<div class="input-append date" date-date-format="dd-mm-yyy">
								<?php echo form_input('startdate',set_value('startdate'),'placeholder="Start Date" class="input-small datepickers"'); ?>
								<span class="add-on"><i class="icon-th"></i></span>
							</div>
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
							<?php echo form_submit('submit', 'Add loan', 'class="btn-block btn-primary btn btn-large"'); ?>
						</td>
					</tr>
				</table>
				<?php echo form_close(); ?>
			</div>

			<div id="form_input" class="form_payment">
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
					</tr>
					<tr>
						<td class="span3">
							<?php echo form_label('Amount:', 'amount'); ?>
						<div class="input-prepend input-append">
							<span class="add-on">P</span>
							<?php echo form_input('amount',set_value('amount'),'placeholder="Amount" id="appendedPrependedInput" class="input-small"'); ?>
							<span class="add-on">.00</span>
						</div>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						<?php echo form_submit('submit', 'Confirm','class="btn btn-block btn-primary btn-large"'); ?>
						</td>
					</tr>
				</table>
				<?php echo form_close(); ?>
			</div>

			<br/><br/>
			<ul class="nav nav-tabs" id="client_tab">
  				<li class="active"><a href="#borrowers">Borrowers</a></li>
  				<li><a href="#loans">Loans</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="borrowers">
				<?php if($borrowers): ?>
				<table class="table table-hover">
					<tr>
						<th>
							ID
						</th>
						<th>
							Agent ID
						</th>
						<th>
							Name
						</th>
						<th>
							Address
						</th>
						<th>
							Loan
						</th>
						<th>
							Actions
						</th>
					</tr>
					<?php foreach($borrowers as $row): ?>
					<tr <?php if($row->status == 1) { echo 'class="success"'; } ?>>
						<td>
							<b><?php echo $row->id ;?></b>
						</td>
						<td>
							<?php echo $row->alastname; ?>, <?php echo $row->afirstname; ?> <?php echo $row->amiddlename; ?>
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
							<?php if($row->status == "1"): ?>
							YES
							<?php else: ?>
							NO
							<?php endif;?>

						</td>
						<td>
							<a href="<?php echo base_url(); ?>borrowers/view/<?php echo $row->id; ?>"><i class="icon-th-large" title="View"></i></a>
							<a href="<?php echo base_url(); ?>borrowers/delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure want to delete?');"><i class="icon-remove" title="Delete"></i></a>
							
						</td>
					</tr>
					<?php endforeach; ?>
				</table>
				<?php endif; ?>
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
							<?php echo $row->blastname; ?>, <?php echo $row->bfirstname; ?>
						</td>
						<td>
							<b>P</b><?php echo $row->amount; ?>
						</td>
						<td>
							<b>P</b><?php echo round($row->amountdue/30,2,PHP_ROUND_HALF_UP); ?>
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
							else if($row->id == 3) {
								echo 'Unpaid';
							}
							?>
							</b>
						</td>
						<td id="progress<?php echo $row->id;?>" data-toggle="tooltip" title="<b>P</b><?php echo $total[$row->id]; ?> / <b>P</b><?php echo $row->amountdue; ?>" data-placement="right">
							<?php $percent = round(($total[$row->id]/$row->amountdue)*100,2) ?>
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
</div>

<script type="text/javascript">
$(document).ready(function() {
	$(".datepickers").datepicker();
});
<?php foreach($loans as $row): ?>
	$('#progress<?php echo $row->id;?>').hover(function() {
		$(this).tooltip('show');
	});
<?php endforeach; ?>
</script>