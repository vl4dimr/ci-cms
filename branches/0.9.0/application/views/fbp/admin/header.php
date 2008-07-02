<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$this->system->site_name?> | <?=__("Administration")?></title>
	<link rel="shortcut icon" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/style/admin.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/style/tabs.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/style/ie.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<?php foreach($this->javascripts->get() as $javascript): ?>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/javascript/<?=$javascript?>" type="text/javascript"></script>
	<?php endforeach; ?>
	<?php if ($this->uri->segment(3) == ('edit' || 'create' || 'add')):?>
	<script src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/javascript/tinymce/tiny_mce.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,inlinepopups,insertdatetime,xhtmlxtras",
		languages : "en",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,link,unlink,|,formatselect,|,undo,redo,insertdate,inserttime,image,cleanup,code",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		tab_focus : ':prev,:next',
		entity_encoding : "raw",
		fix_list_elements : true,
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
	});
	</script>
<?php endif;?>
	<!--[if lt IE 7]>
		<script defer type="text/javascript" src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/javascript/pngfix.js"></script>
	<![endif]-->
</head>

<body>

<!-- [Base] start -->
<div id="base">

<!-- [Header] start -->
<div id="header">
	<a class="logo" href="<?=site_url('admin')?>">
		<img src="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/admin/images/blaze-logo.png" alt="<?=$this->system->site_name?> | Administration" />

	</a>
	<ul class="topnav">
		<li><a href="<?=base_url()?>">View live site</a></li>
		<li>|</li>
<?php if ($this->user->logged_in): ?>
		<li>Logged in as <a href="#"><?=ucfirst($this->session->userdata('username'))?></a></li>
		<li>|</li>
		<li>Today is <?=date('d').'.'.date('m').'.'.date('Y')?></li>
		<li>|</li>
		<li class="logout"><a href="<?=site_url('admin/logout')?>">Log out</a></li>
<?php else: ?>	
		<li>Today is <?=date('d.m.Y')?></li>
		<li>|</li>
		<li class="login"><a href="<?=site_url('admin/login')?>">Log in</a></li>
<?php endif; ?>
	</ul>
</div>
<!-- [Header] end -->

<!-- [Navigation] start -->
<div id="navigation">
<?php if ($this->user->logged_in): ?>
	<ul id="adminnav">
		<li<?php if ($module == 'admin' && $view == 'index'):?> class="active"<?php endif;?>><a href="<?=site_url('admin')?>">Dashboard</a></li>
		<li<?php if ($view == 'navigation/index'):?> class="active"<?php endif;?>><a href="<?=site_url('admin/navigation')?>">Navigation</a></li>
<?php if (isset($this->administration->modules)) : ?>		
<?php foreach ($this->administration->modules as $admin_module): ?>
	<?php if ($admin_module['status'] == 1) :?>
		<li<?php if ($module == $admin_module['name']):?> class="active"<?php endif;?>><a href="<?=site_url('admin/'.$admin_module['name'])?>"><?=ucfirst($admin_module['name'])?></a></li>
	<?php endif; ?>		
<?php endforeach;?>
<?php endif; ?>
	</ul>	
<?php endif; ?>
</div>
<!-- [Navigation] end -->

<!-- [Main] start -->	
<div id="main"><div id="inner">
