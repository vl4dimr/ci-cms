<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Navigation", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Navigation item", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="navigation"><?php echo __("Create/Edit an item", $this->template['module'])?></h1>



<form class="settings" action="<?php echo site_url('admin/navigation/save')?>" method="post" accept-charset="utf-8">
		<input type='hidden' name='lang' value='<?php echo $this->user->lang?>' />
		<input type='hidden' name='id' value='<?php echo $nav['id']?>' />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/navigation')?>" class="input-submit last"><?php echo __("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<div id="one">
		
			<label for="title"><?php echo __("Title", $this->template['module'])?>: </label>
			<input name="title" type='text'  value='<?php echo $nav['title']?>'  class="input-text" />
			<br />
			
			<label for="uri"><?php echo __("Target", $this->template['module'])?>: </label>
			<input name="uri" type='text'  value='<?php echo $nav['uri']?>'  class="input-text" />
			<br />
			
			<label for="parent_id"><?php echo __("Parent", $this->template['module'])?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php $follow = null;
			foreach ($this->navigation->get() as $parent):?>
			<?php  
					
					if ($nav['id'] == $parent['id'] || $follow == $parent['parent_id']) 
					{
						$follow = $parent['id']; 
						continue;
					}
					else
					{
					$follow = null;
					}
			?>
			<option value="<?php echo $parent['id']?>" <?php echo ($nav['parent_id'] == $parent['id'])?"selected":""?>><?php echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php echo $parent['title']. $follow ?> </option>
			
			<?php endforeach;?>
			</select>
			<br />
						
	
			<label for="status"><?php echo __("Status", $this->template['module'])?>:</label>
				<select name="status" class="input-select" id="status">
					<option value='1' <?php if ($nav['active'] == '1'):?>selected='selected' <?php endif;?>><?php echo __("Active", $this->template['module'])?></option>
					<option value='0' <?php if ($nav['active'] == '0'):?>selected='selected' <?php endif;?>><?php echo __("Disabled", $this->template['module'])?></option>
				</select><br />

			<label for="g_id"><?php _e("Group access:", $module)?></label><br />
			<select name="g_id" id="g_id" class="select">
			<?php foreach ($this->user->get_group_list() as $group): ?>
				<option value="<?php echo $group['g_id'] ?>" <?php if ($group['g_id'] == $nav['g_id']) echo "selected" ?> ><?php echo __($group['g_name'], $module) ?></option>
			<?php endforeach; ?> 
			</select>
			<br />
					
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
