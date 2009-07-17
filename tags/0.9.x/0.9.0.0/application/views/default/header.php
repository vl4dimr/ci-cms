<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if (!empty($title)):?><?php echo $title?> - <?php endif;?><?=$this->system->site_name?></title>
	<meta name="keywords" content="<?php if (!empty($meta_keywords)):?><?php echo $meta_keywords?> - <?php endif; ?><?php echo $this->system->meta_keywords;?>" />
	<meta name="description" content="<?php if (!empty($meta_description)):?><?php echo $meta_description?> - <?php endif; ?><?php echo $this->system->meta_description;?>" />
	<meta name="robots" content="index,follow" />
	<link rel="shortcut icon" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/images/favicon.ico" type="image/x-icon" />
	<link rel="alternate" type="application/rss+xml" title="Overall feed" href="<?php echo site_url('feed' )?>" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/stylesheet.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/lightbox.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/style/iefix.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/jquery-1.3.2.min.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/javascript/sitelib.js" type="text/javascript"></script>
<?php $this->plugin->do_action('header');?>
<script type="text/javascript">
$(document).ready(function(){
	$("a.delete").click(function(){
		if (confirm("<?php echo addslashes(__("Confirm delete?", "default"))?>"))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
	/*handleDeleteImage();*/
});
</script>

</head>

<body class='body_bg'>

<div id="wrapper" align="center">
	<div id="header">
		<div id="logo">
		<a href="<?=base_url()?>"><?=$this->system->site_name?></a>
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
<div id="pathway">
<?php echo __("You are here:", "default") ?> 		
<?php echo anchor('', __("home", "default")) ?>
<?php foreach($breadcrumb as $b): ?>
<small>&gt;</small> <?php echo anchor($b['uri'], $b['title']) ?>
<?php endforeach; ?>
</div>
		
	<div id="pagetop"></div>	
	
	
