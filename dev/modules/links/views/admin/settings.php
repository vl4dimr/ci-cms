<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Quick menu", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php echo __("General settings", $this->template['module'])?></span></a></li>
		<li><a href="#two"><span> </span></a></li>
		<li><a href="#three"><span> </span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php _e("Settings", $this->template['module']) ?></h1>
<form class="settings" action="<?=site_url('admin/links/settings/save')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save Settings", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/links')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p><?=__("Change the settings for the module", $this->template['module']);?></p>
		

		<div id="one">

			<label for="settings[apikey]"><?php _e("Webthumb API key", $this->template['module'])?></label>
			<input type="text" name="settings[apikey]" value="<?=$settings['apikey']?>" id="apikey" class="input-text" /><br />
			<?php echo sprintf(__("Go to %s to get your Webthumb API key", "links"), "<a href='http://webthumb.bluga.net/'>http://webthumb.bluga.net</a>"); ?><br />
		
		
			<label for="settings[rootname]"><?php _e("Root name", $this->template['module'])?></label>
			<input type="text" name="settings[rootname]" value="<?=$settings['rootname']?>" id="rootname" class="input-text" /><br />

			
			<label for="settings[rootdesc]"><?=__("Welcome text", $this->template['module'])?></label>
			<textarea type="text" name="settings[rootdesc]" id="zoom" class="input-textarea"><?=$settings['rootdesc']?></textarea><br />




		</div>
		<div id="three">


			
		</div>
	</form>

</div>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
<!-- [Content] end -->
