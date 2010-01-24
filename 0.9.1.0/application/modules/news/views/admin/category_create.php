
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Category information", $this->template['module'])?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Details", $this->template['module'])?></a></li>
		<li><a href="#two"><?=__("Options", $this->template['module'])?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?=__("Add new category", $this->template['module'])?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/news/category/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?=$row['id']?>" />
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", $this->template['module'])?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/news/category')?>" class="input-submit last"><?=__("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="title"><?=__("Name", $this->template['module'])?>:</label>
		<input type="text" name="title" value="<?=(isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br />

		<label for="title"><?=__("Uri", $this->template['module'])?>:</label>
		<input type="text" name="uri" value="<?=(isset($row['uri'])?$row['uri'] : "") ?>" id="uri" class="input-text" /><br />

		
		<label for="pid"><?=__("Parent", $this->template['module'])?>:</label>
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
			<option value="<?=$parent['id']?>" <?=($row['pid'] == $parent['id'])?"selected":""?>> &nbsp;<?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

		<label for="status"><?=__("Status", $this->template['module'])?>:</label>
		<select name='status' id='status' class="input-select">
		<option value="1" <?=(($row['status']==1)?"selected":"")?>><?=__("Active", $this->template['module'])?></option>
		<option value="0" <?=(($row['status']==0)?"selected":"")?>><?=__("Suspended", $this->template['module'])?></option>
		</select><br />
		
		<label for="desc"><?=__("Description", $this->template['module'])?>:</label>
		<textarea name="desc" class="input-textarea"><?=(isset($row['desc'])?$row['desc'] : "") ?></textarea><br />

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