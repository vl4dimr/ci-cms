<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Blog Post</h1>
	
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

<h1 id="edit">Create New Post</h1>

<form class="edit" action="<?=site_url('admin/blog/create')?>" method="post" accept-charset="utf-8">
		
		<ul>
			<li><input type="submit" name="submit" value="Save post" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/blog')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p>To create a new blog post, just fill in your content below and click 'Save Post'.<br />
		If you want to save your progress without publishing the page, make sure the 'Status' field is set to 'Draft'.</p>

		<label for="title">Page Title:</label>
		<input type="text" name="title" value="" id="title" class="input-text" /><br />
		
		<label for="uri">SEF adress:</label>
		<input type="text" name="uri" value="" id="uri" class="input-text" /><br />
		
		<label for="status">Status:</label>
		<select name="status" id="status" class="input-select">
			<option value="draft">Draft</option>
			<option value="published">Published</option>
		</select><br />
		
		<label for="body">Post Content:</label>
		<textarea name="body" class="input-textarea"></textarea><br />
		
		<label for="body">Post Extended:</label>
		<textarea name="ext_body" class="input-textarea"></textarea><br />
		
		</div>
		<div id="two">
			<label for="keywords">Post keywords:</label>
			<input type="text" name="keywords" value="" id="keywords" class="input-text" /><br />
		
			<label for="description">Post description:</label>
			<input type="text" name="description" value="" id="description" class="input-text" /><br />
			
			<label for="tags">Tags:</label>
			<input type="text" name="tags" value="" id="tags" class="input-text" /><br />
			
			<label for="discussion">Discussion:<label>
			<div id="discussion">
				<input checked="checked" type="checkbox" name="allow_comments" value="1" id="allow_comments" /> Allow Comments?<br />
				<input checked="checked" type="checkbox" name="allow_pings" value="1" id="allow_pings" /> Allow Trackbacks/Pings?<br />
			</div>
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