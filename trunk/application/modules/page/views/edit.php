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

<h1 id="edit">Edit page "<?=$page['title']?>"</h1>

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
			
			<label for="menu_title">Navigation Title:</label>
			<input type="text" name="menu_title" value="<?=$page['menu_title']?>" id="menu_title" class="input-text" /><br />
			<?php
				$last_slash = strrpos($page['uri'], '/');
		
				if ($last_slash):
					$parent_uri = substr($page['uri'], 0, $last_slash+1);
					$individual_uri = substr($page['uri'], $last_slash+1, strlen($page['uri']));
				else:
					$parent_uri = '';
					$individual_uri = $page['uri'];
				endif;
			?>
			
			<label for="uri">SEF adress:</label>
			<input type="text" name="uri" value="<?=$individual_uri?>" id="uri" class="input-text" /><br />
			
			<label for="parent">Page Parent</label>
			<select name="parent" id="parent" onchange="change_parent();" class="input-select">
				<option value=''>/</option>
			</select><br />
			
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
	var tabs = new Control.Tabs('tabs',{
		defaultTab: 'last'
	})
	tabs.setActiveTab('one');
</script>
</div>
<!-- [Content] end -->