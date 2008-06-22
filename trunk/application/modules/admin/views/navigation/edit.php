<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Navigation")?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Navigation item")?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<h1 id="navigation"><?=__("Edit an item")?></h1>



<form class="settings" action="<?=site_url('admin/navigation/edit/' . $nav['id'])?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save")?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/navigation')?>" class="input-submit last"><?=__("Cancel")?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />
		
		<div id="one">
		
			<label for="title"><?=__("Title")?>: </label>
			<input name="title" type='text'  value='<?=$nav['title']?>'  class="input-text" />
			<br />
			
			<label for="uri"><?=__("Target")?>: </label>
			<input name="uri" type='text'  value='<?=$nav['uri']?>'  class="input-text" />
			<br />
			
			<label for="parent_id"><?=__("Parent")?>: </label>
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
			<option value="<?=$parent['id']?>" <?=($nav['parent_id'] == $parent['id'])?"selected":""?>><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title']. $follow ?> </option>
			
			<?php endforeach;?>
			</select>
			<br />
						
	
			<label for="status"><?=__("Status")?>:</label>
				<select name="status" class="input-select" id="status">
					<option value='1' <?php if ($nav['active'] == '1'):?>selected='selected' <?php endif;?>><?=__("Active")?></option>
					<option value='0' <?php if ($nav['active'] == '0'):?>selected='selected' <?php endif;?>><?=__("Disabled")?></option>
				</select><br />

					
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->
