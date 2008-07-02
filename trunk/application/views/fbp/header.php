<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$this->system->site_name?> | <?php if (!empty($title)):?><?php echo $title?><?php endif;?></title>
	<meta name="keywords" content="<?php if (!empty($meta_keywords)):?><?php echo $meta_keywords?> - <?php endif; ?><?php echo $this->system->meta_keywords;?>" />
	<meta name="description" content="<?php if (!empty($meta_description)):?><?php echo $meta_description?> - <?php endif; ?><?php echo $this->system->meta_description;?>" />
	<meta name="robots" content="index,follow" />
	<link rel="shortcut icon" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/stylesheet.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/superfish.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/iefix.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/jquery.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/sitelib.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/external.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/superfish.js" type="text/javascript"></script>
	<script type="text/javascript">
	$(document).ready(function(){
	$("ul.nav").superfish({
		animation : { opacity:"show", height:"show" }
	});
	});
	</script>
</head>

<body>

<div id="wrapper" align="center">
		<div id="header">
			<div id="headerimage">
			<a href="<?=base_url()?>"></a>
			</div>
			<div id="logo">
			<a href="<?=base_url()?>"></a>
			</div>
		</div>
		<div id="topmenu">
		<!-- left -->
		<?=$this->navigation->print_menu() ?>
		</div>

			<div id="content">
