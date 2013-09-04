<!DOCTYPE HTML>
<html>
<head>
	<title>Lending System</title>
	<script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js'); ?>"></script>
	<script src="<?php echo base_url(); ?>js/bootstrap.js"></script>
	<script src="<?php echo base_url(); ?>js/script.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/style.css" />
	
</head>
<body>
	
<div class="container" id="container">
	<?php if(($this->session->userdata('usertype'))): ?>
	<div class="top">
		<ul>
			<a href="<?php echo base_url('settings/profile'); ?>"><li>Profile</li></a>
			<a href="<?php echo base_url('home/logout'); ?>"><li>Logout</li></a>
		</ul>
	</div>
	<?php endif; ?>


	
