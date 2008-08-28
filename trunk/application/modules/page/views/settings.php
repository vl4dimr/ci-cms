<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one">General settings</a></li>
		<!-- <li><a href="#two">Theme settings</a></li> -->
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?=__("Settings", $this->template['module'])?></h1>

<form class="settings" action="<?=site_url('admin/page/settings')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save Settings" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/page')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p><?=__("Use this page to change the settings for the page module.", $this->template['module'])?></p>
		
		<div id="one">
		
			<label for="site_name">Homepage:</label>
			<input type="text" name="page_home" value="<?=isset($this->system->page_home)?$this->system->page_home:"home"?>" id="page_home" class="input-text" /><br />
		
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
