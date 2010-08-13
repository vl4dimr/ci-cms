
<div class="leftmenu">

	<h1 id="pageinfo"><?php echo __("Information", $module)?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Details", $module)?></a></li>
		<li><a href="#two"><?php echo __("Options", $module)?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?php echo __("Add new category", $module)?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/institute/classes/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?php echo $row['id']?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?php echo __("Save", $module)?>" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/institute/classes')?>" class="input-submit last"><?php echo __("Cancel", $module)?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="c_name"><?php echo __("Name", $module)?>:</label>
		<input type="text" name="c_name" value="<?php echo $row['c_name']?>" id="c_name" class="input-text" /><br />
	
		<label for="c_parent"><?php echo __("Parent", $module)?>:</label>
		<select name='c_parent' id='c_parent' class="input-select">
		<option value=''></option>
		<?php if($parents): ?>
		<?php $follow = null; foreach($parents as $parent) : ?>
		<?php
		if ($row['id'] == $parent['id'] || $follow == $parent['c_parent'] )
		{
			$follow = $row['id']; 
			continue;
		}
		else
		{
			$follow = null;
		}
		?>
			<option value="<?php echo $parent['id']?>" <?php echo ($row['c_parent'] == $parent['id'] || $parent['id'] == $cat['id'])?"selected":""?>> &nbsp;<?php echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php echo $parent['c_name'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

		
		<label for="c_description"><?php echo __("Description", $module)?>:</label>
		<textarea name="c_description" class="input-textarea"><?php echo (isset($row['c_description'])?$row['c_description'] : "") ?></textarea><br />

		</div>
		<div id="two">
		
		
	
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->