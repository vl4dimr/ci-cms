<script type="text/javascript">
$(document).ready(function(){
	$(".delete").click(function(){
		if (confirm("<?=addslashes(__("Confirm delete?"))?>"))
		{
		window.location = this+'/1';
		return false;
		} else {
		return false;
		}
	});
	/*handleDeleteImage();*/
});
</script>
<!-- [Content] start -->
<div class="content wide">

<h1 id="delete"><?=__("Delete")?></h1>

<hr />

<p style="margin-bottom: 2em;"><?=__("Confirm?")?>

<form class="delete" action="<?=site_url('admin/news/comments/delete/'.$id.'/1')?>" method="post">
	<p>
		<input type="button" name="noway" value="<?=__("No")?>" onclick="parent.location='<?=site_url('admin/news/comments')?>'" class="input-submit" style="margin-right: 2em;" />
		<input type="submit" name="submit" value="<?=__("Yes")?>" class="input-submit" id="submit" />
	</p>
</form>

</div>
<!-- [Content] end -->