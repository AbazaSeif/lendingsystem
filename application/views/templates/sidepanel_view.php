<div id="sidepanel">
	<div id="navigation">
	<ul>
		<?php if($this->session->userdata('borrowers')): ?><li><a href="<?php echo base_url(); ?>borrowers"><img src="<?php echo base_url();?>images/text_clients.png" /></a></li><?php endif; ?>
		<?php if($this->session->userdata('agents')): ?><li><a href="<?php echo base_url(); ?>agents"><img src="<?php echo base_url();?>images/text_agents.png" /></a></li><?php endif; ?>
		<li><a href="<?php echo base_url(); ?>inbox"><img src="<?php echo base_url();?>images/text_sms.png" /></a></li>
		<?php if($this->session->userdata('settings')): ?><li><a href="<?php echo base_url(); ?>settings"><img src="<?php echo base_url();?>images/text_settings.png" /></a></li><?php endif; ?>
		<?php if($this->session->userdata('users')): ?><li><a href="<?php echo base_url(); ?>users"><img src="<?php echo base_url();?>images/text_users.png" /></a></li><?php endif; ?>
	</ul>
	</div>
</div>