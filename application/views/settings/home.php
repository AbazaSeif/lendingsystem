<div id="main">
	<div id="mainpanel">
		<div id="topbar">
			<a title="Home"href="<?php echo base_url();?>" ><img src="<?php echo base_url();?>images/text_home.png" /></a>
		</div>
		<div id="titlebar">
			<?php echo strtoupper($title); ?>
		</div>

		<div id="content">


		<div id="form_input">

			<?php if($info !== FALSE): ?>
			<div class="alert alert-success"><?php echo $info; ?></div>
			<?php endif;?>

			<ul class="nav nav-tabs" id="myTab">
  				<li class="active"><a href="#sms">SMS</a></li>
  				<li><a href="#rates">Rates</a></li>
			</ul>
			<?php echo form_open('settings'); ?>
			<div class="tab-content">
				<div class="tab-pane active" id="sms">
				
				<table width="100%" class="table">
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message1',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to newly registered borrowers.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 1",
							'data-placement' => 'bottom',
							'value' => $settings->message1
						); ?>
						<?php echo form_label('SMS message 1:','message1'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message2',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to newly registered agents.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 2",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message2
						); ?>
						<?php echo form_label('SMS message 2:','message2'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message3',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to non registered numbers.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 3",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message3
						); ?>
						<?php echo form_label('SMS message 3:','message3'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message4',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to borrowers with invalid keywords.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 4",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message4
						); ?>
						<?php echo form_label('SMS message 4:','message4'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message5',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to agents with invalid keywords.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 5",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message5
						); ?>
						<?php echo form_label('SMS message 5:','message5'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message6',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to borrowers upon making an inquiry.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 6",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message6
						); ?>
						<?php echo form_label('SMS message 6:','message6'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message7',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to agents that have successfully registered a borrower.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 7",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message7
						); ?>
						<?php echo form_label('SMS message 7:','message7'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message8',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to agents that have successfully registered a loan.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 8",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message8
						); ?>
						<?php echo form_label('SMS message 8:','message8'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message9',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to agents that have successfully updated their loan.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 9",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message9
						); ?>
						<?php echo form_label('SMS message 9:','message9'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message10',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to borrowers upon making a successfull loan registration.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 10",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message10
						); ?>
						<?php echo form_label('SMS message 10:','message10'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message11',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to agents that doesn't have borrowers under their name.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 11",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message11
						); ?>
						<?php echo form_label('SMS message 11:','message11'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						<?php $ta = array(
							'name' => 'message12',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "This message will be sent to clients upon loan update.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 12",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message12
						); ?>
						<?php echo form_label('SMS message 12:','message12'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php $ta = array(
							'name' => 'message13',
							'class' => 'settingstoggle span4',
							'data-toggle' => 'popover',
							'data-content' => "Agents HELP message response.",
							'data-original-title' => "Note:",
							'rows' => 5,
							'placeholder' => "Message 13",
							'data-placement' => 'bottom',
							'value' => $settings[0]->message13
						); ?>
						<?php echo form_label('SMS message 13:','message13'); ?>
						<?php echo form_textarea($ta); ?>
						</td>
						<td>
						</td>
					</tr>
				</table>
				
			</div>
			<div class="tab-pane" id="rates">
				<?php echo form_label('Loan Interest:','interest'); ?>
				<div class="input-append">
				  <?php echo form_input('commision',$settings[0]->interest,'placeholder="Interest" id="appendedInput" class="input-mini"'); ?>
				  <span class="add-on">%</span>
				</div>

				<?php echo form_label('Agents Commision:','commision'); ?>
				<div class="input-append">
				  <?php echo form_input('commision',$settings[0]->commision,'placeholder="Commision" id="appendedInput" class="input-mini"'); ?>
				  <span class="add-on">%</span>
				</div>

				<?php echo form_label('Penalty:','penalty'); ?>
				<div class="input-append">
				  <?php echo form_input('penalty',$settings[0]->penalty,'placeholder="Penalty" id="appendedInput" class="input-mini"'); ?>
				  <span class="add-on">.00</span>
				</div>
			</div>
		</div>
		<?php echo form_submit('save','Save','class="btn btn-primary"'); ?>
		<?php echo form_close(); ?>

		</div>

		</div>
	</div>
</div>