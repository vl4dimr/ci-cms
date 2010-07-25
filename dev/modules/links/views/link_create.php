
<h1 id="edit"><?=__("Add new link", 'links')?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('links/link/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		
		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		
		<label for="title"><?=__("Title", 'links')?>:</label>
		<input type="text" name="title" value="<?=(isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br /><br />

		<label for="url"><?=__("Link", 'links')?>:</label>
		<input type="text" name="url" value="<?=(isset($row['url'])?$row['url'] : "") ?>" id="url" class="input-text" /><br />
		
		
		<label for="cat"><?=__("Category", 'links')?>:</label>
		<select name='cat' id='cat' class="input-select">
		<option value='0'></option>
		<?php if($parents): ?>
		<?php $follow = null; foreach($parents as $parent) : ?>
			<option value="<?=$parent['id']?>" <?=($row['cat'] == $parent['id'] || $parent['id'] == $cat)?"selected":""?>> &nbsp;<?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		<?php endforeach; ?>
		<?endif;?> 
		</select><br />

	
		<label for="description"><?=__("Description", 'links')?>:</label>
		<textarea name="description" class="input-textarea"><?=(isset($row['description'])?$row['description'] : "") ?></textarea><br />


		<input type="submit" name="submit" value="<?php echo __("Save", $this->template['module'])?>" class="input-submit" />
		<a href="<?php echo site_url( $this->session->userdata("last_uri") )?>" class="input-submit"><?php echo __("Cancel", $this->template['module'])?></a>

		
		
</form>
<!-- [Content] end -->