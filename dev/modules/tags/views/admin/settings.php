<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Quick menu", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php echo __("General settings", $this->template['module'])?></span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings"><?php _e("Settings", $this->template['module']) ?></h1>
<form class="settings" action="<?=site_url('admin/palbum/save')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save Settings", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p><?=__("Change the settings for the module", $this->template['module']);?></p>
		

		<div id="one">

			<label for="settings[userid]"><?php _e("Picasa userid", $this->template['module'])?></label>
			<input type="text" name="settings[userid]" value="<?=$settings['userid']?>" id="apikey" class="input-text" /><br />
			<?php echo __("The Google userid where you want to use for picasa. Maybe your google account", $this->template['module']); ?><br />

			<label for="settings[ttl]"><?php _e("TCache duration", $this->template['module'])?></label>
			<select name="settings[ttl]" id="ttl" class="input-select">
			<option value="<?php echo $settings['ttl'] ?>"><?php echo $settings['ttl'] ?></option>
			<option>0</option>
			<option>3600</option>
			<option>86400</option>
			</select>
			<br />
			<?php echo __("In second. The time after which the images will be refreshed. If you put '0', the cache will not be deleted unless you hit de Refresh link", $this->template['module']); ?><br />
			
			
			<label for="settings[maxwidth]"><?php _e("Thumbnail width", $this->template['module'])?></label>
			<select name="settings[thumbwidth]" id="thumbwidth" class="input-select">
			<option value="<?php echo $settings['thumbwidth'] ?>"><?php echo $settings['thumbwidth'] ?></option>
			<option>32</option>
			<option>48</option>
			<option>64</option>
			<option>72</option>			
			<option>144</option>			
			<option>160</option>			
			</select>
			<br />
			
		
			<label for="settings[maxwidth]"><?php _e("Photo display width", $this->template['module'])?></label>
			<select name="settings[maxwidth]" id="maxwidth" class="input-select">
			<option value="<?php echo $settings['maxwidth'] ?>"><?php echo $settings['maxwidth'] ?></option>
			<option>400</option>
			<option>512</option>
			<option>640</option>
			<option>720</option>			
			<option>800</option>			
			</select>
			<br />

		</div>
	</form>

</div>
<!-- [Content] end -->
