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

<h1 id="edit"><?=__("Create New Page")?></h1>

<form class="edit" action="<?=site_url('admin/page/create')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save page" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/page')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?=__("To create a new page, just fill in your content below and click 'Save page'.<br />If you want to save your progress without publishing the page, Select 'Draft' status.</p>")?>

		<label for="title"><?=__("Page Title")?>:</label>
		<input type="text" name="title" value="" id="title" class="input-text" /><br />
		

		
		<label for="uri"><?=__("SEF adress")?>:</label>
		<input type="text" name="uri" value="" id="uri" class="input-text" /><br />
		
		<label for="parent_id"><?=__("Parent")?>: </label>
		<select name="parent_id" class="input-select" />
		<option value='0'/>
		<?php foreach ($this->pages->list_pages() as $parent):?>

		<option value="<?=$parent['id']?>"><?=($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?=$parent['title'] ?> </option>
		
		<?php endforeach;?>
		</select>
		<br />
			
		<label for="status">Status:</label>
		<select name="status" id="status" class="input-select">
			<option value="0">Draft</option>
			<option value="1">Published</option>
		</select><br />
		
		<label for="body">Page Content:</label>
		<textarea name="body" class="input-textarea"></textarea>
		
		</div>
		<div id="two">
		
			<label for="meta_keywords">Page keywords:</label>
			<input type="text" name="meta_keywords" value="" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description">Page description:</label>
			<input type="text" name="meta_description" value="" id="meta_description" class="input-text" />
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->