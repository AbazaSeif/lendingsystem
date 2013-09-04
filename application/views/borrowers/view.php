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
			</table>
			<?php if($activeloan): ?>
			<table class="table table-bordered table-hover">
				<tr>
					<th>
						Day
					</th>
					<th>
						Date
					</th>
					<th>
						Per Day Amount
					</th>
					<th>
						Amount Paid
					</th>
					<th>
						Status
					</th>
				</tr>
				<?php $total =  $activeloan->total ; ?>
				<?php for($x=0; $x<30 ; $x++): ?>
				<tr>
					<td>
						<b><?php echo $x + 1 ; ?></b>
					</td>
					<td>
						<?php echo date('Y-m-d', strtotime($activeloan->date. ' + '.$x.' days')); ?>
					</td>
					<td>
						<b>P </b><?php echo round($activeloan->amountdue/30,2,PHP_ROUND_HALF_UP); ?>
					</td>
					<td><b>P </b>
						<?php if($activeloan->amountdue - $activeloan->amountdue/30 * ($x) <= $activeloan->amountdue - $activeloan->total) {
							echo -($activeloan->amountdue - $activeloan->amountdue/30 * ($x));
						}
						else {
							echo $activeloan->amountdue/30;
						}
						 ?>
					</td>
					<td>
						<?php if($activeloan->amountdue - $activeloan->amountdue/30 * ($x+1) <= $activeloan->amountdue - $activeloan->total) {
							echo 'Pending';
						}
						else {
							echo 'Ok';
						}
						 ?>
					</td>
				</tr>
				<?php endfor; ?>
			</table>
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
						<b>P</b> <?php echo $row->amount; ?>
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