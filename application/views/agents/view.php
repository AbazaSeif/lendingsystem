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
			  <li class="active">View</li>
			</ul>
			<div class="row">
				<div class="span5">
				<?php foreach($agent as $agent): ?>
				<h3><?php echo $agent->lastname; ?>, <?php echo $agent->firstname; ?></h3>
				<?php endforeach; ?>
				</div>
				<div class="span3">
					<h3>Earned: <b>P</b><?php echo $total; ?></h3>
					<br/>
					<a href="#" class="btn"><i class="icon-circle-arrow-down"></i> Claim</a>
				</div>
			</div>

			<?php if(!$borrowers): ?>
			<h3>--No Borrowers Under this Agent--</h3>
			<?php endif; ?>
			<?php if($borrowers): ?>
			<hr />
			<h3>Borrowers</h3>
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
						<a href="<?php echo base_url(); ?>borrowers/delete/<?php echo $row->id; ?>" onclick="return confirm('Are you sure want to delete?');" ><i class="icon-remove" title="Delete"></i></a>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>

			<hr />
			<h3>Loans</h3>

			<table class="table table-hover">
				<tr>
					<th>
						ID
					</th>
					<th>
						Borrower
					</th>
					<th>
						Loan
					</th>
					<th>
						Due
					</th>
					<th>
						Due
					</th>
					<th>
						Commision
					</th>
					<th>
						Status
					</th>
					<th>
						Progress
					</th>
				</tr>

				<?php foreach($borrowers as $borrower): ?>
				<?php if($loans[$borrower->id]): ?>
				<?php foreach($loans[$borrower->id] as $loan): ?>
				<tr class="<?php if($loan->status == 1) { echo 'success';} else if($loan->status == 2) { echo 'info';} else if($loan->status == 3) { echo 'error'; }?>">
					<td>
						<?php echo $loan->id; ?>
					</td>
					<td>
						<?php echo $loan->blastname; ?>, <?php echo $loan->bfirstname; ?>, <?php echo $loan->bmiddlename; ?>
					</td>
					<td>
						<b>P</b><?php echo $loan->amount; ?>
					</td>
					<td>
						<b>P</b><?php echo $loan->amountdue; ?>
					</td>
					<td>
						<?php echo $loan->duedate; ?>
					</td>
					<td>
						<b>P</b><?php echo ($loan->amountdue - $loan->amount)*($commision/100); ?>
					</td>
					<td>
					<b>
					<?php if($loan->status == 1) {
						echo 'Active';
					}
					else if($loan->status == 2) {
						echo 'Complete';
					}
					else if($loan->id == 3) {
						echo 'Unpaid';
					}
					?>
					</b>
					</td>
					<td id="progress<?php echo $loan->id;?>" data-toggle="tooltip" title="<b>P</b><?php echo $percent[$loan->id]; ?> / <b>P</b><?php echo $loan->amountdue; ?>" data-placement="right">
						<?php $percent = round(($percent[$loan->id]/$loan->amountdue)*100,2) ?>
					<div class="progress progress-striped<?php if($loan->status == 1) { echo ' progress-warning'; } else if($loan->status == 3) { echo ' progress-danger'; } ?>" style="text-align:center;">
					<div class="bar" style="width: <?php echo $percent; ?>%;"><?php echo $percent; ?>%</div>
					</div>
					</td>
					<script type="text/javascript">
						$('#progress<?php echo $loan->id;?>').hover(function() {
							$(this).tooltip('show');
						});
					</script>
				<?php endforeach; ?>
				<?php endif; ?>
				<?php endforeach; ?>
			</tr>
			</table>
			<?php endif; ?>



		</div>
	</div>
</div>

