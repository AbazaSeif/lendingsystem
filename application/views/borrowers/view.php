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
				<?php if(isset($borrower[0]->alastname)): ?>
				<tr>
					<td>
						<h4><small>Agent:</small> <?php echo $borrower[0]->alastname; ?>, <?php echo $borrower[0]->afirstname; ?>, <?php echo $borrower[0]->amiddlename; ?></h4>
					</td>
					<td>
						<?php if($activeloan): ?>
						<h4><small>Wallet:</small> P<?php echo $activeloan->bag; ?></h4>
						<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?>
			</table>
			<?php if($payments): ?>
			<h3>Payments History</h3>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th class="span3">
							Date
						</th>
						<th>
							Payment
						</th>
					</tr>
				</thead>
				<tbody>
					<?php $total = 0; ?>
					<?php foreach($payments as $payment): ?>
					<tr>
						<td>
							<?php echo date("M d Y",strtotime($payment->date)); ?>
						</td>
						<td>
							<strong>P</strong> <?php echo $payment->amount; ?>
						</td>
					</tr>
					<?php $total = $total + $payment->amount; ?>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<td>
							Total:
						</td>
						<td>
							<strong>P</strong> <?php echo $total; ?>
						</td>
					</tr>
				</tfoot>
			</table>
			<?php endif; ?>


			<?php if($activeloan): ?>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="span1">
						Day
					</th>
					<th>
						Date
					</th>
					<th>
						Amount Due
					</th>
					<th class="span1">
						Status
					</th>
				</tr>
				<?php $pseudototal = 0 ; ?>
				<?php for($x=0; $x<30 ; $x++): ?>
				<?php $pseudototal = $pseudototal + ($activeloan->amountdue/30); ?>
				<tr>
					<td>
						<b><?php echo $x + 1 ; ?></b>
					</td>
					<td>
						<?php echo date('M d Y', strtotime($activeloan->date. ' + '.($x + 1).' days')); ?>
					</td>
					<td>
						<strong>P</strong> <?php echo $activeloan->amountdue / 30 ; ?>
					</td>
					<td>
						<?php if($total): ?>
							<?php if($pseudototal < $total): ?>
							<i class="icon-ok"></i>
							<?php endif; ?>
						<?php endif ; ?>
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