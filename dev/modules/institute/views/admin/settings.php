<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Quick menu", $module)?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php echo __("General settings", $module)?></span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php echo $title ?></h1>
<form class="settings" action="<?php echo site_url('admin/guestbook/settings/save')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save Settings", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/news')?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
				
		<div id="one">
		
			<label for="settings[notify_admin]"><?php echo __("Nodify admin", $module)?></label>
			<select name="settings[notify_admin]" class="input-select">
			<option value='Y' <?php echo (($settings['notify_admin']=='Y')?"selected":"")?>><?php echo __("Yes", $module)?></option>
			<option value='N' <?php echo (($settings['notify_admin']=='N')?"selected":"")?>><?php echo __("No", $module)?></option>
			</select>
			
			<label for="settings[style]"><?php echo __("Theme", $module)?></label>
			<select name="settings[notify_admin]" class="input-select">
			<option value='none' <?php echo (($settings['style']=='none')?"selected":"")?>><?php echo __("None", $module)?></option>
			<option value='blue' <?php echo (($settings['style']=='blue')?"selected":"")?>><?php echo __("Blue", $module)?></option>
			</select>
		</div>
	</form>

</div>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
<!-- [Content] end -->
