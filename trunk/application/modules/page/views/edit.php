<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one">Content</a></li>
		<li><a href="#two">Meta data</a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<script type="text/javascript">
function change_parent() {
	selected = document.getElementById('parent').selectedIndex;
	document.getElementById('pre_uri').innerHTML = '/'+document.getElementById('parent').options[selected].value;
}
</script>

<h1 id="edit"><?=__("Edit page")?></h1>

<form class="edit" action="<?=site_url('admin/page/edit/'.$page['id'])?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save page" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/'.$module.'/delete/'.$page['id'])?>" class="input-submit">Delete page</a></li>
			<li><a href="<?=site_url('admin/page')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<input type="hidden" name="id" value="<?=$page['id']?>" />
		<div id="one">
			<label for="title">Page Title:</label>
			<input type="text" name="title" value="<?=$page['title']?>" id="title" class="input-text" /><br />
			
		
			<label for="uri">SEF adress:</label>
			<input type="text" name="uri" value="<?=$page['uri']?>" id="uri" class="input-text" /><br />
			
			<label for="parent_id"><?=__("Parent")?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php $follow = null;
			foreach ($pages as $parent):?>
			<?php  
					
					if ($page['id'] == $parent['id'] || $follow == $parent['parent_id']) 
					{
						$follow = $parent['id']; 
						continue;
					}
					else
					{
					$follow = null;
					}
			?>
			<option value="<?=$parent['id']?>" <?=($page['parent_id'] == $parent['id'])?"selected":""?>><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
			
			<?php endforeach;?>
			</select>
			<br />
			
			<label for="status">Status</label>
			<select name="status" id="status" class="input-select">
				<option<?php if ($page['active'] == 0): echo ' selected="selected"'; endif;?> value="0">Draft</option>
				<option<?php if ($page['active'] == 1): echo ' selected="selected"'; endif;?> value="1">Published</option>
			</select><br />
			
			<label for="body">Page Content:</label>
			<textarea name="body" class="input-textarea"><?=$page['body']?></textarea>
		</div>
		<div id="two">
		
			<label for="meta_keywords">Page keywords:</label>
			<input type="text" name="meta_keywords" value="<?=$page['meta_keywords']?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description">Page description:</label>
			<input type="text" name="meta_description" value="<?=$page['meta_description']?>" id="meta_description" class="input-text" />
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	

</div>
<!-- [Content] end -->