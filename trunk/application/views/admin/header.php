<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?=$this->system->site_name?> | <?=__("Administration")?></title>
	<link rel="shortcut icon" href="<?=base_url()?>application/views/admin/images/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/admin/style/admin.css" type="text/css" media="screen" charset="utf-8" />
	<link rel="stylesheet" href="<?=base_url()?>application/views/admin/style/tabs.css" type="text/css" media="screen" charset="utf-8" />
	<!--[if IE]>
		<link rel="stylesheet" href="<?=base_url()?>application/views/admin/style/ie.css" type="text/css" media="screen" charset="utf-8" />
	<![endif]-->
	<?php foreach($this->javascripts->get() as $javascript): ?>
	<script src="<?=base_url()?>application/views/admin/javascript/<?=$javascript?>" type="text/javascript"></script>
	<?php endforeach; ?>
	<?php if ($this->uri->segment(3) == ('edit' || 'create' || 'add')):?>
	<script src="<?=base_url()?>application/views/admin/javascript/tinymce/tiny_mce.js" type="text/javascript"></script>
	<script language="javascript" type="text/javascript">
		tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,advhr,advimage,advlink,inlinepopups,insertdatetime,xhtmlxtras,media",
		languages : "en",
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyfull,|,bullist,numlist,|,link,unlink,|,fontselect,formatselect,|,undo,redo,image,media",
		theme_advanced_buttons2 : "insertlayer,|,tablecontrols,|,forecolor,backcolor,insertdate,inserttime,pagebreak,|,cleanup,removeformat,code",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		tab_focus : ':prev,:next',
		fix_list_elements : true,
		relative_urls : false,
		convert_urls : false,
		valid_elements : "*[*]",
		external_link_list_url : "/admin/page/tinypagelist",
		external_image_list_url : "/admin/page/tinyimagelist",
		pagebreak_separator : "<!-- page break -->"
	});
	</script>
<?php endif;?>
	<!--[if lt IE 7]>
		<script defer type="text/javascript" src="<?=base_url()?>application/views/admin/javascript/pngfix.js"></script>
	<![endif]-->
<?php $this->plugin->do_action('header');?>	
</head>

<body>

<!-- [Base] start -->
<div id="base">

<!-- [Header] start -->
<div id="header">
	<a class="logo" href="<?=site_url('admin')?>">
		<span><?=$this->system->site_name?> | Administration</span>

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
<?php if (isset($this->system->modules)) : ?>		
<?php foreach ($this->system->modules as $admin_module): ?>
	<?php if ($admin_module['status'] == 1 && $admin_module['with_admin'] == 1 && isset($this->user->level[ $admin_module['name'] ]['level'])) :?>
		<li<?php if ($module == $admin_module['name']):?> class="active"<?php endif;?>><a href="<?=site_url('admin/'.$admin_module['name'])?>"><?=ucfirst($admin_module['name'])?></a></li>
	<?php endif; ?>		
<?php endforeach;?>
<?php endif; ?>
	</ul>

	<div id="langbar">
	<?php if ($languages = $this->locale->get_active()) :?>
	<ul>
	<?php foreach ($languages as $language): ?>
		<li><a href='<?php echo site_url( $language['code'] . $this->uri->uri_string()) ?>' <?php echo ($this->session->userdata('lang') == $language['code']) ? "class='active'" : ""?> ><img src="<?=base_url()?>application/views/admin/images/flags/<?php echo $language['code']?>.gif" alt="<?php echo $language['name'] ?>" width="20" height="14"></a></li>
	<?php endforeach;?>
	</ul>
	<?php else : ?>
	<span style="color: white; font-weight: bold"><?php echo __("Please fix, no active language")?></span>
	<?php endif; ?>
	</div>
	
<?php endif; ?>
</div>
<!-- [Navigation] end -->

<!-- [Main] start -->	
<div id="main"><div id="inner">
