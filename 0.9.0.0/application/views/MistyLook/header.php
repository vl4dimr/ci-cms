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
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/iefix.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/jquery.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/sitelib.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/external.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/superfish.js" type="text/javascript"></script>
<!-- PLACEZ CETTE BALISE DANS LA SECTION head -->
<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js">
</script>
<script type="text/javascript">
  GS_googleAddAdSenseService("ca-pub-9819963022289377");
  GS_googleEnableAllServices();
</script>
<script type="text/javascript">
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_1");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_2");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_3");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_4");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_5");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_125x125_6");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_250x250");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_right_250x250_bottom");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_skyscaper_120x600");
  GA_googleAddSlot("ca-pub-9819963022289377", "serasera_home_top_728x90");
</script>
<script type="text/javascript">
  GA_googleFetchAds();
</script>
<?php $this->plugin->do_action('header');?>
</head>

<body id="section-index">
	<div id="navigation">
		<?php if ($navs = $this->navigation->get()) :?>
		<ul>
		<?php foreach ($navs as $nav) : ?>
		<li><a href="<?=$nav['uri']?>"><?=$nav['title']?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php endif; ?>
	</div>
	
	<div id="container">
		<div id="headertext">
			<h1><?=$this->system->site_name?></h1>
		</div>
		
		<div id="header-outer" class="weebly_header">
			<div>&nbsp;</div>
		</div>

		<div id="content">
