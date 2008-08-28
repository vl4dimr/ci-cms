
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Information", 'downloads')?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Details", 'downloads')?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">
<h1 id="edit"><?=__("Add new document", 'downloads')?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/downloads/document/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?=$row['id']?>" />
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save", 'downloads')?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/downloads/index/'.$cat)?>" class="input-submit last"><?=__("Cancel", 'downloads')?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<label for="title"><?=__("Name", 'downloads')?>:</label>
		<input type="text" name="title" value="<?=(isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br /><br />
		<strong><?=__("File", 'downloads')?>:</strong><br />
		<label for="file_id"><?=__("Downloaded", 'downloads')?>:</label>
		<select name='file_id' id='file_id' class="input-select">
		<option value=''></option>
		<?php if($files): ?>
		<?php foreach($files as $file) : ?>
			<option value="<?=$file['id']?>" <?=(isset($row['file_id']) && $row['file_id'] == $file['id'])?"selected":""?>> <?=$file['file'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

		<label for="file_link"><?=__("or Link", 'downloads')?>:</label>
		<input type="text" name="file_link" value="<?=(isset($row['file_link'])?$row['file_link'] : "") ?>" id="file_link" class="input-text" /><br />
		
		
		<label for="cat"><?=__("Category", 'downloads')?>:</label>
		<select name='cat' id='cat' class="input-select">
		<option value='0'></option>
		<?php if($parents): ?>
		<?php $follow = null; foreach($parents as $parent) : ?>
			<option value="<?=$parent['id']?>" <?=($row['cat'] == $parent['id'] || $parent['id'] == $cat)?"selected":""?>> &nbsp;<?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

		<label for="status"><?=__("Status", 'downloads')?>:</label>
		<select name='status' id='status' class="input-select">
		<option value="1" <?=(($row['status']==1)?"selected":"")?>><?=__("Active", 'downloads')?></option>
		<option value="0" <?=(($row['status']==0)?"selected":"")?>><?=__("Suspended", 'downloads')?></option>
		</select><br />
		
		<label for="keywords"><?=__("Keywords", 'downloads')?>:</label>
		<input type="text" name="keywords" value="<?=(isset($row['keywords'])?$row['keywords'] : "") ?>" id="keywords" class="input-text" /><br />

		
		<label for="desc"><?=__("Description", 'downloads')?>:</label>
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