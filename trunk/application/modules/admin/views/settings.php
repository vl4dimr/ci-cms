<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><span><?php echo __("General settings")?></span></a></li>
		<li><a href="#two"><span><?php echo __("Theme settings")?></span></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="settings">Settings</h1>
<form class="settings" action="<?=site_url('admin/settings')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save Settings" class="input-submit" /></li>
			<li><a href="<?=site_url('admin')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<p>Use this page to change the global settings for your site.</p>
		
		<div id="one">
		
			<label for="site_name">Site Name:</label>
			<input type="text" name="site_name" value="<?=$this->system->site_name?>" id="site_name" class="input-text" /><br />
		
			<label for="meta_keywords">META keywords:</label>
			<input type="text" name="meta_keywords" value="<?=$this->system->meta_keywords?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="description">META description:</label>
			<input type="text" name="meta_description" value="<?=$this->system->meta_description?>" id="meta_description" class="input-text" /><br />
			
			<label for="cache">Output Cache:</label>
			<div id="cache">
				<input <?php if ($this->system->cache == 1):?>checked="checked"<?php endif;?> type="radio" name="cache" value="1" /> On<br />
				<input <?php if ($this->system->cache == 0):?>checked="checked"<?php endif;?> type="radio" name="cache" value="0" /> Off<br />
			</div>
			
			<label for="cache_time">Cache Time:</label>
			<input type="text" name="cache_time" value="<?=$this->system->cache_time?>" id="cache_time" class="input-text" /><span>seconds</span><br />
		
		</div>
		<div id="two">
			<label for="theme">Theme:</label>
				<select name="theme" class="input-select" id="theme">
				<?php foreach ($themes as $theme):?>
					<option <?php if ($theme == $this->layout->theme):?>selected='selected' <?php endif;?>value="<?=$theme?>"><?=ucwords(str_replace('_', ' ', $theme))?></option>
				<?php endforeach;?>
				</select><br />
		</div>
	</form>

</div>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
<!-- [Content] end -->