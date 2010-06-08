<!-- [Content] start -->
<script type="text/javascript">

$(document).ready(function(){
	$("#loading").hide();
	$("#upload_now").click(function(){
		ajaxFileUpload();
		return false;
	});
	$("a.deletefile").click(function(){
		if (confirm("Delete File?"))
		{
		deleteFile(this);
		return false;
		} else {
		return false;
		}
	});	
	/*handleDeleteImage();*/
});

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
			url:'<?php echo site_url('admin/downloads/upload/ajax_file_upload')?>',
			secureuri:false,
			fileElementId: 'file',
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
						$("#file_list tbody").prepend("<tr><td>"+data.filedate+"</td><td>"+data.file+"</td><td>"+data.size+"</td><td><a href='#' class='deletefile' id='"+data.id+"'><?php echo __('Delete file') ?></a></td></tr>");
						$("#file").val("");
						handleDeleteFile();
						
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
function handleDeleteFile() {
	$("a.deletefile").click(function(){
		if (confirm("Delete file?"))
		{
		deleteFile(this);
		return false;
		} else {
		return false;
		}
	});
}

function deleteFile(obj) {
	var id = obj.id
	$.post('<?php echo site_url('admin/downloads/upload/ajax_file_delete')?>',
		{ id: id},
		function(data){
			alert(data);
		}
	);
	$(obj).parent().parent().remove();
}

</script>

<div class="content wide">

<h1 id="page"><?=__("Upload files", 'downloads')?></h1>

<ul class="manage">
	<li><a href="<?=site_url('admin/downloads')?>" class="last"><?=__("Cancel", 'downloads')?></a></li>
</ul>
		
<br class="clearfloat" />

<hr />

<?php if (isset($notice) || $notice = $this->session->flashdata('notification')):?>
<p class="notice"><?=$notice;?></p>
<?php endif;?>

<form  enctype="multipart/form-data" class="edit" action="<?=site_url('admin/downloads/upload/save')?>" method="post" accept-charset="utf-8">
		<label for="file"><?=__("File", 'downloads')?>: </label>
		
		<input type="file" name="file" class="input-file" id="file"/>
<img src='<?php echo site_url('application/views/admin/images/ajax_circle.gif')?>' id='loading'/><input type='submit' id='upload_now' value='  <?php echo __('Upload') ?>  ' />

</form>
		<table id="file_list" class="page-list">
			<thead>
				<tr>
					<th width="10%"><?=__("Date", 'downloads')?></th>
					<th width="72%"><?=__("File", 'downloads')?></th>					
					<th width="8%"><?=__("Size", 'downloads')?></th>					
					<th width="10%"><?=__("Action", 'downloads')?></th>
				</tr>
			</thead>
			<tbody>
		<?php if (isset($rows)) : ?>
		<?php foreach($rows as $file): ?>
		<tr><td><?=date('d/m/Y', $file['date'])?></td><td><a href="<?=site_url('media/files/' . $file['file'])?>"><?=$file['file']?></a></td><td><?=$file['size']?></td><td><a href="<?=site_url('admin/projects/deletefile/' . $file['id']) ?>" class="deletefile" id="<?=$file['id']?>"><?=__("Delete file", 'downloads')?></td></tr>
		<?php endforeach; ?>
		<?php endif;?>
		</tbody>
		</table>
<?=$pager?>
</div>
<!-- [Content] end -->
