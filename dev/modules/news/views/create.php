<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo"><?=__("Quick links")?></h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?=__("Content")?></a></li>
		<li><a href="#two"><?=__("Options")?></a></li>
	</ul>
	<div class="quickend"></div>

</div>
<!-- [Left menu] end -->

<!-- [Content] start -->
<div class="content slim">

<script type="text/javascript">
$(document).ready(function(){
	$("#image")
	.after("<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='button' id='upload_now' value='  <?php echo __('Upload') ?>  ' />");
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
	$.post('<?php echo site_url('admin/news/ajax_delete')?>',
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
			url:'<?php echo site_url('admin/news/ajax_upload')?>',
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
						$("#image_list").append("<div><input type='hidden' name='image_ids[]' value='"+data.imageid+"' /><a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/m') ?>/"+data.image+"\\'></a>');return false;\">"+data.image+"</a> - <a href='#'  class=\"ajaxdelete\" id='"+data.imageid+"' ><?php echo __('Delete image')?></a></div>\n");
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

<h1 id="edit"><?=(isset($row['id'])? __("Edit news"):__("Create News"))?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/news/save')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="id" value="<?=(isset($row['id'])?$row['id'] : "") ?>" />
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="<?=__("Save")?>" class="input-submit" /></li>
			<li><a href="<?=site_url('admin/news')?>" class="input-submit last"><?=__("Cancel")?></a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?=$notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
		<p><?=__("To create a news, just fill in your content below and click 'Save'.<br />If you want to save your progress without publishing it, Select 'Draft' status.</p>")?>

		<label for="title"><?=__("Title")?>:</label>
		<input type="text" name="title" value="<?=(isset($row['title'])?$row['title'] : "") ?>" id="title" class="input-text" /><br />
		
		<label for="status"><?=__("Status")?>:</label>
		<select name="status" id="status" class="input-select">
			<option value="1" <?=(isset($row['status']) && $row['status'] == 1)? "selected"  : "" ?>><?=__("Published")?></option>
			<option value="0" <?=(isset($row['status']) && $row['status'] == 0)? "selected"  : "" ?>><?=__("Draft")?></option>
		</select><br />
		
		<label for="body"><?=__("Content")?>:</label>
		<textarea name="body" class="input-textarea"><?=(isset($row['body'])?$row['body'] : "") ?></textarea><br />

		<div id='image_list'>
		<div style="visibility: hidden">Available images:</div>
		<?php if ($images) : ?>
		<?php foreach($images as $image): ?>
		<div><input type='hidden' name='image_ids[]' value='<?php echo $image['id'] ?>' /><a href='#' onclick="tinyMCE.execCommand('mceInsertContent',false,'<a href=\'<?php echo site_url('media/images/o')?>/<?php echo $image['file'] ?>\'><img border=\'0\' align=\'left\' hspace=\'10\' src=\'<?php echo site_url('media/images/m')?>/<?php echo $image['file'] ?>\' /></a>');return false;"><?php echo $image['file'] ?></a> - <a href="<?php echo site_url('admin/news/removeimg/' . $image['id']) ?>" class="ajaxdelete" id="<?php echo $image['id'] ?>"><?php echo __("Delete image") ?></a></div>
		<?php endforeach; ?>
		<?php endif;?>
		</div>
		
		<label for="image"><?=__("Image")?></label>
		<input type="file" name="image" class="input-file" id="image"/><br />
		</div>
		<div id="two">
		
			<label for="allow_comments"><?=__("Allow Comments")?>:</label>
			<select name="allow_comments" class="input-select" id="allow_comments">
			<option value='1' <?=(($row['allow_comments']==1)?"selected":"")?>><?=__("Yes")?></option>
			<option value='0' <?=(($row['allow_comments']==0)?"selected":"")?>><?=__("No")?></option>
			</select><br />

			<label for="notify"><?=__("Notify me for comments")?>:</label>
			<select name="allow_comments" class="input-select" id="allow_comments">
			<option value='1' <?=(($row['notify']==1)?"selected":"")?>><?=__("Yes")?></option>
			<option value='0' <?=(($row['notify']==0)?"selected":"")?>><?=__("No")?></option>
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