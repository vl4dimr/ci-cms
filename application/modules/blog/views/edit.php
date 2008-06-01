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

<h1 id="edit">Edit Post</h1>

<form class="edit" action="<?=site_url('admin/blog/edit/'.$post['id'])?>" method="post" accept-charset="utf-8">
		
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
	
			<label for="title">Page Title:</label>
			<input type="text" name="title" value="<?=$post['title']?>" id="title" class="input-text" /><br />
			
			<label for="uri">SEF adress:</label>
			<input type="text" name="uri" value="<?=$post['uri']?>" id="uri" class="input-text" /><br />
			
			<label for="status">Status:</label>
			<select name="status" id="status" class="input-select">
				<option <?php if ($post['status'] == 'draft'):?>selected="selected" <?php endif;?>value="draft">Draft</option>
				<option <?php if ($post['status'] == 'published'):?>selected="selected" <?php endif;?>value="published">Published</option>
			</select><br />
			
			<label for="body">Post Content:</label>
			<textarea name="body" class="input-textarea"><?=$post['body']?></textarea><br />
			
			<label for="body">Post Extended:</label>
			<textarea name="ext_body" class="input-textarea"><?=$post['ext_body']?></textarea><br />
		
		</div>
		<div id="two">
		
			<label for="meta_keywords">Post keywords:</label>
			<input type="text" name="meta_keywords" value="<?=$post['meta_keywords']?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description">Post description:</label>
			<input type="text" name="meta_description" value="<?=$post['meta_description']?>" id="meta_description" class="input-text" /><br />
			
			<label for="tags">Tags:</label>
			<input type="text" name="tags" value="<?=$post['tags_string']?>" id="tags" class="input-text" /><br />
			
			<label for="discussion">Discussion:<label>
			<div id="discussion">
				<input <?php if ($post['allow_comments'] == '1'):?>checked="checked" <?php endif; ?>type="checkbox" name="allow_comments" value="1" id="allow_comments" /> Allow Comments?<br />
				<input <?php if ($post['allow_pings'] == '1'):?>checked="checked" <?php endif; ?>type="checkbox" name="allow_pings" value="1" id="allow_pings" /> Allow Trackbacks/Pings?<br />
			</div><br />
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>
</div>
<!-- [Content] end -->