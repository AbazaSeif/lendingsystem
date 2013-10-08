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
			  <li><a href="<?php echo base_url();?>agents/view/<?php echo $this->uri->segment(3); ?>">Agents</a> <span class="divider">/</span></li>
			  <li class="active">Claim Commision</li>
			</ul>
		<h3>Commision available: P0.00</h3>


		</div>
	</div>
</div>

