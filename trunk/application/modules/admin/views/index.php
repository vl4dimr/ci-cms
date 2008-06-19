<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="quicklaunch">Quick launch</h1>
	
	<ul class="quickmenu">
		<li><a href="<?=site_url('admin/page/create')?>">Add new page</a></li>
		<li><a href="<?=site_url('admin/settings')?>">System configuration</a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="dashboard">Dashboard</h1>

<hr />

<?php if ($notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<div class="row">

	<div class="left">
		<h2>System informations</h2>
		<dl>
			<dt>Currently running:</dt>
			<dd class="bold">CI-CMS <?= $this->system->version ?></dd>
<?php if ( $this->system->version < $this->administration->latest_version ): ?>
			<dt>Latest version:</dt>
			<dd class="red">CI CMS <?=$this->administration->latest_version?></dd>
			<dt>Get new version:</dt>
			<dd class="bold"><a href="http://ci-cms.blogspot.com/" rel="external">Upgrade Now!</a></dd>
<?php endif;?>
			<dt>Site name:</dt>
			<dd><?=$this->system->site_name?></dd>
			<dt>Site adress:</dt>
			<dd><a href="<?=base_url()?>"><?=base_url()?></a></dd>
			<dt>Staff:</dt>
			<dd><?=$this->administration->no_active_users()?></dd>
			<dt>Database size:</dt>
			<dd><?=formatfilesize($this->administration->db_size())?></dd>
			<dt>Cache size:</dt>
			<dd><?=recursive_directory_size(APPPATH.'cache/', TRUE);?></dd>
		</dl>
	</div>
	
	<div class="right">
		<h2>Latest News</h2>
		<ul>
<?php //$i = 0; if (BLAZE_VERSION < $this->administration->latest_version): $k = 8; else: $k = 6; endif;?>
<?php foreach ($blaze_news as $news):?>
<?php //$i ++; if ($i > $k) continue;?>
			<li><a href="<?=$news->get_link()?>" rel="external"><?=$news->get_title()?></a></li>
<?php endforeach;?>
		</ul>
	</div>
	
</div>

<br class="clearfloat" />

</div>
<!-- [Content] end -->
