<div id="main">
	<?php $total = 0; ?>
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
			  <li class="active">View</li>
			</ul>
			<table width="100%">
				<tr>
					<td>
					<h3><?php echo $borrower[0]->lastname.', '.$borrower[0]->firstname; ?></h3>
					</td>
					<?php if($borrower[0]->status == 1): ?>
					<td class="text-success">
					<h3>On Loan</h3>
					</td>
					<?php endif; ?>
					<?php if($borrower[0]->status == 0): ?>
					<td class="text-info">
					<h3>No Loan</h3>
					</td>
					<?php endif; ?>
					<?php if($borrower[0]->status == 2): ?>
					<td class="text-warning">
					<h3>Delinquent</h3>
					</td>
					<?php endif; ?>
				</tr>
				<?php if(isset($borrower[0]->alastname)): ?>
				<tr>
					<td>
						<h4><small>Agent:</small> <?php echo $borrower[0]->alastname; ?>, <?php echo $borrower[0]->afirstname; ?>, <?php echo $borrower[0]->amiddlename; ?></h4>
					</td>
					<td>
					</td>
				</tr>
				<?php endif; ?>
			</table>
			<?php if($activeloan): ?>
			<?php if($payments): ?>
			<h3>Current Loan</h3>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="span3">
							Due
						</th>
						<th>
							Amount
						</th>
						<th class="span1">
							Status
						</th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach($payments as $payment): ?>
					<tr>
						<td>
							<?php if(date('Y-m-d', strtotime($payment->date)) == date('Y-m-d')) echo '<strong>'; ?><?php echo date("M d Y",strtotime($payment->date)); ?><?php if(date('Y-m-d', strtotime($payment->date)) == date('Y-m-d')) echo '</strong>'; ?>
						</td>
						<td>
							<strong>P</strong> <?php echo number_format($payment->amount); ?>
						</td>
						<td>
							<?php echo($payment->status == 1) ? '<i class="icon-ok"></i>' : ''; ?>
							<?php echo($payment->status == 3) ? '<i class="icon-remove"></i>' : ''; ?>
						</td>
					</tr>

					<?php
					if($payment->status == 0) {
						$total = $total + $payment->amount;	
					}
					?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td>
							<strong>Total:</strong>
						</td>
						<td>
							<strong>P</strong> <?php echo number_format($total); ?>
						</td>
						<td>
						</td>
					</tr>
				</tfoot>
			</table>
			<?php endif; ?>
			<?php endif; ?>

			<?php if($loans): ?>
			<h4>All loans:</h4>
			<table class="table table-bordered table-hover">
				<tr>
					<th>
						ID
					</th>
					<th>
						Ammount
					</th>
					<th>
						Date borrowed
					</th>
					<th>
						Due date
					</th>
					<th>
						Status
					</th>
				</tr>
				<?php foreach($loans as $row): ?>
				<tr class="<?php if($row->status == 1) { echo 'success';} else if($row->status == 2) { echo 'info';} else if($row->status == 3) { echo 'error'; }?>">
					<td>
						<b><?php echo $row->id; ?></b>
					</td>
					<td>
						<b>P</b> <?php echo number_format($row->amount); ?>
					</td>
					<td>
						<?php echo $row->date; ?>
					</td>
					<td>
						<?php echo $row->duedate; ?>
					</td>
					<td>
						<b>
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
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php else: ?>
			<h4>--No Loans at the moment--</h4>
			<?php endif; ?>
		</div>
	</div>
</div>