<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch"><?php echo __("Settings", $this->template['module'])?></h1>
	
	<ul class="quickmenu">
		<li><a href="<?php echo site_url('admin/settings')?>"><?php echo __("General settings", $this->template['module'])?></a></li>
		<li><a href="<?php echo site_url('admin/module')?>"><?php echo __("Modules settings", $this->template['module'])?></a></li>		
		<li><a href="<?php echo site_url('admin/admins')?>"><?php echo __("Administrators", $this->template['module'])?></a></li>		
		<li><a href="<?php echo site_url('admin/groups')?>"><?php echo __("Group management", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard"><?php echo __("Dashboard", $this->template['module'])?></h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?php echo $notice;?></p>
<?php endif;?>

<div class="row">

	<div class="left">
		<h2><?php echo __("System informations", $this->template['module'])?></h2>
		<dl>
			<dt><?php echo __("Currently running:", $this->template['module'])?></dt>
			<dd class="bold">CI-CMS <?php echo  $this->system->version ?></dd>
<?php if ( $this->system->version < $this->latest_version ): ?>
			<dt><?php echo __("Latest version:", $this->template['module'])?></dt>
			<dd class="red">CI CMS <?php echo $this->latest_version?></dd>
			<dt><?php echo __("Get new version:", $this->template['module'])?></dt>
			<dd class="bold"><a href="http://code.google.com/p/ci-cms/downloads/list" rel="external"><?php echo __("Upgrade Now!", $this->template['module'])?></a></dd>
<?php endif;?>
			<dt><?php echo __("Site name:", $this->template['module'])?></dt>
			<dd><?php echo $this->system->site_name?></dd>
			<dt><?php echo __("Site adress:", $this->template['module'])?></dt>
			<dd><a href="<?php echo base_url()?>"><?php echo base_url()?></a></dd>
			<dt><?php echo __("Staff:", $this->template['module'])?></dt>
			<dd><?php echo $this->administration->no_active_users()?></dd>
			<dt><?php echo __("Database size:", $this->template['module'])?></dt>
			<dd><?php echo formatfilesize($this->administration->db_size())?></dd>
			<dt>C<?php echo __("ache size:", $this->template['module'])?></dt>
			<dd><?php echo recursive_directory_size($this->config->item('cache_path'), TRUE);?></dd>
		</dl>
	</div>
	
	<div class="right">
		<h2><?php echo __("Latest News", $this->template['module'])?></h2>
		<ul>
<?php //$i = 0; if (BLAZE_VERSION < $this->administration->latest_version): $k = 8; else: $k = 6; endif;?>
<?php foreach ($blaze_news as $news):?>
<?php //$i ++; if ($i > $k) continue;?>
			<li><a href="<?php echo $news->get_link()?>" rel="external"><?php echo $news->get_title()?></a></li>
<?php endforeach;?>
		</ul>
	</div>
	
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
