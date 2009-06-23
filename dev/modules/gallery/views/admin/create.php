<!-- [Left menu] start -->
<div class="leftmenu">

	<h1 id="pageinfo">Image Gallery</h1>
	
	<ul id="tabs" class="quickmenu">
		<li><a href="#one"><?php echo __("Add Image", $this->template['module'])?></a></li>
		<li><a href="#two"><?php echo __("Add Image Category", $this->template['module'])?></a></li>
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
	.after("<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='button' id='upload_now' value='  <?php echo __('Upload', $this->template['module']) ?>  ' />");
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
						$("#image_list").append("<div><input type='hidden' name='image_ids[]' value='"+data.imageid+"' /><a href='#' onclick=\"tinyMCE.execCommand('mceInsertContent',false,'<a href=\\'<?php echo site_url('media/images/o') ?>/"+data.image+"\\'><img border=0 align=left hspace=10 src=\\'<?php echo site_url('media/images/m') ?>/"+data.image+"\\'></a>');return false;\">"+data.image+"</a> - <a href='#'  class=\"ajaxdelete\" id='"+data.imageid+"' ><?php echo __('Delete image', $this->template['module'])?></a></div>\n");
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

<h1 id="edit"><?php echo __("Add Image", $this->template['module'])?></h1>

<form  enctype="multipart/form-data" class="edit" action="<?php echo site_url('admin/gallery/create')?>" method="post" accept-charset="utf-8">
		<input type="hidden" name="lang" value="<?php echo $this->user->lang ?>" />
		<ul>
			<li><input type="submit" name="submit" value="Save" class="input-submit" /></li>
			<li><a href="<?php echo site_url('admin/gallery')?>" class="input-submit last">Cancel</a></li>
		</ul>
		
		<br class="clearfloat" />

		<hr />

		<?php if ($notice = $this->session->flashdata('notification')):?>
		<p class="notice"><?php echo $notice;?></p>
		<?php endif;?>
		
		<div id="one">
		
			<p><?php echo __("To add a new image to the gallery, just fill in your content below and click 'Save'", $this->template['module'])?></p>
	
			<label for="title">Image Title:</label>
			<input type="text" name="name" value="" id="title" class="input-text" /><br />
		
			<label for="author">Taken By:</label>
			<input type="text" name="author" value="" id="author" class="input-text" /><br />
            
            <label for="caption">Caption:</label>
			<input type="text" name="caption" value="" id="caption" class="input-text" /><br />
            <br />
            
            <label for="image">Image:</label>
            <input type="file" name="image" value="" id="image" class="ajaxfileupload" /><br />
            
            <label for="category">Assign Category</label>
            <select name="category_id" class="input-select">
            	<?php foreach($categories as $cat)
						{
							echo "<option value=$cat[id]>".$cat['name']." - ".$cat['description']."</option>";
						}
				?>
            </select><br />
            
			<label for="default"><?php echo __("Default", $this->template['module'])?>: </label>
			<select name="default" class="input-select">
				<option value='0'>No</option>
				<option value='1'>Yes</option>
			</select>
			<br />
		</div>

	</form>
<script>

  $(document).ready(function(){
    $("#tabs").tabs();
  });

</script>	
</div>
<!-- [Content] end -->