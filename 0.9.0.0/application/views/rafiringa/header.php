<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$this->system->site_name?> | <?php if (!empty($title)):?><?php echo $title?><?php endif;?></title>
	<meta name="keywords" content="<?php if (!empty($meta_keywords)):?><?php echo $meta_keywords?> - <?php endif; ?><?php echo $this->system->meta_keywords;?>" />
	<meta name="description" content="<?php if (!empty($meta_description)):?><?php echo $meta_description?> - <?php endif; ?><?php echo $this->system->meta_description;?>" />
	<meta name="robots" content="index,follow" />
	<link rel="shortcut icon" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/images/favicon.ico" type="image/x-icon" />
	<link rel="alternate" type="text/xml" title="RSS 2.0" href="http://rafiringa.net/feed" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/stylesheet.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/jquery.lightbox-0.5.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/iefix.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/jquery.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/sitelib.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/external.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/jquery.lightbox-0.5.pack.js" type="text/javascript"></script>

<?php $this->plugin->do_action('header');?>
<script type="text/javascript" src="http://partner.googleadservices.com/gampad/google_service.js">
</script>
<script type="text/javascript">
  GS_googleAddAdSenseService("ca-pub-9819963022289377");
  GS_googleEnableAllServices();
</script>
<script type="text/javascript">
  GA_googleAddSlot("ca-pub-9819963022289377", "rvg_interne_120x600");
</script>
<script type="text/javascript">
  GA_googleFetchAds();
</script>	

</head>

<body class='body_bg'>

<div id="wrapper" align="center">
	<div id="header">
		<div id="logo">
		<a href="<?=base_url()?>"></a>
		</div>
	</div>
	<div id="topmenu">
	<!-- left -->
	<?php if ($navs = $this->navigation->get(array('title' => 'topmenu', 'lang' => $this->user->lang))) :?>
	<ul>
	<?php $i=1; foreach ($navs as $nav) : ?>
	<?php 
	if($i == 1) : $class = "first" ;
	elseif($i == count($navs)): $class = "last";
	else :$class = "middle";
	endif;
	?>
			<li class="<?=$class?>" ><a href="<?php echo $nav['uri']?>"><?=$nav['title']?></a></li>
	<?php $i++; endforeach; ?>
	<?php endif; ?>
	</ul>


	
	</div>
	<div id="langbar">
		<?php if ($languages = $this->locale->get_active()) :?>
		<ul>
		<?php foreach ($languages as $language): ?>
			<li><a href='<?php echo site_url( $language['code']) ?>' <?php echo ($this->session->userdata('lang') == $language['code']) ? "class='active'" : ""?> ><img src="<?=base_url()?>application/views/admin/images/flags/<?php echo $language['code']?>.gif" alt="<?php echo $language['name'] ?>" border="0" width="20" height="14"></a></li>
		<?php endforeach;?>
		</ul>
		<?php else : ?>
		<span style="color: white; font-weight: bold"><?php echo __("Please fix, no active language")?></span>
		<?php endif; ?>
	</div>
	
