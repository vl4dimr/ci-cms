<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$this->system->site_name?> | <?php if (!empty($title)):?><?php echo $title?><?php endif;?></title>
	<meta name="keywords" content="<?php if (!empty($meta_keywords)):?><?php echo $meta_keywords?> - <?php endif; ?><?php echo $this->system->meta_keywords;?>" />
	<meta name="description" content="<?php if (!empty($meta_description)):?><?php echo $meta_description?> - <?php endif; ?><?php echo $this->system->meta_description;?>" />
	<meta name="robots" content="index,follow" />
	<link rel="shortcut icon" href="<?=base_url()?>application/views/default/site/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/default/site/style/stylesheet.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/default/site/style/iefix.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<script src="<?=base_url()?>application/views/default/site/javascript/jquery.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/default/site/javascript/sitelib.js" type="text/javascript"></script>
	<script src="<?=base_url()?>application/views/default/site/javascript/external.js" type="text/javascript"></script>
</head>

<body>

<!-- [Base] start -->
<div id="base">

<!-- [Header] start -->
<div id="header">
	<a class="logo" href="<?=base_url()?>application/views/" title="<?=$this->system->site_name?>">
		<img src="<?=base_url()?>application/views/default/site/images/blaze.png" alt="<?=$this->system->site_name?>" />
		<b><?=$this->system->site_name?></b>
	</a>
</div>
<!-- [Header] end -->

<!-- [Navigation] start -->
<div id="navigation">
	<ul id="nav">
<?php foreach ($this->navigation->get() as $nav):?>
		<li<?php if ($this->uri->segment(1) == $nav['uri']):?> class="active"<?php endif;?>><a href="<?=site_url($nav['uri'])?>" title="<?=$nav['title']?>"><?=$nav['title']?></a></li>
<?php endforeach; ?>
	</ul>
	<ul id="navlogin">
		<li><a class="toggle" href="#">Login</a></li>
	</ul>
</div>
<!-- [Navigation] end -->

<!-- [Login] start -->
<div id="login">
	<form class="login" action="<?=site_url('admin/login')?>" method="post" accept-charset="utf-8">
		<fieldset>
			<p>
				<label for="username">Username:</label>
				<input type='text' name='username' id='username' class="input-text" />
				<label for="password">Password:</label>
				<input type="password" name="password" value="" id="password" class="input-text" />
				<input type="submit" name="submit" value="Login &raquo;" id="submit" class="input-submit" />
			</p>
		</fieldset>
	</form>
</div>
<!-- [Login] end -->

<!-- [Breadcrumbs] start -->
<div id="breadcrumbs">
	<span class="left">
		<b>You are here:</b><a href="<?=base_url()?>application/views/"><?=$this->system->site_name?></a><?php foreach ($breadcrumb as $crumb): ?>&nbsp;&raquo;&nbsp;<a href="<?=site_url($crumb['uri'])?>"><?=$crumb['title']?></a><?php endforeach ?>
	</span>
	<span class="right">Today is <?=date('d.m.Y')?></span>
</div>
<!-- [Breadcrumbs] end -->

<br class="clearfloat none" />

<!-- [Main] start -->
<div id="main">

<div id="right-block">
{area.1}
{area.2}
</div>

<div id="content">
