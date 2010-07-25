
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Information", 'links')?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Details", 'links')?></a></li>
		<li><a href="#two"><?=__("Options", 'links')?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?=__("Add new category", 'links')?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/links/category/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?=$row['id']?>" />
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", 'links')?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/links/index/' . $cat['id'])?>" class="input-submit last"><?=__("Cancel", 'links')?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="title"><?=__("Name", 'links')?>:</label>
		<input type="text" name="title" value="<?=$row['title']?>" id="title" class="input-text" /><br />
	
		<label for="pid"><?=__("Parent", 'links')?>:</label>
		<select name='pid' id='pid' class="input-select">
		<option value=''></option>
		<?php if($parents): ?>
		<?php $follow = null; foreach($parents as $parent) : ?>
		<?php
		if ($row['id'] == $parent['id'] || $follow == $parent['pid'] )
		{
			$follow = $row['id']; 
			continue;
		}
		else
		{
			$follow = null;
		}
		?>
			<option value="<?=$parent['id']?>" <?=($row['pid'] == $parent['id'] || $parent['id'] == $cat['id'])?"selected":""?>> &nbsp;<?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

		
		<label for="desc"><?=__("Description", 'links')?>:</label>
		<textarea name="description" class="input-textarea"><?=(isset($row['description'])?$row['description'] : "") ?></textarea><br />

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