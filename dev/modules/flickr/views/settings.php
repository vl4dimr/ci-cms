<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"> </h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Flickr settings", "flickr") ?></a></li>
		<!-- <li><a href="#two">Theme settings</a></li> -->
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?=__("Settings", $this->template['module'])?></h1>

<form class="settings" action="<?=site_url('admin/flickr')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save Settings", "flickr") ?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin')?>" class="input-submit last"><?php echo __("Cancel", "flickr") ?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p><?=__("Use this page to change the settings for the flickr module.", $this->template['module'])?></p>
		
		<div id="one">
		
			<label for="flickr_api_key"><?php _e("Flickr API Key:", "flickr") ?></label>
			<input type="text" name="flickr_api_key" value="<?=isset($this->flickr->flickr_api_key)?$this->flickr->flickr_api_key:""?>" id="flickr_api_key" class="input-text" /><br /><a href="http://www.flickr.com/services/api/keys/"><?php echo __("Get it here", "flickr") ?></a><br />
		
			<label for="flickr_api_secret"><?php _e("Flickr API Secret:", "flickr") ?></label>
			<input type="text" name="flickr_api_secret" value="<?=isset($this->flickr->flickr_api_secret)?$this->flickr->flickr_api_secret:""?>" id="flickr_api_secret" class="input-text" /><br /> <a href="http://www.flickr.com/services/api/keys/"><?php echo __("Get it here", "flickr") ?></a><br />

			<label for="flickr_user_name"><?php _e("Flickr Screen Name:", "flickr") ?></label>
			<input type="text" name="flickr_user_name" value="<?=isset($this->flickr->flickr_user_name)?$this->flickr->flickr_user_name:""?>" id="flickr_user_name" class="input-text" /><br /><a href="http://www.flickr.com/account/prefs/screenname/"><?php echo __("Get it here", "flickr") ?></a><br />
			
			
</div>
	</form>
</div>
<!-- [Content] end -->
