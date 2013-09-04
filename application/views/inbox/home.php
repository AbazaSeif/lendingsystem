<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>
		<div id="content">
		
		<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a href="#inbox">Inquiry Inbox</a></li>
				<li><a href="#pending">Pending</a></li>
		</ul>


	<div class="tab-content">
		<div class="tab-pane active"  id="inbox">
		</div>
		<div class="tab-pane" id="pending">
		<?php if($sms): ?>
		<h2>Pending</h2>
		<table class="table">
			<tr>
				<th>
					Number
				</th>
				<th>
					Message
				</th>
				<th>
					Type
				</th>
			</tr>
			<?php foreach($sms as $row): ?>
			<tr>
				<td>
					<?php echo $row->number; ?>
				</td>
				<td>
					<?php echo $row->message; ?>
				</td>
				<td>
					<?php if($row->type = 1) {
						echo 'Outgoing';
					}
					else if($row->type = 2) {
						echo 'Incoming';
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?>
		</table>

		<?php endif;?>
		</div>
	</div>

		</div>
	</div>
</div>