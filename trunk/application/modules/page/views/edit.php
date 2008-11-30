<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Page informations</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php _e("Content", $this->template['module'])?></a></li>
		<li><a href="#two"><?php _e("Other fields", $this->template['module'])?></a></li>
		<li><a href="#three"><?php _e("Options", $this->template['module'])?></a></li>
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

$(document).ready(function(){
	$("#image")
	.after("<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='button' id='upload_now' value='  <?php _e('Upload') ?>  ' />");
	$("#loading").hide();
	$("#upload_now").click(function(){
		ajaxFileUpload();
	});
	handleDeleteImage();
});

function handleDeleteImage() {
	$("a.ajaxdelete").click(function(){
		if (confirm("Delete image?"))
		{
		deleteImage(this);
		return false;
		} else {
		return false;
		}
	});
}
function deleteImage(obj) {
	var id = obj.id
	$.post('<?php echo site_url('admin/page/ajax_delete')?>',
		{ id: id},
		function(data){
			alert(data);
		}
	);
	$(obj).parent().hide();
}

function ajaxFileUpload() {
	$("#upload_now")
	.ajaxStart(function(){
		$("img#loading").show();
		$(this).hide();
	})
	.ajaxComplete(function(){
		$("img#loading").hide();
		$(this).show();
	});

	$.ajaxFileUpload
	(
		{
			url:'<?php echo site_url('admin/page/ajax_upload')?>',
			secureuri:false,
			fileElementId: 'image',
			dataType: 'json',
			success: function (data, status)
			{
				if(typeof(data.error) != 'undefined')
				{
					if(data.error != '')
					{
						alert(data.error);
					}else
					{
						$("#image_list").append("<div><input type='hidden' name='image_ids[]' value='"+data.imageid+"' /><a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img align=left border=0 hspace=10 src=\\'<?php echo site_url('media/images/m') ?>/"+data.image+"\\'></a>');return false;\">"+data.image+"</a> - <a href='#'  class=\"ajaxdelete\" id='"+data.imageid+"' ><?php _e('Delete image')?></a></div>\n");
						$("#image").val("");
						handleDeleteImage();
						
					}
				}
			},
			error: function (data, status, e)
			{
				alert(e);
			}
		}
	)
	return false;
}
</script>



<h1 id="edit"><?php _e("Edit page", $this->template['module'])?></h1>

<form enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/page/edit/'.$page['id'])?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		
		<ul>
			<li><input type="submit" name="submit" value="Save page" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/'.$module.'/delete/'.$page['id'])?>" class="input-submit"><?php _e("Delete page", $this->template['module'])?></a></li>
			<li><a href="<?php echo site_url('admin/page')?>" class="input-submit last"><?php _e("Cancel", $this->template['module'])?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<input type="hidden" name="id" value="<?php echo $page['id']?>" />
		<div id="one">
			<label for="title"><?php _e("Page Title:", $this->template['module'])?></label>
			<input type="text" name="title" value="<?php echo $page['title']?>" id="title" class="input-text" /><br />
			
		
			<label for="uri"><?php _e("SEF adress:", $this->template['module'])?></label>
			<input type="text" name="uri" value="<?php echo $page['uri']?>" id="uri" class="input-text" /><br />
			
			<label for="parent_id"><?php _e("Parent", $this->template['module'])?>: </label>
			<select name="parent_id" class="input-select" />
			<option value='0'/>
			<?php $follow = null;
			if($pages):
			foreach ($pages as $parent):?>
			<?php  
					
					if ($page['id'] == $parent['id'] || $follow == $parent['parent_id']) 
					{
						$follow = $page['id']; 
						continue;
					}
					else
					{
					$follow = null;
					}
			?>
			<option value="<?php echo $parent['id']?>" <?php echo ($page['parent_id'] == $parent['id'])?"selected":""?>><?php echo ($parent['level'] > 0) ? "|".str_repeat("__", $parent['level']): ""?> <?php echo $parent['title'] ?> </option>
			
			<?php endforeach;
			endif;?>
			</select>
			<br />
			
			<label for="status"><?php _e("Status", $this->template['module'])?></label>
			<select name="status" id="status" class="input-select">
				<option<?php if ($page['active'] == 0): echo ' selected="selected"'; endif;?> value="0"><?php _e("Draft", $this->template['module'])?></option>
				<option<?php if ($page['active'] == 1): echo ' selected="selected"'; endif;?> value="1"><?php _e("Published", $this->template['module'])?></option>
			</select><br />
			
			<label for="body"><?php _e("Page Content:", $this->template['module'])?></label>
			<textarea name="body" class="input-textarea"><?php echo $page['body']?></textarea>
		
		
		<div id='image_list'>
		<div style="visibility: hidden"><?php _e("Available images:", $this->template['module'])?></div>
		<?php if ($images) : ?>
		<?php foreach($images as $image): ?>
		<div><?php if($image['src_id'] == 0) : ?><input type='hidden' name='image_ids[]' value='<?php echo $image['id'] ?>' /><?php endif; ?><a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<a href=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\'><img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/m')?>/<?php echo $image['file'] ?>\' /></a>');return false;"><?php echo $image['file'] ?></a> - <a href="<?php echo site_url('admin/page/removeimg/' . $image['id']) ?>" class="ajaxdelete" id="<?php echo $image['id'] ?>"><?php _e("Delete image", $this->template['module']) ?></a></div>
		<?php endforeach; ?>
		<?php endif;?>
		</div>
		
		<label for="image"><?php _e("Image", $this->template['module'])?></label>
		<input type="file" name="image" class="input-file" id="image"/><br />		
		
		</div>
		<div id="two">
		
			<label for="meta_keywords"><?php _e("Page keywords:", $this->template['module'])?></label>
			<input type="text" name="meta_keywords" value="<?php echo $page['meta_keywords']?>" id="meta_keywords" class="input-text" /><br />
		
			<label for="meta_description"><?php _e("Page description:", $this->template['module'])?></label>
			<input type="text" name="meta_description" value="<?php echo $page['meta_description']?>" id="meta_description" class="input-text" /><br />
			<?php
			$custom_fields = "";
			
			echo $this->plugin->apply_filters("page_custom_fields", $custom_fields, $page['id']);

			?>
			
		</div>
		<div id="three">
		
			<label for="options[show_subpages]"><?php _e("Show subpages", $this->template['module'])?>:</label>
			<select name="options[show_subpages]" class="input-select" id="show_subpages">
			<option value='0' <?php echo ((isset($page['options']['show_subpages']) && $page['options']['show_subpages']==0)?"selected":"")?>><?php _e("No", $this->template['module'])?></option>
			<option value='1' <?php echo ((isset($page['options']['show_subpages']) && $page['options']['show_subpages']==1)?"selected":"")?>><?php _e("Yes", $this->template['module'])?></option>
			</select><br />

			<label for="options[show_navigation]"><?php _e("Show navigation", $this->template['module'])?>:</label>
			<select name="options[show_navigation]" class="input-select" id="show_navigation">
			<option value='1' <?php echo ((isset($page['options']['show_navigation']) && $page['options']['show_navigation']==1)?"selected":"")?>><?php _e("Yes", $this->template['module'])?></option>
			<option value='0' <?php echo ((isset($page['options']['show_navigation']) && $page['options']['show_navigation']==0)?"selected":"")?>><?php _e("No", $this->template['module'])?></option>
			</select><br />
			
		</div>
	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	

</div>
<!-- [Content] end -->